<?php

namespace App\Http\Middleware;

use App\Models\GuestToolUsage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckQuota
{
    const GUEST_LIMIT_TOTAL = 10;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If not logged in — check guest total limit (10 uses across all tools per day)
        if (!$user) {
            $ip = $request->ip();
            $today = now()->toDateString();

            $totalUsage = GuestToolUsage::where('ip_address', $ip)
                ->where('usage_date', $today)
                ->sum('usage_count');

            // If they already used 10 total today, block
            if ($totalUsage >= self::GUEST_LIMIT_TOTAL) {
                if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda telah mencapai batas penggunaan (10 kali) untuk hari ini. Silakan login untuk melanjutkan.',
                        'quota_exhausted' => true,
                        'requires_login' => true,
                    ], 429);
                }

                return redirect()->back()->with('error', 'Anda telah mencapai batas penggunaan (10 kali) untuk hari ini. Silakan login untuk melanjutkan.');
            }

            return $next($request);
        }

        // Premium users have unlimited access
        if ($user->isPremium()) {
            return $next($request);
        }

        // Free logged-in users: check daily quota (20 per day)
        $user->resetDailyQuotaIfNeeded();

        if ($user->daily_quota <= 0) {
            if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu sudah mencapai batas pemakaian (20 kali) untuk hari ini. Silakan upgrade ke Premium untuk akses tanpa batas.',
                    'quota_exhausted' => true,
                    'requires_upgrade' => true,
                ], 429);
            }

            return redirect()->back()->with('error', 'Kamu sudah mencapai batas pemakaian (20 kali) untuk hari ini. Upgrade ke Premium untuk akses tanpa batas.');
        }

        return $next($request);
    }
}

