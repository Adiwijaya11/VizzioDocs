<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Show the payment page for a given transaction.
     */
    public function show($transactionId)
    {
        $payment = Payment::where('invoice', $transactionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if expired
        if ($payment->expired_at && now()->greaterThan($payment->expired_at)) {
            if ($payment->transaction_status === 'pending') {
                $payment->update(['transaction_status' => 'expired']);
            }
        }

        $clientKey = $this->midtransService->getClientKey();

        return view('payment', compact('payment', 'clientKey'));
    }

    /**
     * Process payment with selected method via Midtrans Core API.
     */
    public function process(Request $request, $transactionId)
    {
        $payment = Payment::where('invoice', $transactionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validate payment method
        $request->validate([
            'payment_method' => 'required|in:qris,bca,bni,mandiri,gopay,dana,shopeepay',
        ]);

        // Check if payment is already paid
        if ($payment->transaction_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran ini sudah lunas.',
            ], 400);
        }

        // Check if expired
        if ($payment->expired_at && now()->greaterThan($payment->expired_at)) {
            $payment->update(['transaction_status' => 'expired']);
            return response()->json([
                'success' => false,
                'message' => 'Waktu pembayaran telah habis. Silakan lakukan upgrade kembali.',
            ], 400);
        }

        try {
            $response = $this->midtransService->createTransaction($payment, $request->payment_method);

            $statusCode = $response['status_code'] ?? '500';

            if ($statusCode === '201' || $statusCode === '200') {
                // Update payment record with method and Midtrans info
                $payment->update([
                    'payment_method' => $request->payment_method,
                    'midtrans_transaction_id' => $response['transaction_id'] ?? null,
                    'transaction_status' => 'pending',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil dibuat.',
                    'data' => $response,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $response['status_message'] ?? 'Gagal memproses pembayaran.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check payment status from Midtrans.
     */
    public function status($transactionId)
    {
        $payment = Payment::where('invoice', $transactionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $midtransStatus = $this->midtransService->checkStatus($payment->invoice);

        // Update local status based on Midtrans response if not already paid locally
        if ($payment->transaction_status !== 'paid') {
            $transactionStatus = $midtransStatus['transaction_status'] ?? 'pending';

            switch ($transactionStatus) {
                case 'settlement':
                case 'capture':
                    $payment->update(['transaction_status' => 'paid', 'paid_at' => now()]);
                    // Activate premium
                    app(\App\Services\PaymentService::class)->activatePremiumUser($payment->user, $payment->plan);
                    break;
                case 'expire':
                    $payment->update(['transaction_status' => 'expired']);
                    break;
                case 'deny':
                case 'cancel':
                    $payment->update(['transaction_status' => 'failed']);
                    break;
                case 'pending':
                    // Check if expired
                    if ($payment->expired_at && now()->greaterThan($payment->expired_at)) {
                        $payment->update(['transaction_status' => 'expired']);
                    }
                    break;
            }
        }

        // Refresh payment
        $payment->refresh();

        return response()->json([
            'success' => true,
            'payment' => [
                'transaction_status' => $payment->transaction_status,
                'invoice' => $payment->invoice,
                'paid_at' => $payment->paid_at,
            ],
            'midtrans' => $midtransStatus,
        ]);
    }
}
