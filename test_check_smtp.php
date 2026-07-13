<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Mail;

echo "=== TEST: Send with VERBOSE Logging ===\n";

// Enable mail log
config(['mail.mailers.smtp.stream' => [
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
]]);

// Send a simple test with try/catch to capture exact SMTP response
try {
    Mail::raw('This is a test to verify SMTP delivery to madebegundal at ' . date('Y-m-d H:i:s'), function ($msg) {
        $msg->to('madebegundal@gmail.com', 'Made Begundal')
            ->from('vizziocraft@gmail.com', 'VizzioDocs')
            ->subject('VERIFICATION TEST - VizzioDocs ' . date('Y-m-d H:i:s'));
    });
    echo "Mail::raw() - SUCCESS (no exception)\n";
} catch (Exception $e) {
    echo "Mail::raw() - FAILED: " . $e->getMessage() . "\n";
    echo "Class: " . get_class($e) . "\n";
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        $prev = $e->getPrevious();
        echo "Previous: " . get_class($prev) . ": " . $prev->getMessage() . "\n";
    }
}

// Also verify we can resolve smtp.gmail.com
echo "\n=== DNS CHECK ===\n";
$host = gethostbynamel('smtp.gmail.com');
if ($host) {
    echo "smtp.gmail.com resolves to: " . implode(', ', $host) . "\n";
} else {
    echo "smtp.gmail.com FAILED DNS resolution!\n";
}

// Check Mailpit port
echo "\n=== MAILPIT CHECK ===\n";
$fp = @fsockopen('127.0.0.1', 8025, $errno, $errstr, 2);
if ($fp) {
    echo "Mailpit HTTP (8025): RUNNING\n";
    fclose($fp);
} else {
    echo "Mailpit HTTP (8025): NOT REACHABLE ($errstr)\n";
}

$fp = @fsockopen('127.0.0.1', 1025, $errno, $errstr, 2);
if ($fp) {
    echo "Mailpit SMTP (1025): RUNNING\n";
    fclose($fp);
} else {
    echo "Mailpit SMTP (1025): NOT REACHABLE ($errstr)\n";
}

// Check if Gmail SMTP is reachable
echo "\n=== SMTP.GMAIL.COM CHECK ===\n";
$fp = @fsockopen('smtp.gmail.com', 587, $errno, $errstr, 5);
if ($fp) {
    echo "smtp.gmail.com:587 - REACHABLE\n";
    fread($fp, 1024);
    fclose($fp);
} else {
    echo "smtp.gmail.com:587 - NOT REACHABLE ($errstr)\n";
}

$fp = @fsockopen('smtp.gmail.com', 465, $errno, $errstr, 5);
if ($fp) {
    echo "smtp.gmail.com:465 - REACHABLE\n";
    fclose($fp);
} else {
    echo "smtp.gmail.com:465 - NOT REACHABLE ($errstr)\n";
}
