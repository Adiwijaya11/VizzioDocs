<?php

namespace App\Http\Middleware;

use App\Models\GuestToolUsage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeductQuota
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = Auth::user();

        // If not logged in — log guest usage per tool
        if (!$user) {
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                if (isset($data['success']) && $data['success'] === true) {
                    $ip = $request->ip();
                    $today = now()->toDateString();
                    $toolName = $request->path();

                    $record = GuestToolUsage::firstOrCreate(
                        [
                            'ip_address' => $ip,
                            'tool_name' => $toolName,
                            'usage_date' => $today,
                        ],
                        [
                            'last_used_at' => now(),
                            'usage_count' => 0,
                        ]
                    );

                    // Increment usage_count atomically and update timestamp
                    $record->increment('usage_count', 1, ['last_used_at' => now()]);
                }
            }
            return $response;
        }

        // Only deduct quota for free users on successful operations
        if ($user && !$user->isPremium()) {
            // Check if the response indicates success
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                if (isset($data['success']) && $data['success'] === true) {
                    $user->decrement('daily_quota');

                    // Refresh user data in session
                    $user->refresh();
                }
            }
        }

        return $response;
    }
}
