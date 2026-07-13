<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VizzioDocs — Konversi & Manipulasi Dokumen Online')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Vite Styles & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            min-height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f8fafc; /* slate-50 */
            overflow-x: hidden;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            position: relative;
        }
        body > main {
            flex: 1 0 auto;
            background-color: #f8fafc; /* slate-50 for main content area */
        }
        body > footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800">

    {{-- Toast Notification Container --}}
    <div id="toast-container" class="fixed z-[20000] pointer-events-auto w-full max-w-sm"
         style="top: 90px;">
        <div class="flex flex-col items-center space-y-3"></div>
    </div>

    <!-- Decorative Background Blobs (locked to viewport, never affects document height) -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] rounded-full bg-indigo-300/20 blur-3xl animate-blob"></div>
        <div class="absolute top-[10%] right-[-10%] w-[450px] h-[450px] rounded-full bg-purple-300/20 blur-3xl animate-blob [animation-delay:2s]"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[600px] h-[600px] rounded-full bg-pink-300/10 blur-3xl animate-blob [animation-delay:4s]"></div>
    </div>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main class="vd-main-content">
        @yield('content')
    </main>

    {{-- Footer (hidden on tool pages via hideFooter section) --}}
    @if (!View::hasSection('hideFooter'))
        @include('partials.footer')
    @endif

    <!-- Login/Register Modal -->
    <div id="auth-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
        <!-- 🔥 Premium Animated Backdrop with Deep Blur -->
        <div id="modal-backdrop" class="absolute inset-0 transition-all duration-700 ease-out">
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-2xl"></div>
            <!-- Animated Gradient Orbs -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-gradient-to-br from-indigo-500/30 to-purple-600/20 rounded-full blur-3xl animate-[blob_8s_infinite] pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-gradient-to-br from-pink-500/20 to-rose-400/20 rounded-full blur-3xl animate-[blob_8s_infinite_2s] pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-br from-cyan-400/10 to-indigo-400/10 rounded-full blur-3xl animate-[blob_8s_infinite_4s] pointer-events-none"></div>
            <!-- Subtle Grid Pattern Overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:3rem_3rem] pointer-events-none"></div>
        </div>

        <!-- Modal Content -->
        <div class="relative w-full max-w-md transform transition-all duration-500 animate-[scaleIn_0.5s_cubic-bezier(.34,1.56,.64,1)]">
            <!-- Premium Outer Glow -->
            <div class="absolute -inset-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-3xl blur-3xl opacity-30 animate-[pulseGlow_3s_ease-in-out_infinite]"></div>

            <!-- Glass Card -->
            <div class="relative bg-white/90 backdrop-blur-2xl rounded-3xl shadow-[0_20px_70px_-15px_rgba(99,102,241,0.35),0_8px_30px_-6px_rgba(0,0,0,0.15)] border border-white/40 overflow-hidden">
                <!-- Subtle Inner Glow -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/60 via-transparent to-purple-50/30 pointer-events-none"></div>

                <!-- Top Accent Bar -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                <!-- Premium Close Button -->
                <button id="close-modal" class="absolute top-6 right-6 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-slate-100 to-slate-200 hover:from-rose-100 hover:to-rose-200 text-slate-600 hover:text-rose-600 transition-all duration-300 hover:scale-110 hover:rotate-90 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Premium Quota Exhaustion Banner (hidden by default) -->
                <div id="quota-banner" class="hidden relative mx-6 mt-6 mb-2 p-4 rounded-2xl bg-gradient-to-r from-amber-50 via-orange-50/80 to-rose-50 border-2 border-amber-200/80 shadow-lg shadow-amber-200/30 animate-slide-down overflow-hidden">
                    <!-- Decorative glow -->
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-gradient-to-br from-amber-300 to-orange-400 rounded-full blur-2xl opacity-20"></div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-gradient-to-tr from-rose-300 to-pink-400 rounded-full blur-2xl opacity-15"></div>

                    <div class="relative flex items-start space-x-3.5">
                        <!-- Premium Warning Icon -->
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 shadow-md shadow-amber-200/50">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p id="quota-banner-message" class="text-sm font-bold text-amber-800 leading-relaxed"></p>
                            <p class="text-xs font-semibold text-amber-600 mt-1.5 flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Login atau daftar untuk melanjutkan menggunakan semua tools</span>
                            </p>
                        </div>
                        <!-- Dismiss button -->
                        <button onclick="document.getElementById('quota-banner')?.classList.add('hidden')" class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full hover:bg-amber-200/50 text-amber-500 hover:text-amber-700 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Premium Tabs Header -->
                <div class="flex border-b border-slate-200/50 bg-gradient-to-r from-slate-50/80 via-indigo-50/30 to-slate-50/80 backdrop-blur-sm">
                    <button id="login-tab" class="tab-btn active flex-1 py-5 text-sm font-bold text-slate-400 transition-all duration-300 relative hover:text-indigo-600 group">
                        <span class="relative z-10">Masuk</span>
                        <span class="tab-indicator absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500 opacity-0 transition-all duration-300 rounded-t-full shadow-lg shadow-indigo-300"></span>
                        <span class="absolute inset-0 bg-gradient-to-b from-indigo-50/0 to-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    </button>
                    <button id="register-tab" class="tab-btn flex-1 py-5 text-sm font-bold text-slate-400 transition-all duration-300 relative hover:text-indigo-600 group">
                        <span class="relative z-10">Daftar</span>
                        <span class="tab-indicator absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500 opacity-0 transition-all duration-300 rounded-t-full shadow-lg shadow-indigo-300"></span>
                        <span class="absolute inset-0 bg-gradient-to-b from-indigo-50/0 to-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="p-8 bg-gradient-to-br from-white via-slate-50/30 to-white">

                    <!-- Login Form -->
                    <div id="login-form" class="tab-content space-y-6">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-200 mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-extrabold bg-gradient-to-r from-slate-800 via-indigo-800 to-slate-800 bg-clip-text text-transparent">Selamat Datang!</h2>
                            <p class="text-sm text-slate-500 mt-2">Masuk ke akun VizzioDocs Anda</p>
                        </div>

                        <form class="space-y-5">
                            <!-- Email Input with Icon -->
                            <div class="group">
                                <label for="login-email" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="email" id="login-email" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white hover:border-slate-300 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all duration-200 text-sm font-medium placeholder:text-slate-400 shadow-sm focus:shadow-lg focus:shadow-indigo-100" placeholder="nama@email.com" required>
                                </div>
                            </div>

                            <!-- Password Input with Icon -->
                            <div class="group">
                                <label for="login-password" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="login-password" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white hover:border-slate-300 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all duration-200 text-sm font-medium placeholder:text-slate-400 shadow-sm focus:shadow-lg focus:shadow-indigo-100" placeholder="••••••••" required>
                                </div>
                            </div>

                            <!-- Remember & Forgot -->
                            <div class="flex items-center justify-between pt-1">
                                <label class="flex items-center space-x-2.5 text-sm text-slate-600 cursor-pointer group">
                                    <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 transition-all cursor-pointer">
                                    <span class="group-hover:text-slate-800 transition-colors">Ingat saya</span>
                                </label>
                                <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors hover:underline">Lupa password?</a>
                            </div>

                            <!-- Premium Submit Button -->
                            <button type="submit" class="w-full py-4 px-6 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-size-200 bg-pos-0 hover:bg-pos-100 shadow-xl shadow-indigo-300/50 hover:shadow-2xl hover:shadow-indigo-400/60 transition-all duration-500 hover:-translate-y-1 active:translate-y-0 active:shadow-lg relative overflow-hidden group">
                                <span class="relative z-10 flex items-center justify-center space-x-2">
                                    <span>Masuk Sekarang</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </button>
                        </form>

                        <!-- Premium Social Login -->
                        <div class="relative my-7">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="px-4 py-1 bg-white text-slate-500 font-bold uppercase tracking-wide rounded-full">atau masuk dengan</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <button class="flex items-center justify-center space-x-2.5 py-3.5 px-4 rounded-xl border-2 border-slate-200 hover:border-indigo-300 bg-white hover:bg-gradient-to-br hover:from-slate-50 hover:to-indigo-50 transition-all duration-300 group shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Google</span>
                            </button>
                            <button class="flex items-center justify-center space-x-2.5 py-3.5 px-4 rounded-xl border-2 border-slate-200 hover:border-blue-300 bg-white hover:bg-gradient-to-br hover:from-slate-50 hover:to-blue-50 transition-all duration-300 group shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" fill="#1877F2" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Facebook</span>
                            </button>
                        </div>
                    </div>

                    <!-- Register Form -->
                    <div id="register-form" class="tab-content hidden space-y-6">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg shadow-purple-200 mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-extrabold bg-gradient-to-r from-slate-800 via-purple-800 to-slate-800 bg-clip-text text-transparent">Buat Akun Baru</h2>
                            <p class="text-sm text-slate-500 mt-2">Bergabung dengan VizzioDocs sekarang</p>
                        </div>

                        <form class="space-y-4">
                            <!-- Name Input with Icon -->
                            <div class="group">
                                <label for="register-name" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-purple-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="register-name" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white hover:border-slate-300 focus:border-purple-400 focus:ring-4 focus:ring-purple-100 outline-none transition-all duration-200 text-sm font-medium placeholder:text-slate-400 shadow-sm focus:shadow-lg focus:shadow-purple-100" placeholder="John Doe" required>
                                </div>
                            </div>

                            <!-- Email Input with Icon -->
                            <div class="group">
                                <label for="register-email" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-purple-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="email" id="register-email" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white hover:border-slate-300 focus:border-purple-400 focus:ring-4 focus:ring-purple-100 outline-none transition-all duration-200 text-sm font-medium placeholder:text-slate-400 shadow-sm focus:shadow-lg focus:shadow-purple-100" placeholder="nama@email.com" required>
                                </div>
                            </div>

                            <!-- Password Input with Icon -->
                            <div class="group">
                                <label for="register-password" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-purple-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="register-password" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white hover:border-slate-300 focus:border-purple-400 focus:ring-4 focus:ring-purple-100 outline-none transition-all duration-200 text-sm font-medium placeholder:text-slate-400 shadow-sm focus:shadow-lg focus:shadow-purple-100" placeholder="••••••••" required>
                                </div>
                            </div>

                            <!-- Confirm Password Input with Icon -->
                            <div class="group">
                                <label for="register-password-confirm" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5">Konfirmasi Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-purple-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="register-password-confirm" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white hover:border-slate-300 focus:border-purple-400 focus:ring-4 focus:ring-purple-100 outline-none transition-all duration-200 text-sm font-medium placeholder:text-slate-400 shadow-sm focus:shadow-lg focus:shadow-purple-100" placeholder="••••••••" required>
                                </div>
                            </div>

                            <!-- Terms Checkbox -->
                            <div class="flex items-start space-x-3 pt-2">
                                <input type="checkbox" id="terms" class="w-4 h-4 mt-1 rounded border-slate-300 text-purple-600 focus:ring-purple-500 focus:ring-offset-0 transition-all cursor-pointer" required>
                                <label for="terms" class="text-xs text-slate-600 leading-relaxed cursor-pointer">
                                    Saya setuju dengan <a href="#" class="font-bold text-purple-600 hover:text-purple-700 hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="font-bold text-purple-600 hover:text-purple-700 hover:underline">Kebijakan Privasi</a>
                                </label>
                            </div>

                            <!-- Premium Submit Button -->
                            <button type="submit" class="w-full py-4 px-6 rounded-xl font-bold text-white bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 bg-size-200 bg-pos-0 hover:bg-pos-100 shadow-xl shadow-purple-300/50 hover:shadow-2xl hover:shadow-purple-400/60 transition-all duration-500 hover:-translate-y-1 active:translate-y-0 active:shadow-lg relative overflow-hidden group">
                                <span class="relative z-10 flex items-center justify-center space-x-2">
                                    <span>Daftar Sekarang</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-rose-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </button>
                        </form>

                        <!-- Premium Social Register -->
                        <div class="relative my-7">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="px-4 py-1 bg-white text-slate-500 font-bold uppercase tracking-wide rounded-full">atau daftar dengan</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <button class="flex items-center justify-center space-x-2.5 py-3.5 px-4 rounded-xl border-2 border-slate-200 hover:border-indigo-300 bg-white hover:bg-gradient-to-br hover:from-slate-50 hover:to-indigo-50 transition-all duration-300 group shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Google</span>
                            </button>
                            <button class="flex items-center justify-center space-x-2.5 py-3.5 px-4 rounded-xl border-2 border-slate-200 hover:border-blue-300 bg-white hover:bg-gradient-to-br hover:from-slate-50 hover:to-blue-50 transition-all duration-300 group shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" fill="#1877F2" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Facebook</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Premium Expired Popup -- Cek langsung apakah user premiumnya habis --}}
    @php
        $showExpiredPopup = false;
        if (Auth::check()) {
            $user = Auth::user();
            // Jangan tampilkan popup di halaman upgrade (karena user sedang meng-upgrade)
            if ($user->plan === 'premium' && !$user->isPremium() && !request()->routeIs('upgrade.*')) {
                $showExpiredPopup = true;
            }
        }
    @endphp

    @if($showExpiredPopup)
    <div id="premium-expired-modal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="relative w-full max-w-md transform transition-all duration-500 animate-[scaleIn_0.5s_cubic-bezier(.34,1.56,.64,1)]">
            <!-- Premium Outer Glow -->
            <div class="absolute -inset-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-3xl blur-3xl opacity-30 animate-[pulseGlow_3s_ease-in-out_infinite]"></div>

            <!-- Glass Card -->
            <div class="relative bg-white/95 backdrop-blur-2xl rounded-3xl shadow-[0_20px_70px_-15px_rgba(99,102,241,0.35)] border border-white/40 overflow-hidden">
                <!-- Top Accent Bar -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                <div class="p-8 text-center">
                    <!-- Crown Icon with theme gradient -->
                    <div class="mx-auto w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-200/50 flex items-center justify-center animate-bounce">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                    </div>

                    <h3 class="mt-6 text-2xl font-extrabold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Premium Habis!
                    </h3>
                    <p class="mt-3 text-slate-500 leading-relaxed">
                        Masa premium Anda telah berakhir. Ayo perbarui premium Anda sekarang!
                    </p>

                    <div class="mt-8 space-y-3">
                        <a href="{{ route('upgrade.index') }}"
                           class="block w-full py-3.5 px-6 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 shadow-xl shadow-indigo-300/40 hover:shadow-2xl hover:shadow-purple-400/50 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                </svg>
                                <span>Upgrade Premium Lagi</span>
                            </span>
                        </a>
                        <button onclick="dismissPremiumPopup()"
                                class="block w-full py-3 px-6 rounded-xl font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition-all duration-200">
                            Nanti Saja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cek localStorage agar pop-up hanya muncul SEKALI per sesi browser
        (function() {
            var dismissed = localStorage.getItem('premium_expired_dismissed');
            if (dismissed === 'true') {
                var modal = document.getElementById('premium-expired-modal');
                if (modal) modal.remove();
            }
        })();

        function dismissPremiumPopup() {
            localStorage.setItem('premium_expired_dismissed', 'true');
            var modal = document.getElementById('premium-expired-modal');
            if (modal) modal.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('premium-expired-modal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        dismissPremiumPopup();
                    }
                });
            }
        });
    </script>
    @endif

    <!-- Scripts Stack (for @push('scripts') from child pages) -->
    @stack('scripts')

</body>
</html>