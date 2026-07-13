@extends('layouts.app')

@section('title', 'Hubungi Kami — VizzioDocs')

@section('content')
<style>
    /* ── Hero Gradient Animation ── */
    .contact-hero {
        position: relative;
        overflow: hidden;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 600px 400px at 20% 50%, rgba(99,102,241,0.08) 0%, transparent 70%),
                    radial-gradient(ellipse 500px 300px at 80% 30%, rgba(168,85,247,0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ── Floating Orbs ── */
    .contact-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(100px);
        opacity: 0.4;
        animation: orbFloat 8s ease-in-out infinite alternate;
    }
    @keyframes orbFloat {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(30px, -20px) scale(1.1); }
    }

    /* ── Staggered Card Entrance ── */
    .contact-section {
        opacity: 0;
        transform: translateY(20px);
        animation: contactFadeIn 0.5s ease-out forwards;
    }
    .contact-section:nth-child(1) { animation-delay: 0.05s; }
    .contact-section:nth-child(2) { animation-delay: 0.1s; }
    .contact-section:nth-child(3) { animation-delay: 0.15s; }
    .contact-section:nth-child(4) { animation-delay: 0.2s; }
    .contact-section:nth-child(5) { animation-delay: 0.25s; }
    .contact-section:nth-child(6) { animation-delay: 0.3s; }

    @keyframes contactFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Form styling ── */
    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.9rem;
        color: #1e293b;
        background: white;
        transition: all 0.2s ease;
        outline: none;
    }
    .form-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
    }
    .form-input::placeholder {
        color: #94a3b8;
    }
    textarea.form-input {
        resize: vertical;
        min-height: 140px;
    }

    /* ── Contact card hover ── */
    .contact-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .contact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(99,102,241,0.1);
    }

    /* ── Social button pulse ── */
    .social-btn {
        transition: all 0.3s ease;
    }
    .social-btn:hover {
        transform: scale(1.05);
    }

    /* ── Success toast animation ── */
    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes toastSlideOut {
        from { opacity: 1; transform: translateY(0) scale(1); }
        to { opacity: 0; transform: translateY(20px) scale(0.95); }
    }
    .toast-enter {
        animation: toastSlideIn 0.4s ease-out forwards;
    }
    .toast-leave {
        animation: toastSlideOut 0.3s ease-in forwards;
    }

    @media (max-width: 640px) {
        .contact-title {
            font-size: 1.75rem !important;
        }
    }
</style>

