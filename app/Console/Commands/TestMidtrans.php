<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MidtransService;
use Midtrans\Config;

class TestMidtrans extends Command
{
    protected $signature = 'midtrans:test';
    protected $description = 'Test Midtrans with a fresh transaction';

    public function handle()
    {
        $this->info('=== Midtrans Config Test ===');

        // Instantiate service to init Config
        $service = new MidtransService();

        $this->info('ServerKey ends with: ...' . substr(Config::$serverKey ?? '', -8));
        $this->info('ClientKey ends with: ...' . substr(Config::$clientKey ?? '', -8));
        $this->info('isProduction: ' . (Config::$isProduction ? 'true' : 'false'));

        // Use a truly unique order_id for testing
        $testOrderId = 'TEST-QRIS-' . time() . '-' . rand(100, 999);

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $testOrderId,
                'gross_amount' => 10000,
            ],
            'item_details' => [
                [
                    'id' => 'test',
                    'price' => 10000,
                    'quantity' => 1,
                    'name' => 'Test QRIS Payment',
                ],
            ],
            'customer_details' => [
                'first_name' => 'Test',
                'email' => 'test@example.com',
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
        ];

        $this->info("\nCharging with order_id: " . $testOrderId);
        $this->info('Params: ' . json_encode($params, JSON_PRETTY_PRINT));

        try {
            $response = \Midtrans\CoreApi::charge($params);
            $responseArr = json_decode(json_encode($response), true);

            $this->info("\n=== RESPONSE ===");
            $this->info(json_encode($responseArr, JSON_PRETTY_PRINT));

            if (isset($responseArr['actions'])) {
                $this->info("\nActions:");
                foreach ($responseArr['actions'] as $a) {
                    $this->info(' - ' . ($a['name'] ?? '?') . ': ' . ($a['url'] ?? '?'));
                }
            } else {
                $this->warn("\nNo 'actions' in response!");
            }

            if (isset($responseArr['status_code'])) {
                $this->info("\nStatus code: " . $responseArr['status_code']);
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}
