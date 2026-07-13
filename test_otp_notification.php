<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

use App\Models\User;
use App\Notifications\SendOtpNotification;

echo "=== TEST SENDOTPNOTIFICATION to madebegundal@gmail.com ===\n\n";

// Cari user
$user = User::where('email', 'madebegundal@gmail.com')->first();
if (!$user) {
    echo "USER NOT FOUND in database!\n";
    exit(1);
}

echo "User found: ID={$user->id}, Name={$user->name}, Email={$user->email}\n\n";

// Generate OTP
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
echo "Generated OTP: $otp\n\n";

// Send notification - this is EXACTLY what the application does
echo "Attempting to send notification...\n";
try {
    $user->notify(new SendOtpNotification($otp));
    echo "SUCCESS: Notification sent without exception!\n";
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    echo "Class: " . get_class($e) . "\n";
    $prev = $e->getPrevious();
    if ($prev) {
        echo "Previous: " . get_class($prev) . ": " . $prev->getMessage() . "\n";
    }
}

// Also verify mail config
echo "\n=== CURRENT MAIL CONFIG ===\n";
echo "MAIL_MAILER: " . env('MAIL_MAILER', 'NOT SET') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST', 'NOT SET') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT', 'NOT SET') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME', 'NOT SET') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'NOT SET') . "\n";
echo "QUEUE_CONNECTION: " . env('QUEUE_CONNECTION', 'NOT SET') . "\n";

// Swift mailer preferences check
echo "\n=== TRANSPORT CHECK ===\n";
try {
    $mailer = app('mailer');
    $symfonyMailer = $mailer->getSymfonyTransport();
    echo "Transport class: " . get_class($symfonyMailer) . "\n";
    
    if (method_exists($symfonyMailer, 'getStream')) {
        // SwiftMailer style
        echo "Stream: " . get_class($symfonyMailer->getStream()) . "\n";
    }
} catch (Exception $e) {
    echo "Error inspecting transport: " . $e->getMessage() . "\n";
}
