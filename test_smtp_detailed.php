<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Mail;

echo "=== DETAILED SMTP TEST ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

// Test 1: Laravel Mail with port 587
echo "--- Test 1: Laravel Mail (port 587 TLS) ---\n";
try {
    Mail::raw('Test from VizzioDocs via 587 at ' . date('Y-m-d H:i:s'), function ($msg) {
        $msg->to('madebegundal@gmail.com', 'Made Begundal')
            ->from('vizziocraft@gmail.com', 'VizzioDocs')
            ->subject('SMTP Test 587 - VizzioDocs');
    });
    echo "✓ Test 1: Sent via port 587\n";
} catch (Exception $e) {
    echo "✗ Test 1 FAILED: " . $e->getMessage() . "\n";
}

// Test 2: Override with port 465
echo "\n--- Test 2: Laravel Mail (port 465 SSL) ---\n";
try {
    // Temporarily override mail config
    config(['mail.mailers.smtp.port' => 465]);
    config(['mail.mailers.smtp.encryption' => 'ssl']);
    
    Mail::raw('Test from VizzioDocs via 465 at ' . date('Y-m-d H:i:s'), function ($msg) {
        $msg->to('madebegundal@gmail.com', 'Made Begundal')
            ->from('vizziocraft@gmail.com', 'VizzioDocs')
            ->subject('SMTP Test 465 - VizzioDocs');
    });
    echo "✓ Test 2: Sent via port 465\n";
    
    // Restore
    config(['mail.mailers.smtp.port' => 587]);
    config(['mail.mailers.smtp.encryption' => 'tls']);
} catch (Exception $e) {
    echo "✗ Test 2 FAILED: " . $e->getMessage() . "\n";
    config(['mail.mailers.smtp.port' => 587]);
    config(['mail.mailers.smtp.encryption' => 'tls']);
}

// Test 3: Send to another Gmail (vizziocraft@gmail.com itself) for self-testing
echo "\n--- Test 3: Send to SELF (vizziocraft@gmail.com) ---\n";
try {
    Mail::raw('This is a self-test from VizzioDocs. If you see this, SMTP works. Time: ' . date('Y-m-d H:i:s'), function ($msg) {
        $msg->to('vizziocraft@gmail.com', 'VizzioCraft')
            ->from('vizziocraft@gmail.com', 'VizzioDocs')
            ->subject('SELF-TEST - VizzioDocs SMTP Test');
    });
    echo "✓ Test 3: Sent to self (vizziocraft@gmail.com)\n";
} catch (Exception $e) {
    echo "✗ Test 3 FAILED: " . $e->getMessage() . "\n";
}

// Test 4: With extra headers for deliverability
echo "\n--- Test 4: With DKIM-like headers ---\n";
try {
    Mail::send([], [], function ($msg) {
        $msg->to('madebegundal@gmail.com', 'Made Begundal')
            ->from('vizziocraft@gmail.com', 'VizzioDocs')
            ->subject('Important - VizzioDocs Account Notification')
            ->html('<h1>Test Email</h1><p>This is a test with proper HTML formatting from VizzioDocs at ' . date('Y-m-d H:i:s') . '</p>');
        
        // Add custom headers
        $msg->getSymfonyMessage()->getHeaders()->addTextHeader('X-Priority', '1');
        $msg->getSymfonyMessage()->getHeaders()->addTextHeader('X-MSMail-Priority', 'High');
    });
    echo "✓ Test 4: Sent with priority headers\n";
} catch (Exception $e) {
    echo "✗ Test 4 FAILED: " . $e->getMessage() . "\n";
    echo "  " . $e->getClass() . ": " . $e->getMessage() . "\n";
}

echo "\n=== DONE ===\n";
