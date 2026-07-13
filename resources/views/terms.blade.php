@extends('layouts.app')

@section('title', 'Syarat & Ketentuan — VizzioDocs')

@section('content')
<style>
    /* ── Hero Gradient Animation ── */
    .terms-hero {
        position: relative;
        overflow: hidden;
    }
    .terms-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 600px 400px at 20% 50%, rgba(99,102,241,0.08) 0%, transparent 70%),
                    radial-gradient(ellipse 500px 300px at 80% 30%, rgba(168,85,247,0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ── Floating Orbs ── */
    .terms-orb {
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

    /* ── Staggered Entrance ── */
    .terms-section {
        opacity: 0;
        transform: translateY(20px);
        animation: termsFadeIn 0.5s ease-out forwards;
    }
    .terms-section:nth-child(1) { animation-delay: 0.05s; }
    .terms-section:nth-child(2) { animation-delay: 0.1s; }
    .terms-section:nth-child(3) { animation-delay: 0.15s; }
    .terms-section:nth-child(4) { animation-delay: 0.2s; }
    .terms-section:nth-child(5) { animation-delay: 0.25s; }
    .terms-section:nth-child(6) { animation-delay: 0.3s; }
    .terms-section:nth-child(7) { animation-delay: 0.35s; }
    .terms-section:nth-child(8) { animation-delay: 0.4s; }
    .terms-section:nth-child(9) { animation-delay: 0.45s; }
    .terms-section:nth-child(10) { animation-delay: 0.5s; }
    .terms-section:nth-child(11) { animation-delay: 0.55s; }

    @keyframes termsFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Section Divider ── */
    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99,102,241,0.2), rgba(168,85,247,0.2), transparent);
    }

    /* ── Key point styling ── */
    .key-point {
        position: relative;
        padding-left: 1.75rem;
    }
    .key-point::before {
        content: '→';
        position: absolute;
        left: 0;
        top: 0;
        font-weight: 800;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ── Card highlight ── */
    .highlight-card {
        position: relative;
        overflow: hidden;
    }
    .highlight-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -75%;
        width: 50%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: skewX(-25deg);
        transition: left 0.6s ease;
    }
    .highlight-card:hover::before {
        left: 125%;
    }

    /* ── Badge Pills ── */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* ── Mobile ── */
    @media (max-width: 640px) {
        .terms-title {
            font-size: 1.75rem !important;
        }
    }
</style>

