<?php

namespace App\Providers;

use App\Models\ToolLock;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Inject dynamic max file size into ALL views ──
        // This gives us $maxFileSizeMB (integer) and $maxFileSizeFormatted (string)
        // in every Blade view (welcome, tools, admin, etc.)
        $adminMaxMB = \App\Services\FileValidationService::getAdminMaxFileSizeMB();

        View::share('adminMaxFileSizeMB', $adminMaxMB);

        // ── Premium max file size (from constant) ──
        $premiumMaxMB = \App\Services\FileValidationService::MAX_FILE_SIZE_PREMIUM / (1024 * 1024);
        View::share('adminMaxFileSizePremiumMB', (int) $premiumMaxMB); // 200

        // Share tool lock status with navbar & admin sidebar
        View::composer([
            'partials.navbar',
            'admin.partials.sidebar',
            'components.tool-template',
            'tools.*', // Share with all tool views
        ], function ($view) {
            $toolLocks = ToolLock::all();

            // Build a lookup: route name → bool is_locked
            $lockMap = $toolLocks->pluck('is_locked', 'tool_route')
                ->map(fn ($v) => (bool) $v)
                ->toArray();

            // Build a list of URL paths that are locked (for JS matching)
            $lockedPaths = $toolLocks
                ->where('is_locked', true)
                ->map(function ($lock) {
                    $parts = explode('.', $lock->tool_route);
                    return '/' . $parts[0];
                })
                ->values()
                ->toArray();

            $view->with('lockMap', $lockMap)->with('lockedPaths', $lockedPaths);
        });

        // ── Inject real user stats into auth layout (login, register, forgot password) ──
        // Replaces hardcoded JD/AM/RK avatars and "10,000+" count with live DB data
        View::composer('layouts.auth', function ($view) {
            $colors = ['bg-indigo-500', 'bg-purple-500', 'bg-pink-500', 'bg-violet-500', 'bg-cyan-500', 'bg-teal-500'];

            // Get 3 newest users for avatar display
            $latestUsers = User::latest()->take(3)->get(['id', 'name']);

            $authAvatars = $latestUsers->map(function ($user, $index) use ($colors) {
                // Build initials: take first letter of each word (max 2 words)
                $words = explode(' ', trim($user->name));
                $initials = collect($words)
                    ->take(2)
                    ->map(fn ($w) => strtoupper($w[0]))
                    ->implode('');

                return [
                    'initials' => $initials,
                    'color'    => $colors[$index % count($colors)],
                ];
            });

            // Total registered users — format with + suffix if > 0
            $totalUsers = User::count();
            $userCountLabel = $totalUsers > 0 ? number_format($totalUsers) . '+' : '0';

            $view->with('authAvatars', $authAvatars)
                 ->with('authUserCount', $userCountLabel);
        });
    }
}
