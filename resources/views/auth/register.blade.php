@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
<div class="space-y-10">
    <div class="space-y-3">
        <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
            Bergabung <span class="text-indigo-600">Bersama Kami</span>
        </h1>
        <p class="text-slate-500 text-lg font-medium">Buat akun gratis dan nikmati semua fitur VizzioDocs.</p>
    </div>

    {{-- ===== ERROR ALERT ===== --}}
    @if ($errors->any())
    <div id="error-alert" class="flex items-start gap-4 bg-red-50 border-2 border-red-200 text-red-700 rounded-2xl px-5 py-4 animate-shake">
        <div class="shrink-0 mt-0.5">
            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-black text-sm text-red-700 mb-1">Pendaftaran Gagal</p>
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

    <form action="{{ route('register') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="space-y-2">
            <label for="name" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Nama Lengkap</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input type="text" id="name" name="name" required 
                    value="{{ old('name') }}"
                    class="block w-full pl-11 pr-4 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl
                        {{ $errors->has('name') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                    placeholder="Masukkan nama lengkap">
            </div>
        </div>

        <div class="space-y-2">
            <label for="email" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Alamat Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input type="email" id="email" name="email" required 
                    value="{{ old('email') }}"
                    class="block w-full pl-11 pr-4 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl
                        {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                    placeholder="nama@email.com">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label for="phone_number" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">No. Telepon</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input type="text" id="phone_number" name="phone_number" required 
                        value="{{ old('phone_number') }}"
                        class="block w-full pl-11 pr-4 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl
                            {{ $errors->has('phone_number') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                        placeholder="0812...">
                </div>
            </div>

            <div class="space-y-2">
                <label for="date_of_birth" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Tanggal Lahir</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="date" id="date_of_birth" name="date_of_birth" required 
                        value="{{ old('date_of_birth') }}"
                        class="block w-full pl-11 pr-4 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold rounded-2xl
                            {{ $errors->has('date_of_birth') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                        placeholder="Pilih tanggal">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label for="origin" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Asal / Kota</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <input type="text" id="origin" name="origin" required 
                        value="{{ old('origin') }}"
                        class="block w-full pl-11 pr-4 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl
                            {{ $errors->has('origin') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                        placeholder="Contoh: Jakarta">
                </div>
            </div>

            <div class="space-y-2">
                <label for="country" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Negara</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input type="text" id="country" name="country" 
                        value="{{ old('country') }}"
                        class="block w-full pl-11 pr-4 py-4 bg-white border-2 border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl transition-all outline-none text-slate-900 font-bold placeholder-slate-400"
                        placeholder="Otomatis terdeteksi...">
                </div>
                <p class="text-xs text-slate-400 ml-1 mt-1">Negara akan otomatis terdeteksi dari IP Anda. Bisa diubah manual.</p>
            </div>
        </div>

        <div class="space-y-2">
            <label for="password" class="text-sm font-black text-slate-700 uppercase tracking-wider ml-1">Kata Sandi</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input type="password" id="password" name="password" required 
                    class="block w-full pl-11 pr-4 py-4 bg-white border-2 transition-all outline-none text-slate-900 font-bold placeholder-slate-400 rounded-2xl
                        {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 bg-red-50/30' : 'border-slate-100 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10' }}"
                    placeholder="Minimal 8 karakter">
            </div>
        </div>

        <button type="submit" 
            class="w-full py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-size-200 bg-pos-0 hover:bg-pos-100 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all active:scale-95 duration-500">
            BUAT AKUN SEKARANG
        </button>
    </form>

    {{-- ===== OAuth Buttons ===== --}}
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-200"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-white px-4 font-black text-slate-400 tracking-widest uppercase">Atau Daftar Dengan</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <button onclick="window.location='{{ route('auth.google') }}'" type="button" class="flex items-center justify-center space-x-3 py-4 bg-white border-2 border-slate-100 rounded-2xl hover:bg-slate-50 hover:border-slate-200 transition-all group font-bold text-slate-700">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span>Google</span>
        </button>
        <button onclick="window.location='{{ route('auth.github') }}'" type="button" class="flex items-center justify-center space-x-3 py-4 bg-white border-2 border-slate-100 rounded-2xl hover:bg-slate-50 hover:border-slate-200 transition-all group font-bold text-slate-700">
            <svg class="w-5 h-5 text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.477 2 2 6.477 2 12c0 4.419 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91 1.333.092-.646.35-1.087.635-1.337-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482C19.138 20.161 22 16.416 22 12c0-5.523-4.477-10-10-10z"/>
            </svg>
            <span>GitHub</span>
        </button>
    </div>

    <div class="text-center">
        <p class="text-slate-500 font-bold text-sm">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 underline decoration-2 underline-offset-4 ml-1">Masuk Saja</a>
        </p>
    </div>

    <div class="pt-8 border-t border-slate-100">
        <p class="text-xs text-center text-slate-400 font-bold uppercase tracking-widest leading-relaxed">
            Dengan mendaftar, Anda menyetujui 
            <a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors">Ketentuan Layanan</a> <br> dan 
            <a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors">Kebijakan Privasi</a> kami.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 50%, 90% { transform: translateX(-6px); }
        30%, 70% { transform: translateX(6px); }
    }
    .animate-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countryInput = document.getElementById('country');
    if (countryInput) {
        fetch('http://ip-api.com/json/?fields=country')
            .then(res => res.json())
            .then(data => {
                if (data.country) {
                    countryInput.value = data.country;
                }
            })
            .catch(() => {});
    }
});
</script>
@endpush
