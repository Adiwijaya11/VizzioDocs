<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\User;
use Illuminate\Support\Facades\Mail;

$user = User::where('email', 'madebegundal@gmail.com')->first();

// Test 1: Send raw email via Mail::raw()
echo "=== Test 1: Mail::raw() ===\n";
try {
    Mail::raw('Test email dari VizzioDocs - ' . date('Y-m-d H:i:s'), function ($msg) use ($user) {
        $msg->to($user->email, $user->name)
            ->subject('Test Direct - VizzioDocs');
    });
    echo "Mail::raw() BERHASIL (no exception)\n";
} catch (Exception $e) {
    echo "Mail::raw() ERROR: " . $e->getMessage() . "\n";
}

// Test 2: Send notification via notify()
echo "\n=== Test 2: notify() with SendOtpNotification ===\n";
try {
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $user->notify(new App\Notifications\SendOtpNotification($otp));
    echo "notify() BERHASIL (no exception)\n";
    echo "OTP: $otp\n";
} catch (Exception $e) {
    echo "notify() ERROR: " . $e->getMessage() . "\n";
}

// Test 3: Send to a different email (vizziocraft) to compare
echo "\n=== Test 3: Mail::raw() to vizziocraft@gmail.com ===\n";
try {
    Mail::raw('Test email ke sender sendiri - ' . date('Y-m-d H:i:s'), function ($msg) {
        $msg->to('vizziocraft@gmail.com')
            ->subject('Test Self - VizzioDocs');
    });
    echo "Mail to vizziocraft BERHASIL\n";
} catch (Exception $e) {
    echo "Mail to vizziocraft ERROR: " . $e->getMessage() . "\n";
}
