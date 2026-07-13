@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@section('content')
<div class="space-y-8">
    <div class="space-y-3">
        <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
            Verifikasi <span class="text-indigo-600">Kode OTP</span>
        </h1>
        <p class="text-slate-500 text-lg font-medium">Masukkan kode 6 digit yang telah dikirim ke <strong class="text-slate-700">{{ $email }}</strong></p>
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
            <p class="font-black text-sm text-red-700 mb-1">Kode OTP Salah</p>
            @foreach ($errors->all() as $error)
                <p class="text-sm font-medium text-red-600">{{ $error }}</p>
            @endforeach
        </div>
        <button type="button" onclick="this.closest('#error-alert').remove()" class="shrink-0 text-red-400 hover:text-red-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    {{-- ===== SUCCESS ALERT ===== --}}
    @if (session('status'))
    <div id="success-alert" class="flex items-start gap-4 bg-green-50 border-2 border-green-200 text-green-700 rounded-2xl px-5 py-4">
        <div class="shrink-0 mt-0.5">
            <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-black text-sm text-green-700 mb-1">Berhasil</p>
            <p class="text-sm font-medium text-green-600">{{ session('status') }}</p>
        </div>
        <button type="button" onclick="this.closest('#success-alert').remove()" class="shrink-0 text-green-400 hover:text-green-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <form action="{{ route('password.otp.verify') }}" method="POST" class="space-y-6" id="otp-form" novalidate>
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        {{-- ===== OTP FIELD ===== --}}
        <div class="space-y-2">
            <label for="otp" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Kode OTP</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <input type="text" id="otp" name="otp" required maxlength="6" inputmode="numeric" pattern="[0-9]*"
                    value="{{ old('otp') }}"
                    class="block w-full pl-11 pr-11 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold text-center text-2xl tracking-[0.5em] placeholder-slate-400 rounded-2xl
                        {{ $errors->has('otp') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                    placeholder="000000"
                    aria-describedby="otp-error"
                    autocomplete="one-time-code">
                {{-- Status icon kanan --}}
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    @if($errors->has('otp'))
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    @endif
                </div>
            </div>
            {{-- Error message per field --}}
            @error('otp')
            <div id="otp-error" class="flex items-center gap-2 ml-1 mt-1" role="alert">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-xs font-bold text-red-600">{{ $message }}</p>
            </div>
            @enderror
            <p id="otp-hint" class="text-xs text-slate-400 font-medium ml-1">Masukkan 6 digit kode yang dikirim ke email Anda.</p>
        </div>

        <button type="submit" id="submit-btn"
            class="w-full py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-size-200 bg-pos-0 hover:bg-pos-100 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all active:scale-95 duration-500 flex items-center justify-center gap-2">
            <span id="btn-text">VERIFIKASI OTP</span>
            <svg id="btn-spinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </button>
    </form>

    {{-- ===== RESEND OTP ===== --}}
    <div class="text-center space-y-4">
        <p class="text-slate-500 font-bold text-sm" id="resend-text">
            Tidak menerima kode?
            <button type="button" id="resend-btn"
                class="text-indigo-600 hover:text-indigo-700 underline decoration-2 underline-offset-4 font-black inline-flex items-center gap-1 transition-all">
                Kirim Ulang OTP
            </button>
        </p>

        {{-- Hidden form for resend --}}
        <form action="{{ route('password.otp.resend') }}" method="POST" id="resend-form" class="hidden">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
        </form>

        <div id="countdown-text" class="text-xs text-slate-400 font-medium hidden">
            Kirim ulang dalam <span id="countdown-seconds" class="font-black text-indigo-600">30</span> detik
        </div>

        <div class="border-t border-slate-100 pt-4">
            <a href="{{ route('password.request') }}"
                class="text-slate-500 hover:text-indigo-600 font-bold text-sm inline-flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Ganti Email
            </a>
        </div>
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

    /* OTP input increment buttons hidden on number field */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const otpInput     = document.getElementById('otp');
    const form         = document.getElementById('otp-form');
    const submitBtn    = document.getElementById('submit-btn');
    const btnText      = document.getElementById('btn-text');
    const btnSpinner   = document.getElementById('btn-spinner');
    const resendBtn    = document.getElementById('resend-btn');
    const resendForm   = document.getElementById('resend-form');
    const countdownEl  = document.getElementById('countdown-text');
    const countdownSec = document.getElementById('countdown-seconds');
    const resendText   = document.getElementById('resend-text');

    // ── Auto-submit when 6 digits entered ──
    if (otpInput) {
        otpInput.addEventListener('input', function () {
            clearFieldError(otpInput);
            // Only allow digits
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value.length === 6) {
                // Auto submit
                submitBtn.click();
            }
        });

        otpInput.addEventListener('blur', function () {
            clearFieldError(otpInput);
            const val = this.value.trim();
            if (val && val.length !== 6) {
                showFieldError(otpInput, 'Kode OTP harus 6 digit angka.');
            } else if (!val) {
                showFieldError(otpInput, 'Kode OTP tidak boleh kosong.');
            }
        });
    }

    // ── Loading State on Submit ──
    if (form) {
        form.addEventListener('submit', function (e) {
            let hasError = false;

            if (!otpInput.value.trim()) {
                showFieldError(otpInput, 'Kode OTP tidak boleh kosong.'); hasError = true;
            } else if (otpInput.value.trim().length !== 6) {
                showFieldError(otpInput, 'Kode OTP harus 6 digit angka.'); hasError = true;
            }

            if (hasError) { e.preventDefault(); return; }

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            btnText.textContent = 'Memverifikasi...';
            btnSpinner.classList.remove('hidden');
        });
    }

    // ── Resend OTP with cooldown ──
    if (resendBtn && resendForm) {
        resendBtn.addEventListener('click', function () {
            // Disable button and start countdown
            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
            resendText.classList.add('hidden');
            countdownEl.classList.remove('hidden');

            // Submit resend form
            resendForm.submit();

            // Start countdown
            let seconds = 30;
            countdownSec.textContent = seconds;
            const interval = setInterval(function () {
                seconds--;
                countdownSec.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(interval);
                    countdownEl.classList.add('hidden');
                    resendText.classList.remove('hidden');
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }, 1000);
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
