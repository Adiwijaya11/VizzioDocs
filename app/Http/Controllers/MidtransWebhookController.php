<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Midtrans Core API notification (webhook).
     * Midtrans will POST to this URL for status updates.
     */
    public function notification(Request $request)
    {
        // Log incoming notification for debugging
        \Illuminate\Support\Facades\Log::info('Midtrans notification received', $request->all());

        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        // If no order_id, try to get from transaction_details
        if (!$orderId) {
            $orderId = $request->input('transaction_details.order_id');
        }
        if (!$transactionStatus) {
            $transactionStatus = $request->input('transaction_status');
        }

        if (!$orderId || !$transactionStatus) {
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        // Verify signature
        $serverKey = config('midtrans.server_key');
        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey && $calculatedSignature !== $signatureKey) {
            \Illuminate\Support\Facades\Log::warning('Invalid Midtrans signature', [
                'order_id' => $orderId,
                'calculated' => $calculatedSignature,
                'received' => $signatureKey,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find the payment
        $payment = Payment::where('invoice', $orderId)->first();

        if (!$payment) {
            \Illuminate\Support\Facades\Log::warning('Payment not found for Midtrans order', [
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // If already marked as paid, skip
        if ($payment->transaction_status === 'paid') {
            return response()->json(['message' => 'OK']);
        }

        // Process based on transaction status from Midtrans
        switch ($transactionStatus) {
            case 'settlement':
            case 'capture':
                $payment->update([
                    'transaction_status' => 'paid',
                    'paid_at' => now(),
                ]);
                $this->paymentService->activatePremiumUser($payment->user, $payment->plan);
                break;

            case 'expire':
                $payment->update(['transaction_status' => 'expired']);
                break;

            case 'deny':
            case 'cancel':
                $payment->update(['transaction_status' => 'failed']);
                break;

            case 'pending':
                // Still pending — do nothing, just acknowledge
                break;
        }

        return response()->json(['message' => 'OK']);
    }
}
