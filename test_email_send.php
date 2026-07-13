<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Notifications\SendOtpNotification;

$user = User::where('email', 'madebegundal@gmail.com')->first();
if (!$user) {
    echo "User not found!\n";
    exit(1);
}

echo "User: {$user->name} ({$user->email})\n";

$otp = '123456';
try {
    $user->notify(new SendOtpNotification($otp));
    echo "EMAIL SENT SUCCESSFULLY!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
