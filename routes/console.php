<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $directory = storage_path('app/private/vizziodocs');
    if (File::exists($directory)) {
        $files = File::allFiles($directory);
        $now = time();
        foreach ($files as $file) {
            // Delete files older than 1 hour (3600 seconds)
            if ($now - $file->getMTime() >= 3600) {
                File::delete($file->getRealPath());
            }
        }
        
        // Delete empty directories
        $directories = File::directories($directory);
        foreach ($directories as $dir) {
            if (count(File::allFiles($dir)) === 0) {
                File::deleteDirectory($dir);
            }
        }
    }
})->hourly();
