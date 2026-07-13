<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

// Check what config is actually loaded
echo "=== MAIL CONFIG ===\n";
echo "default: " . config('mail.default') . "\n";
echo "mailer: " . config('mail.mailers.smtp.transport') . "\n";
echo "host: " . config('mail.mailers.smtp.host') . "\n";
echo "port: " . config('mail.mailers.smtp.port') . "\n";
echo "username: " . config('mail.mailers.smtp.username') . "\n";
echo "password: " . (config('mail.mailers.smtp.password') ? 'SET' : 'NOT SET') . "\n";
echo "encryption: " . config('mail.mailers.smtp.encryption') . "\n";
echo "from_address: " . config('mail.from.address') . "\n";
echo "from_name: " . config('mail.from.name') . "\n";
echo "queue: " . config('queue.default') . "\n";

echo "\n=== ENV VALUES ===\n";
$envFile = file_get_contents(__DIR__ . '/.env');
preg_match('/MAIL_USERNAME=(.*)/', $envFile, $m);
echo "MAIL_USERNAME from .env: " . ($m[1] ?? 'NOT FOUND') . "\n";
preg_match('/MAIL_PASSWORD="?(.*?)"?(\r?\n|$)/', $envFile, $m);
echo "MAIL_PASSWORD from .env: " . ($m[1] ? 'SET (' . strlen($m[1]) . ' chars)' : 'NOT SET') . "\n";
preg_match('/MAIL_FROM_ADDRESS=(.*)/', $envFile, $m);
echo "MAIL_FROM_ADDRESS from .env: " . ($m[1] ?? 'NOT FOUND') . "\n";
preg_match('/QUEUE_CONNECTION=(.*)/', $envFile, $m);
echo "QUEUE_CONNECTION from .env: " . ($m[1] ?? 'NOT FOUND') . "\n";

// Check if the server's php.ini has SMTP settings
echo "\n=== PHP MAIL CONFIG ===\n";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
echo "SMTP: " . ini_get('SMTP') . "\n";
echo "smtp_port: " . ini_get('smtp_port') . "\n";
