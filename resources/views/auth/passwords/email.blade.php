@extends('layouts.auth')

@section('title', 'Lupa Sandi')

@section('content')
<div class="space-y-8">
    <div class="space-y-3">
        <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
            Lupa <span class="text-indigo-600">Kata Sandi</span>
        </h1>
        <p class="text-slate-500 text-lg font-medium">Masukkan email Anda dan kami akan mengirimkan kode OTP untuk memverifikasi identitas Anda.</p>
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
            <p class="font-black text-sm text-red-700 mb-1">Gagal Mengirim OTP</p>
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
        <button type="button" onclick="document.getElementById('success-alert').remove()" class="shrink-0 text-green-400 hover:text-green-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-6" id="forgot-form" novalidate>
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
                    aria-describedby="email-error">
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
            @enderror
        </div>

        <button type="submit" id="submit-btn"
            class="w-full py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-size-200 bg-pos-0 hover:bg-pos-100 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all active:scale-95 duration-500 flex items-center justify-center gap-2">
            <span id="btn-text">KIRIM OTP</span>
            <svg id="btn-spinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </button>
    </form>

    <div class="text-center">
        <p class="text-slate-500 font-bold text-sm">
            Ingat kata sandi Anda?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 underline decoration-2 underline-offset-4 ml-1">Kembali ke Masuk</a>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const form       = document.getElementById('forgot-form');
    const submitBtn  = document.getElementById('submit-btn');
    const btnText    = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');

    // ── Inline Validation Real-time ──
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

    // ── Loading State on Submit ──
    if (form) {
        form.addEventListener('submit', function (e) {
            let hasError = false;

            if (!emailInput.value.trim()) {
                showFieldError(emailInput, 'Email tidak boleh kosong.'); hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
                showFieldError(emailInput, 'Format email tidak valid.'); hasError = true;
            }

            if (hasError) { e.preventDefault(); return; }

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            btnText.textContent = 'Mengirim...';
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
