<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - VizzioDocs</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased text-slate-900 bg-white">
    <div class="min-h-screen lg:h-screen flex flex-col lg:flex-row">
        <!-- Left Side: Brand Hero (Visible on LG up) -->
        <div class="hidden lg:flex lg:w-7/12 lg:sticky lg:top-0 lg:h-screen relative overflow-hidden bg-slate-900 items-center justify-center p-12">
            <!-- Mesh Gradient Background -->
            <div class="absolute inset-0 opacity-40">
                <div class="absolute top-[-10%] left-[-10%] w-[70%] h-[70%] bg-indigo-600 rounded-full blur-[120px] animate-blob"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-purple-600 rounded-full blur-[120px] animate-blob animation-delay-2000"></div>
                <div class="absolute top-[20%] right-[10%] w-[40%] h-[40%] bg-pink-600 rounded-full blur-[120px] animate-blob animation-delay-4000"></div>
            </div>

            <!-- Decorative Grid -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:3rem_3rem]"></div>

            <div class="relative z-10 w-full max-w-2xl">
                <!-- Floating Icons (Matches Home Page) -->
                <div class="absolute -top-20 -left-10 opacity-20 animate-float">
                    <svg class="w-32 h-32 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <div class="space-y-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center space-x-4 group">
                        <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white shadow-2xl shadow-indigo-500/50 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-4xl font-black tracking-tighter text-white">VizzioDocs</span>
                    </a>

                    <div class="space-y-4">
                        <h2 class="text-5xl font-black text-white leading-tight">
                            Solusi <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Cerdas</span> Untuk<br>Semua Dokumen PDF.
                        </h2>
                        <p class="text-slate-400 text-xl font-medium max-w-lg leading-relaxed">
                            Gabungkan, kompres, dan ubah dokumen Anda dalam hitungan detik dengan teknologi pengolahan dokumen tercanggih.
                        </p>
                    </div>

                    <!-- Trust Badges — data real dari database -->
                    <div class="pt-8 flex items-center space-x-8">
                        <div class="flex -space-x-3">
                            @forelse($authAvatars ?? [] as $avatar)
                                <div class="w-12 h-12 rounded-full border-2 border-slate-900 {{ $avatar['color'] }} flex items-center justify-center text-white text-xs font-bold" title="{{ $avatar['initials'] }}">
                                    {{ $avatar['initials'] }}
                                </div>
                            @empty
                                {{-- Fallback jika belum ada user --}}
                                <div class="w-12 h-12 rounded-full border-2 border-slate-900 bg-indigo-500 flex items-center justify-center text-white text-xs font-bold">VD</div>
                            @endforelse
                        </div>
                        <div class="text-sm font-bold text-slate-500">
                            <span class="text-white">{{ $authUserCount ?? '0' }}</span> Pengguna Terdaftar<br>
                            Dipercaya oleh profesional.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Auth Form (Scrollable) -->
        <div class="flex-1 flex flex-col items-center justify-start p-8 sm:p-12 lg:p-20 bg-slate-50 relative overflow-y-auto min-h-screen">
            <!-- Mobile Logo (Visible only on SM/MD) -->
            <div class="lg:hidden mb-12">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight text-slate-900">VizzioDocs</span>
                </a>
            </div>



            <div class="w-full max-w-md animate-scale-in">
                @yield('content')
            </div>

            <!-- Footer Text (Minimal) -->
            <div class="mt-12 text-slate-400 text-sm font-medium">
                &copy; {{ date('Y') }} VizzioDocs. Keamanan Terjamin 100%.
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
