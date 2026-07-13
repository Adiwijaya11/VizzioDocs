<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\ToolLock::all() as $t) {
    echo "{$t->id} | {$t->tool_slug} | {$t->tool_route} | {$t->tool_name} | locked=" . ($t->is_locked ? 'yes' : 'no') . PHP_EOL;
}