<div class="relative bg-white">
    <!-- ════ Background Orbs ════ -->
    <div class="contact-orb" style="top:-10%;right:-10%;width:500px;height:500px;background:rgba(99,102,241,0.08);"></div>
    <div class="contact-orb" style="bottom:-10%;left:-10%;width:450px;height:450px;background:rgba(168,85,247,0.06);animation-delay:2s;"></div>
    <div class="contact-orb" style="top:40%;left:50%;width:300px;height:300px;background:rgba(236,72,153,0.05);animation-delay:4s;"></div>

    <!-- ════════════════════════════════════════════════
         HERO SECTION
         ════════════════════════════════════════════════ -->
    <section class="contact-hero relative pt-28 pb-16 sm:pt-32 sm:pb-20 lg:pt-40 lg:pb-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/60 via-white to-white pointer-events-none"></div>
        <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-indigo-200/60 to-transparent"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100/60 text-indigo-700 text-xs sm:text-sm font-bold tracking-wide mb-6 sm:mb-8 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Ada pertanyaan? Kami siap membantu</span>
            </div>

            <!-- Title -->
            <h1 class="contact-title text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-tight">
                Hubungi
                <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 bg-clip-text text-transparent">Kami</span>
            </h1>

            <p class="mt-4 sm:mt-6 text-base sm:text-lg text-slate-500 font-medium max-w-3xl mx-auto leading-relaxed">
                Punya pertanyaan, saran, atau butuh bantuan? Tim VizzioDocs siap mendengarkan 
                dan membantu Anda dengan senang hati.
            </p>

            <!-- Quick Stats -->
            <div class="mt-8 sm:mt-10 flex flex-wrap justify-center gap-3 sm:gap-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600">Respon &lt; 24 Jam</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600">Dukungan 24/7</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600">Live Chat Tersedia</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════
         CONTACT CARDS + FORM
         ════════════════════════════════════════════════ -->
    <section class="relative pb-16 sm:pb-20 lg:pb-28">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- ─── Left: Contact Info ─── -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Email -->
                    <div class="contact-section p-6 bg-white border border-slate-200 rounded-2xl contact-card">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-200 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-wider text-slate-800 mb-1">Email</h3>
                                <p class="text-sm text-slate-500 mb-2">Kirim pertanyaan Anda via email</p>
                                <a href="mailto:support@vizziocs.com" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">support@vizziocs.com</a>
                            </div>
                        </div>
                    </div>

                    <!-- Jam Operasional -->
                    <div class="contact-section p-6 bg-white border border-slate-200 rounded-2xl contact-card">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-200 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-wider text-slate-800 mb-1">Jam Operasional</h3>
                                <p class="text-sm text-slate-500 mb-2">Tim kami siap membantu Anda</p>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600 font-semibold">Senin — Jumat</span>
                                        <span class="text-slate-800 font-bold">08:00 — 18:00 WIB</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600 font-semibold">Sabtu</span>
                                        <span class="text-slate-800 font-bold">09:00 — 15:00 WIB</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600 font-semibold">Minggu & Libur</span>
                                        <span class="text-emerald-600 font-bold">Libur</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="contact-section p-6 bg-white border border-slate-200 rounded-2xl contact-card">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-lg shadow-rose-200 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-wider text-slate-800 mb-1">Kantor Pusat</h3>
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    Jl. Merak No. 12, Kel. Bendungan Hilir,<br>
                                    Kec. Tanah Abang, Jakarta Pusat<br>
                                    DKI Jakarta 10210, Indonesia
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="contact-section p-6 bg-white border border-slate-200 rounded-2xl contact-card">
                        <h3 class="text-sm font-black uppercase tracking-wider text-slate-800 mb-4">Ikuti Kami</h3>
                        <div class="flex gap-3">
                            <a href="#" class="social-btn w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-indigo-100 hover:text-indigo-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="social-btn w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-sky-100 hover:text-sky-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="#" class="social-btn w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-purple-100 hover:text-purple-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 5.523 4.477 10 10 10s10-4.477 10-10c0-5.523-4.477-10-10-10zm3.75 14.5h-2.5v-4.5c0-.828-.672-1.5-1.5-1.5s-1.5.672-1.5 1.5v4.5h-2.5V9.75h2.5v1.125c.625-.75 1.5-1.125 2.5-1.125 1.657 0 3 1.343 3 3v3.75z"/></svg>
                            </a>
                            <a href="#" class="social-btn w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-rose-100 hover:text-rose-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 3.273 1.533 6.185 3.92 8.113l-.52 1.936c-.39 1.45 1.0 2.686 2.357 2.108l.53-.21C9.7 24.1 10.83 24.319 12 24c5.523 0 10-4.477 10-10S17.523 2 12 2zm5.5 14.5c0 .276-.224.5-.5.5h-10c-.276 0-.5-.224-.5-.5v-1c0-.276.224-.5.5-.5h10c.276 0 .5.224.5.5v1zm0-4c0 .276-.224.5-.5.5h-10c-.276 0-.5-.224-.5-.5v-1c0-.276.224-.5.5-.5h10c.276 0 .5.224.5.5v1zm0-4c0 .276-.224.5-.5.5h-10c-.276 0-.5-.224-.5-.5v-1c0-.276.224-.5.5-.5h10c.276 0 .5.224.5.5v1z"/></svg>
                            </a>
                            <a href="#" class="social-btn w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-emerald-100 hover:text-emerald-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- ─── Right: Contact Form ─── -->
                <div class="lg:col-span-2">
                    <div class="contact-section p-6 sm:p-8 lg:p-10 bg-white border border-slate-200 rounded-3xl shadow-sm">
                        <!-- Form Header -->
                        <div class="mb-8">
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Kirim Pesan</h2>
                            <p class="text-sm sm:text-base text-slate-500 mt-2">
                                Isi form di bawah dan tim kami akan menghubungi Anda dalam waktu 1x24 jam.
                            </p>
                        </div>

                        <form id="contactForm" class="space-y-6" onsubmit="return handleContactForm(event)">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" required placeholder="Masukkan nama Anda" class="form-input">
                                </div>
                                <div>
                                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Alamat Email</label>
                                    <input type="email" id="email" name="email" required placeholder="Masukkan email Anda" class="form-input">
                                </div>
                            </div>

                            <div>
                                <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Subjek</label>
                                <select id="subject" name="subject" required class="form-input appearance-none bg-no-repeat" style="background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e\");background-position:right 12px center;background-size:20px;">
                                    <option value="" disabled selected>Pilih subjek pesan</option>
                                    <option value="Bantuan Teknis">Bantuan Teknis</option>
                                    <option value="Laporan Bug">Laporan Bug</option>
                                    <option value="Saran Fitur">Saran Fitur</option>
                                    <option value="Kerjasama & Mitra">Kerjasama & Mitra</option>
                                    <option value="Penagihan & Akun">Penagihan & Akun</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Pesan</label>
                                <textarea id="message" name="message" required placeholder="Tulis pesan Anda di sini..."></textarea>
                            </div>

                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="agreement" required class="mt-1 w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="agreement" class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                    Saya setuju dengan <a href="{{ route('privacy') }}" class="text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">Kebijakan Privasi</a> 
                                    dan <a href="{{ route('terms') }}" class="text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">Syarat & Ketentuan</a> VizzioDocs.
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="submitBtn" class="w-full py-4 px-6 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-size-200 bg-pos-0 hover:bg-pos-100 shadow-xl shadow-indigo-300/50 hover:shadow-2xl hover:shadow-purple-400/60 transition-all duration-500 hover:-translate-y-1 active:translate-y-0 active:shadow-lg relative overflow-hidden group">
                                <span id="submitText" class="relative z-10 flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Kirim Pesan</span>
                                </span>
                                <span id="submitSpinner" class="hidden relative z-10 flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Mengirim...</span>
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-rose-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- ════════════════════════════════════════════════
                 FAQ CONTACT (Mini)
                 ════════════════════════════════════════════════ -->
            <div class="mt-16 sm:mt-20">
                <div class="max-w-3xl mx-auto">
                    <div class="text-center mb-10">
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Sebelum Menghubungi</h2>
                        <p class="text-sm sm:text-base text-slate-500 mt-2">
                            Mungkin pertanyaan Anda sudah terjawab di Pusat Bantuan kami.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('bantuan') }}#cara-menggunakan" class="contact-section flex items-start gap-4 p-5 bg-white border border-slate-200 rounded-2xl hover:border-indigo-200 hover:shadow-lg transition-all duration-300 group">
                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">Cara Menggunakan Alat</h4>
                                <p class="text-xs text-slate-500 mt-1">Panduan lengkap menggunakan semua alat VizzioDocs</p>
                            </div>
                        </a>

                        <a href="{{ route('bantuan') }}#akun" class="contact-section flex items-start gap-4 p-5 bg-white border border-slate-200 rounded-2xl hover:border-indigo-200 hover:shadow-lg transition-all duration-300 group">
                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">Manajemen Akun</h4>
                                <p class="text-xs text-slate-500 mt-1">Informasi tentang akun, login, dan pengaturan profil</p>
                            </div>
                        </a>

                        <a href="{{ route('bantuan') }}#privasi-keamanan" class="contact-section flex items-start gap-4 p-5 bg-white border border-slate-200 rounded-2xl hover:border-indigo-200 hover:shadow-lg transition-all duration-300 group">
                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">Privasi & Keamanan</h4>
                                <p class="text-xs text-slate-500 mt-1">Kebijakan privasi dan keamanan data VizzioDocs</p>
                            </div>
                        </a>

                        <a href="{{ route('privacy') }}" class="contact-section flex items-start gap-4 p-5 bg-white border border-slate-200 rounded-2xl hover:border-indigo-200 hover:shadow-lg transition-all duration-300 group">
                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">Kebijakan Privasi</h4>
                                <p class="text-xs text-slate-500 mt-1">Baca kebijakan privasi lengkap VizzioDocs</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- ════════════════════════════════════════════════
     TOAST NOTIFICATION
     ════════════════════════════════════════════════ -->
