<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\CoreApi;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function getClientKey(): string
    {
        return Config::$clientKey;
    }

    /**
     * Create transaction via Midtrans Core API.
     */
    public function createTransaction(\App\Models\Payment $payment, string $paymentMethod): array
    {
        $transactionDetails = [
            'order_id' => $payment->invoice,
            'gross_amount' => (int) $payment->final_price,
        ];

        $itemDetails = [
            [
                'id' => $payment->plan,
                'price' => (int) $payment->final_price,
                'quantity' => 1,
                'name' => 'Paket ' . ucfirst($payment->plan) . ' - VizzioDocs Premium',
            ],
        ];

        $customerDetails = [
            'first_name' => $payment->user->name,
            'email' => $payment->user->email,
        ];

        // Prepare payment-specific parameters
        $params = [
            'payment_type' => $this->mapPaymentMethod($paymentMethod),
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        // Add payment method specific parameters
        $this->addPaymentMethodParams($params, $paymentMethod);

        $response = CoreApi::charge($params);

        return json_decode(json_encode($response), true);
    }

    /**
     * Check transaction status from Midtrans.
     */
    public function checkStatus(string $orderId): array
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            return json_decode(json_encode($status), true);
        } catch (\Exception $e) {
            return [
                'status_code' => '404',
                'transaction_status' => 'not_found',
            ];
        }
    }

    /**
     * Map payment method to Midtrans payment_type.
     */
    private function mapPaymentMethod(string $method): string
    {
        $map = [
            'qris' => 'qris',
            'dana' => 'gopay',       // e-wallet via gopay mechanism
            'gopay' => 'gopay',
            'shopeepay' => 'gopay',  // e-wallet via gopay mechanism
            'bca' => 'bank_transfer',
            'bni' => 'bank_transfer',
            'mandiri' => 'bank_transfer',
            'bca_va' => 'bank_transfer',
            'bni_va' => 'bank_transfer',
            'mandiri_va' => 'bank_transfer',
        ];

        return $map[$method] ?? 'bank_transfer';
    }

    /**
     * Add payment method specific parameters to the request.
     */
    private function addPaymentMethodParams(array &$params, string $method): void
    {
        $paymentType = $this->mapPaymentMethod($method);

        if ($paymentType === 'bank_transfer') {
            $bankMap = [
                'bca' => 'bca',
                'bca_va' => 'bca',
                'bni' => 'bni',
                'bni_va' => 'bni',
                'mandiri' => 'mandiri',
                'mandiri_va' => 'mandiri',
            ];

            $bank = $bankMap[$method] ?? 'bca';

            $params['bank_transfer'] = [
                'bank' => $bank,
            ];
        } elseif ($paymentType === 'gopay') {
            $params['gopay'] = [
                'enable_callback' => false,
            ];
        } elseif ($paymentType === 'qris') {
            $params['qris'] = [
                'acquirer' => 'gopay',
            ];
        }
    }
}
