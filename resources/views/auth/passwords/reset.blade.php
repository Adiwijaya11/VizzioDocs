@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
<div class="space-y-8">
    <div class="space-y-3">
        <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
            Atur Ulang <span class="text-indigo-600">Kata Sandi</span>
        </h1>
        <p class="text-slate-500 text-lg font-medium">Buat kata sandi baru yang kuat untuk akun Anda.</p>
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
            <p class="font-black text-sm text-red-700 mb-1">Gagal Mengatur Ulang</p>
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

    <form action="{{ route('password.update') }}" method="POST" class="space-y-6" id="reset-form" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        {{-- ===== NEW PASSWORD FIELD ===== --}}
        <div class="space-y-2">
            <label for="password" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Kata Sandi Baru</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input type="password" id="password" name="password" required minlength="8"
                    class="block w-full pl-11 pr-11 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl
                        {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                    placeholder="Minimal 8 karakter"
                    aria-describedby="password-hint">
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
            @error('password')
            <div id="password-error" class="flex items-center gap-2 ml-1 mt-1" role="alert">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-xs font-bold text-red-600">{{ $message }}</p>
            </div>
            @enderror
            <p id="password-hint" class="text-xs text-slate-400 font-medium ml-1 hidden">Minimal 8 karakter untuk keamanan maksimal.</p>
        </div>

        {{-- ===== CONFIRM PASSWORD FIELD ===== --}}
        <div class="space-y-2">
            <label for="password_confirmation" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Konfirmasi Kata Sandi</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                    class="block w-full pl-11 pr-11 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10"
                    placeholder="Ulangi kata sandi baru">
            </div>
        </div>

        <button type="submit" id="submit-btn"
            class="w-full py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-size-200 bg-pos-0 hover:bg-pos-100 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all active:scale-95 duration-500 flex items-center justify-center gap-2">
            <span id="btn-text">ATUR ULANG KATA SANDI</span>
            <svg id="btn-spinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </button>
    </form>

    <div class="text-center">
        <p class="text-slate-500 font-bold text-sm">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 underline decoration-2 underline-offset-4 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Masuk
            </a>
        </p>
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

    @keyframes strength-fill {
        from { width: 0; }
    }
    .strength-bar-fill { animation: strength-fill 0.3s ease-out; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const confirmInput  = document.getElementById('password_confirmation');
    const toggleBtn     = document.getElementById('toggle-password');
    const eyeIcon       = document.getElementById('eye-icon');
    const eyeOffIcon    = document.getElementById('eye-off-icon');
    const form          = document.getElementById('reset-form');
    const submitBtn     = document.getElementById('submit-btn');
    const btnText       = document.getElementById('btn-text');
    const btnSpinner    = document.getElementById('btn-spinner');
    const passwordHint  = document.getElementById('password-hint');

    // ── Toggle Show/Hide Password ──
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type  = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('hidden', isHidden);
            eyeOffIcon.classList.toggle('hidden', !isHidden);
        });
    }

    // ── Password Strength Indicator ──
    if (passwordInput) {
        passwordInput.addEventListener('focus', () => passwordHint.classList.remove('hidden'));
        passwordInput.addEventListener('blur', () => {
            if (!passwordInput.value) passwordHint.classList.add('hidden');
        });

        passwordInput.addEventListener('blur', function () {
            clearFieldError(passwordInput);
            const val = passwordInput.value;
            if (!val) {
                showFieldError(passwordInput, 'Kata sandi tidak boleh kosong.');
            } else if (val.length < 8) {
                showFieldError(passwordInput, 'Minimal 8 karakter.');
            }
        });
        passwordInput.addEventListener('input', () => clearFieldError(passwordInput));
    }

    // ── Confirm Password Match ──
    if (confirmInput) {
        confirmInput.addEventListener('blur', function () {
            clearFieldError(confirmInput);
            if (this.value && this.value !== passwordInput.value) {
                showFieldError(confirmInput, 'Kata sandi tidak cocok.');
            }
        });
        confirmInput.addEventListener('input', function () {
            clearFieldError(confirmInput);
            if (this.value && this.value !== passwordInput.value) {
                showFieldError(confirmInput, 'Kata sandi tidak cocok.');
            }
        });
    }

    // ── Loading State on Submit ──
    if (form) {
        form.addEventListener('submit', function (e) {
            let hasError = false;

            if (!passwordInput.value) {
                showFieldError(passwordInput, 'Kata sandi tidak boleh kosong.'); hasError = true;
            } else if (passwordInput.value.length < 8) {
                showFieldError(passwordInput, 'Minimal 8 karakter.'); hasError = true;
            }

            if (!confirmInput.value) {
                showFieldError(confirmInput, 'Konfirmasi kata sandi tidak boleh kosong.'); hasError = true;
            } else if (confirmInput.value !== passwordInput.value) {
                showFieldError(confirmInput, 'Kata sandi tidak cocok.'); hasError = true;
            }

            if (hasError) { e.preventDefault(); return; }

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            btnText.textContent = 'Memproses...';
            btnSpinner.classList.remove('hidden');
        });
    }

    // ── Helper Functions ──
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