<div class="relative bg-white overflow-hidden">
    <!-- ════ Background Orbs ════ -->
    <div class="terms-orb" style="top:-10%;right:-10%;width:500px;height:500px;background:rgba(99,102,241,0.08);"></div>
    <div class="terms-orb" style="bottom:-10%;left:-10%;width:450px;height:450px;background:rgba(168,85,247,0.06);animation-delay:2s;"></div>
    <div class="terms-orb" style="top:40%;left:50%;width:300px;height:300px;background:rgba(236,72,153,0.05);animation-delay:4s;"></div>

    <!-- ════════════════════════════════════════════════
         HERO SECTION
         ════════════════════════════════════════════════ -->
    <section class="terms-hero relative pt-28 pb-16 sm:pt-32 sm:pb-20 lg:pt-40 lg:pb-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/60 via-white to-white pointer-events-none"></div>
        <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-indigo-200/60 to-transparent"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100/60 text-indigo-700 text-xs sm:text-sm font-bold tracking-wide mb-6 sm:mb-8 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Terakhir diperbarui: 27 Juni 2026</span>
            </div>

            <!-- Title -->
            <h1 class="terms-title text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-tight">
                Syarat &
                <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 bg-clip-text text-transparent">Ketentuan</span>
            </h1>

            <p class="mt-4 sm:mt-6 text-base sm:text-lg text-slate-500 font-medium max-w-3xl mx-auto leading-relaxed">
                Dengan menggunakan layanan VizzioDocs, Anda menyetujui syarat dan ketentuan yang dijelaskan di halaman ini. 
                Mohon baca dengan saksama sebelum menggunakan layanan kami.
            </p>

            <!-- Quick Info -->
            <div class="mt-8 sm:mt-10 flex flex-wrap justify-center gap-3 sm:gap-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600">Berlaku sejak 27 Juni 2026</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600">Layanan Online 100%</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════
         TABLE OF CONTENTS
         ════════════════════════════════════════════════ -->
    <section class="relative pb-4">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-indigo-50/80 via-purple-50/60 to-pink-50/60 rounded-3xl border border-indigo-100/60 p-6 sm:p-8">
                <h2 class="text-sm font-black uppercase tracking-wider text-indigo-600 mb-4">Daftar Isi</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <a href="#penerimaan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        1. Penerimaan Syarat
                    </a>
                    <a href="#layanan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        2. Deskripsi Layanan
                    </a>
                    <a href="#akun" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        3. Pendaftaran Akun
                    </a>
                    <a href="#penggunaan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        4. Penggunaan yang Diizinkan
                    </a>
                    <a href="#kekayaan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        5. Kekayaan Intelektual
                    </a>
                    <a href="#batasan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        6. Batasan Tanggung Jawab
                    </a>
                    <a href="#penghentian" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        7. Penghentian Layanan
                    </a>
                    <a href="#tautan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        8. Tautan Pihak Ketiga
                    </a>
                    <a href="#hukum" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        9. Hukum yang Berlaku
                    </a>
                    <a href="#perubahan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        10. Perubahan Ketentuan
                    </a>
                    <a href="#kontak" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        11. Kontak Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════
         CONTENT SECTIONS
         ════════════════════════════════════════════════ -->
    <section class="relative pb-16 sm:pb-20 lg:pb-28">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <!-- 1. Penerimaan Syarat -->
            <div id="penerimaan" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">1</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Penerimaan Syarat</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        Dengan mengakses atau menggunakan situs web VizzioDocs (<strong class="text-slate-700">vizziocs.com</strong>) dan 
                        layanan yang disediakan, Anda menyatakan telah membaca, memahami, dan menyetujui untuk terikat oleh 
                        seluruh Syarat & Ketentuan ini.
                    </p>
                    <p>
                        Jika Anda tidak setuju dengan sebagian atau seluruh syarat dan ketentuan ini, harap tidak menggunakan 
                        layanan kami.
                    </p>
                    <div class="mt-4 p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-start gap-3">
                        <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <span class="text-sm font-bold text-indigo-800">Catatan Penting:</span>
                            <p class="text-xs sm:text-sm text-indigo-700 mt-1">Syarat & Ketentuan ini mengikat secara hukum (<em class="font-semibold">legally binding</em>). Mohon baca dengan saksama sebelum menggunakan Layanan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 2. Deskripsi Layanan -->
            <div id="layanan" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">2</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Deskripsi Layanan</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        VizzioDocs adalah platform online yang menyediakan alat-alat untuk mengelola dokumen PDF, termasuk 
                        namun tidak terbatas pada: kompresi, penggabungan, pemisahan, konversi format, rotasi, dan 
                        penambahan watermark pada file PDF.
                    </p>
                    <p>Layanan kami bersifat:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <span class="key-point text-sm font-bold text-slate-700">Berbasis Web</span>
                            <p class="text-xs text-slate-400 mt-1 ml-7">Semua alat berjalan langsung di browser tanpa perlu instalasi software.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <span class="key-point text-sm font-bold text-slate-700">Gratis</span>
                            <p class="text-xs text-slate-400 mt-1 ml-7">Fitur dasar tersedia gratis. Fitur premium mungkin diterapkan di masa mendatang.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <span class="key-point text-sm font-bold text-slate-700">Otomatis</span>
                            <p class="text-xs text-slate-400 mt-1 ml-7">File diproses secara otomatis oleh sistem tanpa campur tangan manusia.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <span class="key-point text-sm font-bold text-slate-700">Sementara</span>
                            <p class="text-xs text-slate-400 mt-1 ml-7">File dihapus secara permanen dari server dalam waktu 1 jam setelah diproses.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 3. Pendaftaran Akun -->
            <div id="akun" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">3</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Pendaftaran Akun</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Beberapa fitur VizzioDocs memerlukan pendaftaran akun. Dengan mendaftar, Anda menyetujui bahwa:</p>

                    <ul class="space-y-3 mt-4">
                        <li class="flex items-start gap-3 p-3 bg-white border border-slate-200 rounded-xl">
                            <div class="w-6 h-6 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 text-xs font-black flex-shrink-0 mt-0.5">1</div>
                            <div>
                                <span class="text-sm font-bold text-slate-700">Informasi Akurat</span>
                                <p class="text-xs text-slate-400 mt-0.5">Anda wajib memberikan informasi yang benar, akurat, dan terkini saat mendaftar.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-3 bg-white border border-slate-200 rounded-xl">
                            <div class="w-6 h-6 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 text-xs font-black flex-shrink-0 mt-0.5">2</div>
                            <div>
                                <span class="text-sm font-bold text-slate-700">Kerahasiaan Akun</span>
                                <p class="text-xs text-slate-400 mt-0.5">Anda bertanggung jawab penuh atas kerahasiaan password dan segala aktivitas yang terjadi di akun Anda.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-3 bg-white border border-slate-200 rounded-xl">
                            <div class="w-6 h-6 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 text-xs font-black flex-shrink-0 mt-0.5">3</div>
                            <div>
                                <span class="text-sm font-bold text-slate-700">Satu Akun per Orang</span>
                                <p class="text-xs text-slate-400 mt-0.5">Setiap pengguna hanya diperbolehkan memiliki satu akun, kecuali mendapat izin tertulis dari kami.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-3 bg-white border border-slate-200 rounded-xl">
                            <div class="w-6 h-6 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 text-xs font-black flex-shrink-0 mt-0.5">4</div>
                            <div>
                                <span class="text-sm font-bold text-slate-700">Usia Minimum</span>
                                <p class="text-xs text-slate-400 mt-0.5">Anda harus berusia minimal 13 tahun untuk mendaftar akun. Jika di bawah 13 tahun, pendaftaran harus dengan pengawasan orang tua/wali.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 4. Penggunaan yang Diizinkan -->
            <div id="penggunaan" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">4</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Penggunaan yang Diizinkan</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Anda setuju untuk menggunakan layanan VizzioDocs hanya untuk tujuan yang sah dan sesuai dengan ketentuan berikut:</p>

                    <div class="mt-4 space-y-4">
                        <div class="highlight-card p-4 sm:p-5 bg-emerald-50 border border-emerald-100 rounded-2xl">
                            <h4 class="text-sm font-bold text-emerald-800 flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Diperbolehkan
                            </h4>
                            <ul class="space-y-1.5">
                                <li class="flex items-center gap-2 text-xs sm:text-sm text-emerald-700">
                                    <span class="w-1 h-1 rounded-full bg-emerald-400 flex-shrink-0"></span>
                                    Mengompres, menggabung, memisah, dan mengonversi dokumen pribadi/pekerjaan Anda
                                </li>
                                <li class="flex items-center gap-2 text-xs sm:text-sm text-emerald-700">
                                    <span class="w-1 h-1 rounded-full bg-emerald-400 flex-shrink-0"></span>
                                    Menggunakan alat untuk keperluan pendidikan, riset non-komersial, dan pribadi
                                </li>
                                <li class="flex items-center gap-2 text-xs sm:text-sm text-emerald-700">
                                    <span class="w-1 h-1 rounded-full bg-emerald-400 flex-shrink-0"></span>
                                    Mengakses layanan melalui antarmuka yang disediakan (bukan scraping/API ilegal)
                                </li>
                            </ul>
                        </div>

                        <div class="highlight-card p-4 sm:p-5 bg-red-50 border border-red-100 rounded-2xl">
                            <h4 class="text-sm font-bold text-red-800 flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                Dilarang
                            </h4>
                            <ul class="space-y-1.5">
                                <li class="flex items-center gap-2 text-xs sm:text-sm text-red-700">
                                    <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                                    Mengunggah konten ilegal, melanggar hak cipta, atau melanggar hukum
                                </li>
                                <li class="flex items-center gap-2 text-xs sm:text-sm text-red-700">
                                    <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                                    Menggunakan layanan untuk mendistribusikan malware, virus, atau kode berbahaya
                                </li>
                                <li class="flex items-center gap-2 text-xs sm:text-sm text-red-700">
                                    <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                                    Melakukan rekayasa balik (reverse engineering) pada platform VizzioDocs
                                </li>
                                <li class="flex items-center gap-2 text-xs sm:text-sm text-red-700">
                                    <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                                    Menyalahgunakan sistem dengan mengirimkan permintaan berlebihan (DDoS, spam, dll)
                                </li>
                                <li class="flex items-center gap-2 text-xs sm:text-sm text-red-700">
                                    <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                                    Menggunakan layanan untuk tujuan komersial tanpa izin tertulis dari VizzioDocs
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 5. Kekayaan Intelektual -->
            <div id="kekayaan" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">5</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kekayaan Intelektual</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        Seluruh konten, fitur, dan fungsionalitas VizzioDocs — termasuk namun tidak terbatas pada nama, logo, 
                        desain antarmuka, kode sumber, dan algoritma — adalah milik VizzioDocs dan dilindungi oleh undang-undang 
                        hak cipta, merek dagang, dan kekayaan intelektual yang berlaku.
                    </p>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="flex items-center gap-3 mb-1">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-bold text-slate-700">Milik VizzioDocs</span>
                            </div>
                            <p class="text-xs text-slate-400 ml-7">Logo, nama, desain, kode, dan algoritma dilindungi hak cipta.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="flex items-center gap-3 mb-1">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="text-sm font-bold text-slate-700">Milik Anda</span>
                            </div>
                            <p class="text-xs text-slate-400 ml-7">File dan konten yang Anda unggah tetap menjadi milik Anda sepenuhnya.</p>
                        </div>
                    </div>
                    <p class="mt-4">
                        Kami tidak mengklaim kepemilikan atas file yang Anda unggah, proses, atau buat menggunakan layanan kami. 
                        Hak kepemilikan atas dokumen Anda tetap sepenuhnya milik Anda.
                    </p>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 6. Batasan Tanggung Jawab -->
            <div id="batasan" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">6</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Batasan Tanggung Jawab</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        VizzioDocs menyediakan layanan "sebagaimana adanya" (<em class="font-semibold text-slate-600">as is</em>) dan 
                        "tersedia sebagaimana adanya" (<em class="font-semibold text-slate-600">as available</em>). Kami tidak 
                        memberikan jaminan bahwa layanan akan bebas dari gangguan, aman, atau bebas dari kesalahan.
                    </p>

                    <div class="mt-4 p-4 sm:p-5 bg-amber-50 border border-amber-100 rounded-2xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <span class="text-sm font-bold text-amber-800">Batasan Tanggung Jawab:</span>
                                <p class="text-xs sm:text-sm text-amber-700 mt-1">
                                    Dalam kondisi apa pun VizzioDocs tidak bertanggung jawab atas kerusakan langsung, tidak langsung, 
                                    insidental, khusus, atau konsekuensial yang timbul dari penggunaan atau ketidakmampuan menggunakan 
                                    layanan kami, termasuk kehilangan data, kehilangan keuntungan, atau gangguan bisnis.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p>
                        Anda bertanggung jawab untuk mencadangkan (<em class="font-semibold text-slate-600">backup</em>) file asli 
                        Anda sebelum menggunakan layanan kami. Kami tidak bertanggung jawab atas kehilangan data akibat kegagalan 
                        sistem atau kesalahan pengguna.
                    </p>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 7. Penghentian Layanan -->
            <div id="penghentian" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">7</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Penghentian Layanan</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        VizzioDocs berhak untuk menghentikan atau menangguhkan akses Anda ke layanan, tanpa pemberitahuan 
                        sebelumnya, jika kami yakin Anda telah melanggar Syarat & Ketentuan ini.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-red-200 transition-colors">
                            <span class="key-point text-sm font-bold text-slate-700">Penghentian oleh Kami</span>
                            <p class="text-xs text-slate-400 mt-1 ml-7">Pelanggaran berat atau berulang terhadap ketentuan dapat mengakibatkan penghentian akun secara permanen.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors">
                            <span class="key-point text-sm font-bold text-slate-700">Penghentian oleh Anda</span>
                            <p class="text-xs text-slate-400 mt-1 ml-7">Anda dapat menghentikan penggunaan layanan kapan saja. Untuk penghapusan akun, hubungi support.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 8. Tautan Pihak Ketiga -->
            <div id="tautan" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">8</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Tautan Pihak Ketiga</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        Layanan kami mungkin berisi tautan ke situs web atau layanan pihak ketiga yang tidak dimiliki atau 
                        dikendalikan oleh VizzioDocs.
                    </p>
                    <p>
                        Kami tidak memiliki kendali dan tidak bertanggung jawab atas konten, kebijakan privasi, atau praktik 
                        dari situs atau layanan pihak ketiga mana pun. Anda mengakses situs tersebut dengan risiko Anda sendiri.
                    </p>
                    <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs sm:text-sm text-slate-500">Kami menyarankan Anda untuk membaca syarat & ketentuan serta kebijakan privasi setiap situs pihak ketiga yang Anda kunjungi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 9. Hukum yang Berlaku -->
            <div id="hukum" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">9</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Hukum yang Berlaku</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        Syarat & Ketentuan ini diatur dan ditafsirkan sesuai dengan <strong class="text-slate-700">hukum Republik Indonesia</strong>, 
                        tanpa memperhatikan ketentuan tentang pertentangan hukum.
                    </p>
                    <p>
                        Setiap sengketa yang timbul dari atau terkait dengan Syarat & Ketentuan ini akan diselesaikan melalui 
                        musyawarah terlebih dahulu. Jika tidak tercapai kesepakatan, sengketa akan diselesaikan melalui 
                        pengadilan yang berwenang di <strong class="text-slate-700">Indonesia</strong>.
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Yurisdiksi: Republik Indonesia</span>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 10. Perubahan Ketentuan -->
            <div id="perubahan" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">10</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Perubahan Ketentuan</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        VizzioDocs berhak untuk mengubah atau memperbarui Syarat & Ketentuan ini sewaktu-waktu. Perubahan 
                        akan diumumkan melalui:
                    </p>
                    <ul class="space-y-2 mt-4">
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-6 h-6 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 text-xs font-black flex-shrink-0">1</span>
                            <span class="text-slate-600">Pembaruan di halaman ini dengan tanggal revisi terbaru</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-6 h-6 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 text-xs font-black flex-shrink-0">2</span>
                            <span class="text-slate-600">Pemberitahuan melalui email (untuk pengguna terdaftar)</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-6 h-6 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 text-xs font-black flex-shrink-0">3</span>
                            <span class="text-slate-600">Notifikasi di situs untuk perubahan material</span>
                        </li>
                    </ul>
                    <p class="mt-4">
                        Dengan terus menggunakan layanan setelah perubahan diberlakukan, Anda dianggap menyetujui perubahan tersebut. 
                        Jika Anda tidak setuju dengan perubahan, Anda harus berhenti menggunakan layanan dan dapat menghapus akun Anda.
                    </p>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 11. Kontak Kami -->
            <div id="kontak" class="terms-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">11</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kontak Kami</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-6 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        Jika Anda memiliki pertanyaan, keluhan, atau membutuhkan klarifikasi mengenai Syarat & Ketentuan ini, 
                        silakan hubungi kami:
                    </p>

                    <div class="bg-gradient-to-br from-indigo-50/80 via-purple-50/60 to-pink-50/60 rounded-2xl border border-indigo-100/60 p-6 sm:p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="w-12 h-12 mx-auto flex items-center justify-center rounded-xl bg-white shadow-sm mb-3">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700 mb-1">Email</h4>
                                <a href="mailto:support@vizziocs.com" class="text-xs text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">support@vizziocs.com</a>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 mx-auto flex items-center justify-center rounded-xl bg-white shadow-sm mb-3">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700 mb-1">Respon Cepat</h4>
                                <p class="text-xs text-slate-400">1x24 jam kerja</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 mx-auto flex items-center justify-center rounded-xl bg-white shadow-sm mb-3">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700 mb-1">Yurisdiksi</h4>
                                <p class="text-xs text-slate-400">Hukum Indonesia</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-slate-400 text-center">
                        Terima kasih telah menggunakan VizzioDocs. Kami berkomitmen untuk memberikan layanan terbaik untuk Anda.
                    </p>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
