<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$results = Illuminate\Support\Facades\DB::select('SELECT id, invoice, transaction_status, payment_method, final_price, user_id FROM payments ORDER BY id DESC LIMIT 10');
foreach ($results as $r) {
    echo "{$r->id} | {$r->invoice} | user:{$r->user_id} | {$r->transaction_status} | " . ($r->payment_method ?? '-') . " | {$r->final_price}\n";
}
