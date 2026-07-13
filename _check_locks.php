<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tools = App\Models\ToolLock::all();
foreach ($tools as $t) {
    echo $t->id . ' | ' . $t->tool_slug . ' | ' . $t->tool_name . ' | ' . $t->tool_route . ' | ' . ($t->is_locked ? 'locked' : 'unlocked') . PHP_EOL;
}