<div id="toast" class="fixed bottom-6 right-6 z-50 hidden">
    <div class="flex items-center gap-3 px-5 py-4 bg-white border border-emerald-200 rounded-2xl shadow-2xl shadow-emerald-200/40 toast-enter">
        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-emerald-50 text-emerald-500">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-800">Pesan terkirim!</p>
            <p class="text-xs text-slate-500">Tim kami akan menghubungi Anda dalam 1x24 jam.</p>
        </div>
        <button onclick="closeToast()" class="ml-2 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
    function handleContactForm(event) {
        event.preventDefault();

        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('submitText');
        const spinner = document.getElementById('submitSpinner');

        // Show loading
        text.classList.add('hidden');
        spinner.classList.remove('hidden');
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');

        // Simulate send (in production, this would be a fetch/axios call)
        setTimeout(() => {
            // Reset button
            text.classList.remove('hidden');
            spinner.classList.add('hidden');
            btn.disabled = false;
            btn.classList.remove('opacity-80', 'cursor-not-allowed');

            // Reset form
            document.getElementById('contactForm').reset();

            // Show toast
            showToast();
        }, 1500);

        return false;
    }

    function showToast() {
        const toast = document.getElementById('toast');
        toast.classList.remove('hidden');
        toast.querySelector('div').className = 'flex items-center gap-3 px-5 py-4 bg-white border border-emerald-200 rounded-2xl shadow-2xl shadow-emerald-200/40 toast-enter';

        // Auto hide after 5 seconds
        setTimeout(closeToast, 5000);
    }

    function closeToast() {
        const toast = document.getElementById('toast');
        const inner = toast.querySelector('div');
        if (inner) {
            inner.className = 'flex items-center gap-3 px-5 py-4 bg-white border border-emerald-200 rounded-2xl shadow-2xl shadow-emerald-200/40 toast-leave';
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }
    }
</script>
@endsection
