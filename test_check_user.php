<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\User;

// Check if user exists
$user = User::where('email', 'madebegundal@gmail.com')->first();
if ($user) {
    echo "FOUND: {$user->name} (id={$user->id})\n";
} else {
    echo "madebegundal@gmail.com NOT FOUND in users table\n";
}

// Check if any mail logs exist
echo "\n--- Password Reset OTPs ---\n";
$otps = DB::table('password_reset_otps')->where('email', 'madebegundal@gmail.com')->orderBy('created_at', 'desc')->get();
if ($otps->count() > 0) {
    foreach ($otps as $otp) {
        echo "OTP: {$otp->otp} | created: {$otp->created_at} | expires: {$otp->expires_at}\n";
    }
} else {
    echo "No OTP records found\n";
}

// Check recent mail log
echo "\n--- Recent Error Logs ---\n";
$logPath = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logPath)) {
    $lines = file($logPath);
    $recent = array_slice($lines, -20);
    foreach ($recent as $line) {
        echo $line;
    }
}
