<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPremiumExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek expired premium SEBELUM request diproses, agar reset DB terjadi
        // sebelum controller / view melihat data user.
        if (Auth::check()) {
            $user = Auth::user();
            // isPremium() otomatis mereset user expired ke free + quota 20
            if ($user->plan === 'premium' && !$user->isPremium()) {
                // Flash tersedia untuk halaman yang akan di-render
                session()->flash('premium_expired', 'Masa premium Anda telah berakhir. Kuota sudah dikembalikan ke 20/hari.');
            }
        }

        $response = $next($request);

        return $response;
    }
}
