<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     */
    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['github' => 'Tidak dapat login dengan GitHub. Silakan coba lagi.']);
        }

        $user = \App\Models\User::where('github_id', $githubUser->getId())->first();

        if ($user) {
            // Update token for existing user
            $user->github_token = $githubUser->token;
            $user->save();
        } else {
            // Cek apakah user sudah terdaftar dengan email yang sama
            $email = $githubUser->getEmail();
            $existingUser = $email ? \App\Models\User::where('email', $email)->first() : null;

            if ($existingUser) {
                // Link GitHub ke akun yang sudah ada
                $existingUser->github_id = $githubUser->getId();
                $existingUser->github_token = $githubUser->token;
                $existingUser->save();
                $user = $existingUser;
            } else {
                // Users can have null email; GitHub may not provide a public email
                // If no email from GitHub, create a unique placeholder
                if (!$email) {
                    $email = 'github-' . $githubUser->getId() . '@github-user.local';
                }

                $user = \App\Models\User::create([
                    'name' => $githubUser->getName() ?? $githubUser->getNickname() ?? 'GitHub User',
                    'email' => $email,
                    'github_id' => $githubUser->getId(),
                    'github_token' => $githubUser->token,
                    'password' => bcrypt(Str::random(24)),
                    'daily_quota' => 20,
                    'last_quota_reset' => now(),
                ]);
            }
        }

        Auth::login($user, true);

        \App\Models\LoginHistory::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => 'success',
            'reason' => 'GitHub OAuth',
        ]);

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect()->intended('/');
    }

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Pakai stateless() biar gak gagal validasi state pas pertama kali login
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->withErrors(['google' => 'Tidak dapat login dengan Google. Silakan coba lagi.']);
        }

        $user = \App\Models\User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            // Update token untuk user yang sudah punya google_id
            $user->google_token = $googleUser->token;
            $user->save();
        } else {
            // Cek apakah user sudah terdaftar dengan email yang sama (tapi belum pake Google)
            $existingUser = \App\Models\User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Link Google ke akun yang sudah ada
                $existingUser->google_id = $googleUser->getId();
                $existingUser->google_token = $googleUser->token;
                $existingUser->save();
                $user = $existingUser;
            } else {
                // Buat user baru
                $detectedCountry = $this->detectCountry($request->ip());

                $user = \App\Models\User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'password' => bcrypt(Str::random(24)),
                    'daily_quota' => 20,
                    'last_quota_reset' => now(),
                    'country' => $detectedCountry,
                ]);
            }
        }

        Auth::login($user, true);

        \App\Models\LoginHistory::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
            'reason' => 'Google OAuth',
        ]);

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect()->intended('/');
    }


    /**
     * Display the login view.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login logic.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            \App\Models\LoginHistory::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'reason' => 'Form Login',
            ]);

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        // Log failed login
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        \App\Models\LoginHistory::create([
            'user_id' => $user ? $user->id : null,
            'name' => $user ? $user->name : 'User Tidak Dikenal',
            'email' => $credentials['email'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'failed',
            'reason' => 'Sandi Salah',
        ]);

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Display the register view.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration logic.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'phone_number'  => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'origin'        => 'required|string|max:255',
            'country'       => 'nullable|string|max:100',
            'password'      => 'required|string|min:8',
        ]);

        // Auto-detect country from IP, fallback to form input if detection fails
        $detected = $this->detectCountry($request->ip());
        $country = $detected ?: $validated['country'];

        $user = \App\Models\User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone_number'  => $validated['phone_number'],
            'date_of_birth' => $validated['date_of_birth'],
            'origin'        => $validated['origin'],
            'country'       => $country,
            'password'      => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        Auth::login($user);

        \App\Models\LoginHistory::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
            'reason' => 'Registrasi Baru',
        ]);

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/');
    }

    /**
     * Handle logout logic.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Detect country from IP address using ip-api.com (free, no key needed).
     */
    private function detectCountry(?string $ip): ?string
    {
        // Skip detection for local/private IPs
        if (!$ip || $ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return null;
        }

        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country");
            if ($response) {
                $data = json_decode($response, true);
                return $data['country'] ?? null;
            }
        } catch (\Exception $e) {
            // Silently fail — country will be null
        }

        return null;
    }
}
