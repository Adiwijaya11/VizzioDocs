@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="space-y-8">
    <div class="space-y-3">
        <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
            Selamat <span class="text-indigo-600">Datang Kembali</span>
        </h1>
        <p class="text-slate-500 text-lg font-medium">Silakan masuk untuk melanjutkan produktivitas Anda.</p>
    </div>

    {{-- ===== ERROR ALERT (Server-side) ===== --}}
    @if ($errors->any())
    <div id="error-alert" class="flex items-start gap-4 bg-red-50 border-2 border-red-200 text-red-700 rounded-2xl px-5 py-4 animate-shake">
        <div class="shrink-0 mt-0.5">
            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-black text-sm text-red-700 mb-1">Login Gagal</p>
            @foreach ($errors->all() as $error)
                <p class="text-sm font-medium text-red-600">{{ $error }}</p>
            @endforeach
        </div>
        <button type="button" onclick="document.getElementById('error-alert').remove()" class="shrink-0 text-red-400 hover:text-red-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    {{-- ===== SESSION STATUS (e.g. after logout) ===== --}}
    @if (session('status'))
    <div class="flex items-center gap-3 bg-green-50 border-2 border-green-200 text-green-700 rounded-2xl px-5 py-4">
        <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <p class="text-sm font-bold text-green-700">{{ session('status') }}</p>
    </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-6" id="login-form" novalidate>
        @csrf
        
        {{-- ===== EMAIL FIELD ===== --}}
        <div class="space-y-2">
            <label for="email" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Alamat Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg id="icon-email" class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input type="email" id="email" name="email" required
                    value="{{ old('email') }}"
                    class="block w-full pl-11 pr-11 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl
                        {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                    placeholder="nama@email.com"
                    aria-describedby="email-error email-hint">
                {{-- Status icon kanan --}}
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    @if($errors->has('email'))
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    @endif
                </div>
            </div>
            {{-- Error message per field --}}
            @error('email')
            <div id="email-error" class="flex items-center gap-2 ml-1 mt-1" role="alert">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-xs font-bold text-red-600">{{ $message }}</p>
            </div>
            @else
            {{-- Hint saat tidak ada error --}}
            <p id="email-hint" class="text-xs text-slate-400 font-medium ml-1 hidden" id="email-hint-text">Masukkan email yang terdaftar di akun Anda.</p>
            @enderror
        </div>

        {{-- ===== PASSWORD FIELD ===== --}}
        <div class="space-y-2">
            <div class="flex items-center justify-between ml-1">
                <label for="password" class="text-sm font-black text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lupa Sandi?</a>
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input type="password" id="password" name="password" required
                    class="block w-full pl-11 pr-11 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl
                        {{ $errors->any() ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                    placeholder="••••••••"
                    aria-describedby="password-hint">
                {{-- Toggle show/hide password --}}
                <button type="button" id="toggle-password"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none"
                    aria-label="Tampilkan atau sembunyikan kata sandi">
                    <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            {{-- Password hint --}}
            @if($errors->any())
            <div id="password-error" class="flex items-center gap-2 ml-1 mt-1" role="alert">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-xs font-bold text-red-600">Periksa kembali kata sandi Anda.</p>
            </div>
            @endif
            <p id="password-hint" class="text-xs text-slate-400 font-medium ml-1 hidden">Minimal 8 karakter.</p>
        </div>

        <div class="flex items-center justify-between ml-1">
            <label class="flex items-center space-x-2 cursor-pointer group">
                <input type="checkbox" id="remember" name="remember" class="w-5 h-5 rounded-lg text-indigo-600 focus:ring-indigo-500 border-slate-200 transition-all">
                <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900">Ingat Saya</span>
            </label>
        </div>

        <button type="submit" id="submit-btn"
            class="w-full py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-size-200 bg-pos-0 hover:bg-pos-100 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all active:scale-95 duration-500 flex items-center justify-center gap-2">
            <span id="btn-text">MASUK KE AKUN</span>
            <svg id="btn-spinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </button>
    </form>

    <div class="text-center">
        <p class="text-slate-500 font-bold text-sm">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 underline decoration-2 underline-offset-4 ml-1">Daftar Sekarang</a>
        </p>
    </div>

    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-200"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-slate-50 px-4 font-black text-slate-400 tracking-widest uppercase">Atau Lanjutkan Dengan</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <button onclick="window.location='{{ route('auth.google') }}'" class="flex items-center justify-center space-x-3 py-4 bg-white border-2 border-slate-100 rounded-2xl hover:bg-slate-50 hover:border-slate-200 transition-all group font-bold text-slate-700">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span>Google</span>
        </button>
        <button onclick="window.location='{{ route('auth.github') }}'" class="flex items-center justify-center space-x-3 py-4 bg-white border-2 border-slate-100 rounded-2xl hover:bg-slate-50 hover:border-slate-200 transition-all group font-bold text-slate-700">
            <svg class="w-5 h-5 text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.477 2 2 6.477 2 12c0 4.419 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91 1.333.092-.646.35-1.087.635-1.337-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482C19.138 20.161 22 16.416 22 12c0-5.523-4.477-10-10-10z"/>
            </svg>
            <span>GitHub</span>
        </button>
    </div>
</div>

{{-- ===== STYLES & SCRIPTS ===== --}}
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 50%, 90% { transform: translateX(-6px); }
        30%, 70% { transform: translateX(6px); }
    }
    .animate-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Toggle Show/Hide Password ──────────────────────────────────────────
    const toggleBtn   = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const eyeIcon     = document.getElementById('eye-icon');
    const eyeOffIcon  = document.getElementById('eye-off-icon');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type  = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('hidden', isHidden);
            eyeOffIcon.classList.toggle('hidden', !isHidden);
        });
    }

    // ── Client-side Validation Hints ──────────────────────────────────────
    const emailInput    = document.getElementById('email');
    const passwordHint  = document.getElementById('password-hint');
    const emailHintText = document.getElementById('email-hint-text');

    // Tampilkan hint email saat fokus
    if (emailInput && emailHintText) {
        emailInput.addEventListener('focus', () => emailHintText.classList.remove('hidden'));
        emailInput.addEventListener('blur', () => {
            if (!emailInput.value) emailHintText.classList.add('hidden');
        });
    }

    // Tampilkan hint password saat fokus
    if (passwordInput && passwordHint) {
        passwordInput.addEventListener('focus', () => passwordHint.classList.remove('hidden'));
        passwordInput.addEventListener('blur', () => {
            if (!passwordInput.value) passwordHint.classList.add('hidden');
        });
    }

    // ── Inline Validation Real-time ────────────────────────────────────────
    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            clearFieldError(emailInput);
            const val = emailInput.value.trim();
            if (!val) {
                showFieldError(emailInput, 'Email tidak boleh kosong.');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                showFieldError(emailInput, 'Format email tidak valid. Contoh: nama@email.com');
            }
        });
        emailInput.addEventListener('input', () => clearFieldError(emailInput));
    }

    if (passwordInput) {
        passwordInput.addEventListener('blur', function () {
            clearFieldError(passwordInput);
            if (!passwordInput.value) {
                showFieldError(passwordInput, 'Kata sandi tidak boleh kosong.');
            }
        });
        passwordInput.addEventListener('input', () => clearFieldError(passwordInput));
    }

    // ── Loading State on Submit ────────────────────────────────────────────
    const form      = document.getElementById('login-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText   = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');

    if (form) {
        form.addEventListener('submit', function (e) {
            // Validasi client-side sebelum submit
            let hasError = false;

            if (!emailInput.value.trim()) {
                showFieldError(emailInput, 'Email tidak boleh kosong.'); hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
                showFieldError(emailInput, 'Format email tidak valid.'); hasError = true;
            }

            if (!passwordInput.value) {
                showFieldError(passwordInput, 'Kata sandi tidak boleh kosong.'); hasError = true;
            }

            if (hasError) { e.preventDefault(); return; }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            btnText.textContent = 'Memproses...';
            btnSpinner.classList.remove('hidden');
        });
    }

    // ── Helper: show/clear inline error ───────────────────────────────────
    function showFieldError(input, message) {
        clearFieldError(input);
        input.classList.add('border-red-400', 'bg-red-50/30', 'focus:border-red-500', 'focus:ring-red-500/10');
        input.classList.remove('border-slate-100', 'focus:border-indigo-600', 'focus:ring-indigo-500/10');

        const errEl = document.createElement('div');
        errEl.className = 'js-field-error flex items-center gap-2 ml-1 mt-1';
        errEl.setAttribute('role', 'alert');
        errEl.innerHTML = `
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs font-bold text-red-600">${message}</p>`;
        input.closest('.space-y-2').appendChild(errEl);
    }

    function clearFieldError(input) {
        input.classList.remove('border-red-400', 'bg-red-50/30', 'focus:border-red-500', 'focus:ring-red-500/10');
        input.classList.add('border-slate-100', 'focus:border-indigo-600', 'focus:ring-indigo-500/10');
        const existing = input.closest('.space-y-2').querySelector('.js-field-error');
        if (existing) existing.remove();
    }
});
</script>
@endsection
