@extends('layouts.app')

@section('title', 'Kebijakan Privasi — VizzioDocs')

@section('content')
<style>
    /* ── Hero Gradient Animation ── */
    .privacy-hero {
        position: relative;
        overflow: hidden;
    }
    .privacy-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 600px 400px at 20% 50%, rgba(99,102,241,0.08) 0%, transparent 70%),
                    radial-gradient(ellipse 500px 300px at 80% 30%, rgba(168,85,247,0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ── Floating Orbs ── */
    .privacy-orb {
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
    .policy-section {
        opacity: 0;
        transform: translateY(20px);
        animation: policyFadeIn 0.5s ease-out forwards;
    }
    .policy-section:nth-child(1) { animation-delay: 0.05s; }
    .policy-section:nth-child(2) { animation-delay: 0.1s; }
    .policy-section:nth-child(3) { animation-delay: 0.15s; }
    .policy-section:nth-child(4) { animation-delay: 0.2s; }
    .policy-section:nth-child(5) { animation-delay: 0.25s; }
    .policy-section:nth-child(6) { animation-delay: 0.3s; }
    .policy-section:nth-child(7) { animation-delay: 0.35s; }
    .policy-section:nth-child(8) { animation-delay: 0.4s; }
    .policy-section:nth-child(9) { animation-delay: 0.45s; }
    .policy-section:nth-child(10) { animation-delay: 0.5s; }

    @keyframes policyFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Smooth Accordion ── */
    .policy-content {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .policy-content.open {
        grid-template-rows: 1fr;
    }
    .policy-content > div {
        overflow: hidden;
    }

    /* ── Section Divider ── */
    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99,102,241,0.2), rgba(168,85,247,0.2), transparent);
    }

    /* ── Benefit checkmark list ── */
    .benefit-item {
        position: relative;
        padding-left: 1.75rem;
    }
    .benefit-item::before {
        content: '✓';
        position: absolute;
        left: 0;
        top: 0;
        font-weight: 800;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ── Table styling ── */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .data-table thead {
        background: linear-gradient(135deg, #6366f1, #a855f7);
    }
    .data-table th {
        color: white;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 16px;
        text-align: left;
    }
    .data-table td {
        padding: 12px 16px;
        font-size: 0.9rem;
        color: #475569;
        border-bottom: 1px solid #f1f5f9;
    }
    .data-table tbody tr:hover {
        background: #f8fafc;
    }
    .data-table tbody tr:last-child td {
        border-bottom: none;
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
        .privacy-title {
            font-size: 1.75rem !important;
        }
        .data-table {
            font-size: 0.8rem;
        }
        .data-table th,
        .data-table td {
            padding: 8px 10px;
        }
    }
</style>

<div class="relative bg-white overflow-hidden">
    <!-- ════ Background Orbs ════ -->
    <div class="privacy-orb" style="top:-10%;right:-10%;width:500px;height:500px;background:rgba(99,102,241,0.08);"></div>
    <div class="privacy-orb" style="bottom:-10%;left:-10%;width:450px;height:450px;background:rgba(168,85,247,0.06);animation-delay:2s;"></div>
    <div class="privacy-orb" style="top:40%;left:50%;width:300px;height:300px;background:rgba(236,72,153,0.05);animation-delay:4s;"></div>

    <!-- ════════════════════════════════════════════════
         HERO SECTION
         ════════════════════════════════════════════════ -->
    <section class="privacy-hero relative pt-28 pb-16 sm:pt-32 sm:pb-20 lg:pt-40 lg:pb-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/60 via-white to-white pointer-events-none"></div>
        <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-indigo-200/60 to-transparent"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100/60 text-indigo-700 text-xs sm:text-sm font-bold tracking-wide mb-6 sm:mb-8 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Terakhir diperbarui: 27 Juni 2026</span>
            </div>

            <!-- Title -->
            <h1 class="privacy-title text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-tight">
                Kebijakan
                <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 bg-clip-text text-transparent">Privasi</span>
            </h1>

            <p class="mt-4 sm:mt-6 text-base sm:text-lg text-slate-500 font-medium max-w-3xl mx-auto leading-relaxed">
                VizzioDocs berkomitmen untuk melindungi privasi Anda. Dokumen ini menjelaskan bagaimana kami mengumpulkan, 
                menggunakan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami.
            </p>

            <!-- Quick Stats -->
            <div class="mt-8 sm:mt-10 flex flex-wrap justify-center gap-3 sm:gap-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600">Enkripsi SSL 256-bit</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600">Hapus otomatis 1 jam</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600">Tanpa log file</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════
         TABLE OF CONTENTS (Sticky Sidebar)
         ════════════════════════════════════════════════ -->
    <section class="relative pb-4">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-indigo-50/80 via-purple-50/60 to-pink-50/60 rounded-3xl border border-indigo-100/60 p-6 sm:p-8">
                <h2 class="text-sm font-black uppercase tracking-wider text-indigo-600 mb-4">Daftar Isi</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <a href="#pendahuluan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        1. Pendahuluan
                    </a>
                    <a href="#data-dikumpulkan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        2. Data yang Kami Kumpulkan
                    </a>
                    <a href="#penggunaan-data" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        3. Penggunaan Data
                    </a>
                    <a href="#penyimpanan-file" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        4. Penyimpanan & Penghapusan File
                    </a>
                    <a href="#keamanan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        5. Keamanan Data
                    </a>
                    <a href="#cookie" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        6. Cookie & Pelacakan
                    </a>
                    <a href="#pihak-ketiga" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        7. Pihak Ketiga
                    </a>
                    <a href="#hak-anda" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        8. Hak Anda
                    </a>
                    <a href="#perubahan" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        9. Perubahan Kebijakan
                    </a>
                    <a href="#kontak" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors py-1.5 px-3 rounded-lg hover:bg-white/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        10. Kontak Kami
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

            <!-- 1. Pendahuluan -->
            <div id="pendahuluan" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">1</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Pendahuluan</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        Selamat datang di VizzioDocs. Privasi Anda adalah prioritas utama kami. Kebijakan Privasi ini berlaku untuk 
                        seluruh layanan, fitur, dan alat yang tersedia di <strong class="text-slate-700">vizziocs.com</strong> (selanjutnya disebut "Layanan").
                    </p>
                    <p>
                        Dengan menggunakan Layanan VizzioDocs, Anda menyetujui pengumpulan dan penggunaan informasi sesuai dengan 
                        Kebijakan Privasi ini. Jika Anda tidak setuju dengan kebijakan ini, harap jangan menggunakan Layanan kami.
                    </p>
                    <p>
                        Kami memahami bahwa dokumen Anda bersifat sensitif. Oleh karena itu, kami merancang sistem kami dengan 
                        prinsip <em class="text-slate-600 font-semibold">privacy-first</em> — file Anda diproses secara otomatis, tidak pernah 
                        diakses oleh manusia, dan dihapus dalam waktu 1 jam.
                    </p>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 2. Data yang Kami Kumpulkan -->
            <div id="data-dikumpulkan" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">2</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Data yang Kami Kumpulkan</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Kami hanya mengumpulkan data yang benar-benar diperlukan untuk menyediakan Layanan terbaik kepada Anda:</p>

                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tipe Data</th>
                                    <th>Detail</th>
                                    <th>Dikumpulkan Saat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-bold text-slate-700">Informasi Akun</td>
                                    <td>Nama, alamat email, password (terenkripsi)</td>
                                    <td><span class="badge-pill bg-indigo-50 text-indigo-700">Registrasi</span></td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-slate-700">Data Negara</td>
                                    <td>Negara asal (deteksi otomatis atau manual)</td>
                                    <td><span class="badge-pill bg-purple-50 text-purple-700">Registrasi</span></td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-slate-700">File Dokumen</td>
                                    <td>File PDF/gambar yang diunggah untuk diproses</td>
                                    <td><span class="badge-pill bg-emerald-50 text-emerald-700">Penggunaan alat</span></td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-slate-700">Data Teknis</td>
                                    <td>Alamat IP, tipe browser, sistem operasi, timestamp</td>
                                    <td><span class="badge-pill bg-amber-50 text-amber-700">Otomatis</span></td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-slate-700">Cookie</td>
                                    <td>Session token, preferensi pengguna</td>
                                    <td><span class="badge-pill bg-rose-50 text-rose-700">Saat kunjungan</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <span class="text-sm font-bold text-amber-800">Penting:</span>
                                <p class="text-xs sm:text-sm text-amber-700 mt-1">Kami <strong class="text-amber-800">TIDAK</strong> mengumpulkan data sensitif seperti nomor KTP, informasi keuangan, alamat rumah, atau data pribadi lainnya yang tidak relevan dengan Layanan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 3. Penggunaan Data -->
            <div id="penggunaan-data" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">3</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Penggunaan Data</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Data yang kami kumpulkan digunakan untuk tujuan berikut:</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors">
                            <span class="benefit-item text-sm font-bold text-slate-700 mb-1 block">Operasional Layanan</span>
                            <p class="text-xs text-slate-400 ml-7">Memproses file Anda sesuai alat yang dipilih (kompres, gabung, pisah, dll)</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors">
                            <span class="benefit-item text-sm font-bold text-slate-700 mb-1 block">Akun & Autentikasi</span>
                            <p class="text-xs text-slate-400 ml-7">Mengelola login, registrasi, dan keamanan akun Anda</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors">
                            <span class="benefit-item text-sm font-bold text-slate-700 mb-1 block">Peningkatan Layanan</span>
                            <p class="text-xs text-slate-400 ml-7">Menganalisis pola penggunaan untuk mengembangkan fitur baru</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors">
                            <span class="benefit-item text-sm font-bold text-slate-700 mb-1 block">Komunikasi</span>
                            <p class="text-xs text-slate-400 ml-7">Mengirim notifikasi penting terkait akun dan pembaruan layanan</p>
                        </div>
                    </div>

                    <p class="mt-2">Kami <strong class="text-slate-700 font-bold">TIDAK PERNAH</strong> menjual, menyewakan, atau membagikan data pribadi Anda kepada pihak ketiga untuk tujuan pemasaran atau periklanan.</p>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 4. Penyimpanan & Penghapusan File -->
            <div id="penyimpanan-file" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">4</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Penyimpanan & Penghapusan File</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Kebijakan kami terkait file yang Anda unggah sangat ketat untuk memastikan privasi Anda:</p>

                    <div class="space-y-4 mt-4">
                        <div class="flex items-start gap-4 p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Penghapusan Otomatis</h4>
                                <p class="text-xs sm:text-sm text-slate-500 mt-1">Semua file yang diunggah secara otomatis dihapus dari server dalam waktu <strong class="text-slate-700">maksimal 1 jam</strong> setelah pemrosesan selesai. Penghapusan bersifat permanen dan tidak dapat dikembalikan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-50 text-red-600 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Tidak Ada Penyimpanan Permanen</h4>
                                <p class="text-xs sm:text-sm text-slate-500 mt-1">Kami tidak menyimpan salinan permanen file Anda. File hanya disimpan sementara selama proses berlangsung dan langsung dihapus. Dokumen Anda tidak pernah digunakan untuk pelatihan AI atau tujuan lainnya.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Enkripsi Penuh</h4>
                                <p class="text-xs sm:text-sm text-slate-500 mt-1">Semua transfer file menggunakan enkripsi SSL/TLS 256-bit. File yang disimpan sementara di server juga dalam keadaan terenkripsi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 5. Keamanan Data -->
            <div id="keamanan" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">5</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Keamanan Data</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang ketat untuk melindungi data Anda:</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span class="text-sm font-bold text-slate-700">Enkripsi SSL/TLS</span>
                            </div>
                            <p class="text-xs text-slate-400">Semua data ditransfer melalui koneksi terenkripsi HTTPS dengan sertifikat SSL 256-bit.</p>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm font-bold text-slate-700">Enkripsi Server</span>
                            </div>
                            <p class="text-xs text-slate-400">File dalam penyimpanan sementara dienkripsi menggunakan standar AES-256.</p>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="text-sm font-bold text-slate-700">Firewall & Monitoring</span>
                            </div>
                            <p class="text-xs text-slate-400">Server dilindungi firewall dan sistem deteksi intrusi 24/7.</p>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="text-sm font-bold text-slate-700">Audit Berkala</span>
                            </div>
                            <p class="text-xs text-slate-400">Kami secara rutin melakukan audit keamanan dan penetration testing.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 6. Cookie & Pelacakan -->
            <div id="cookie" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">6</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Cookie & Pelacakan</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Kami menggunakan cookie dan teknologi serupa untuk meningkatkan pengalaman Anda:</p>

                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Jenis Cookie</th>
                                    <th>Tujuan</th>
                                    <th>Durasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-bold text-slate-700">Session Cookie</td>
                                    <td>Menyimpan status login sementara</td>
                                    <td>Sesi browser</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-slate-700">CSRF Token</td>
                                    <td>Melindungi dari serangan cross-site request</td>
                                    <td>Sesi browser</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-slate-700">Preference Cookie</td>
                                    <td>Mengingat preferensi tampilan Anda</td>
                                    <td>1 tahun</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-slate-700">Analytics Cookie</td>
                                    <td>Data agregat anonym untuk analisis penggunaan</td>
                                    <td>1 tahun</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 p-4 bg-white border border-slate-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs sm:text-sm text-slate-500">Anda dapat mengatur preferensi cookie melalui pengaturan browser Anda. Menonaktifkan cookie dapat memengaruhi beberapa fungsionalitas situs.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 7. Pihak Ketiga -->
            <div id="pihak-ketiga" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">7</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Pihak Ketiga</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Kami tidak menjual data Anda. Namun, kami menggunakan layanan pihak ketiga yang tepercaya untuk menjalankan Layanan:</p>

                    <div class="space-y-3 mt-4">
                        <div class="flex items-center justify-between p-3 sm:p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-black text-xs">G</div>
                                <div>
                                    <span class="text-sm font-bold text-slate-700">Google Cloud Platform</span>
                                    <p class="text-xs text-slate-400">Hosting server & penyimpanan sementara</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="flex items-center justify-between p-3 sm:p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-black text-xs">ip</div>
                                <div>
                                    <span class="text-sm font-bold text-slate-700">ip-api.com</span>
                                    <p class="text-xs text-slate-400">Deteksi negara asal (data agregat, tidak menyimpan IP)</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>

                    <p class="text-xs text-slate-400 mt-2">Setiap pihak ketiga di atas telah melalui proses seleksi ketat dan memiliki komitmen keamanan yang setara dengan standar kami.</p>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 8. Hak Anda -->
            <div id="hak-anda" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">8</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Hak Anda</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>Sesuai dengan peraturan perlindungan data yang berlaku (termasuk GDPR dan UU Perlindungan Data Pribadi Indonesia), Anda memiliki hak-hak berikut:</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 text-sm font-black group-hover:scale-110 transition-transform">H1</div>
                                <span class="text-sm font-bold text-slate-700">Hak Akses</span>
                            </div>
                            <p class="text-xs text-slate-400 ml-11">Anda berhak mengetahui data apa yang kami simpan tentang Anda.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-purple-50 text-purple-600 text-sm font-black group-hover:scale-110 transition-transform">H2</div>
                                <span class="text-sm font-bold text-slate-700">Hak Perbaikan</span>
                            </div>
                            <p class="text-xs text-slate-400 ml-11">Anda dapat memperbarui data yang tidak akurat kapan saja.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 text-sm font-black group-hover:scale-110 transition-transform">H3</div>
                                <span class="text-sm font-bold text-slate-700">Hak Penghapusan</span>
                            </div>
                            <p class="text-xs text-slate-400 ml-11">Anda dapat meminta penghapusan akun dan data Anda kapan saja.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-200 transition-colors group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 text-sm font-black group-hover:scale-110 transition-transform">H4</div>
                                <span class="text-sm font-bold text-slate-700">Hak Portabilitas</span>
                            </div>
                            <p class="text-xs text-slate-400 ml-11">Anda dapat meminta ekspor data Anda dalam format terstruktur.</p>
                        </div>
                    </div>

                    <p class="mt-4">Untuk menggunakan hak-hak di atas, hubungi kami melalui email <a href="mailto:support@vizziocs.com" class="text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">support@vizziocs.com</a> dan kami akan merespon dalam waktu 3x24 jam.</p>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 9. Perubahan Kebijakan -->
            <div id="perubahan" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">9</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Perubahan Kebijakan</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        Kebijakan Privasi ini dapat diperbarui dari waktu ke waktu. Kami akan memberitahukan perubahan material 
                        melalui email (jika Anda terdaftar) atau pemberitahuan di situs kami. Tanggal "Terakhir diperbarui" 
                        di bagian atas halaman ini akan selalu menampilkan versi terbaru.
                    </p>
                    <p>
                        Kami menyarankan Anda untuk meninjau halaman ini secara berkala untuk tetap mendapatkan informasi 
                        tentang bagaimana kami melindungi data Anda.
                    </p>
                </div>
            </div>

            <div class="section-divider my-10 sm:my-14"></div>

            <!-- 10. Kontak Kami -->
            <div id="kontak" class="policy-section scroll-mt-28">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-black shadow-lg shadow-indigo-200">10</div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kontak Kami</h2>
                </div>
                <div class="ml-0 sm:ml-14 space-y-6 text-sm sm:text-base text-slate-500 leading-relaxed">
                    <p>
                        Jika Anda memiliki pertanyaan, keluhan, atau permintaan terkait Kebijakan Privasi ini atau praktik 
                        data kami, jangan ragu untuk menghubungi kami:
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
                                <h4 class="text-sm font-bold text-slate-700 mb-1">Hak Privasi</h4>
                                <p class="text-xs text-slate-400">Hapus akun via email</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-slate-400 text-center">
                        Terima kasih telah mempercayai VizzioDocs. Privasi Anda adalah komitmen kami.
                    </p>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
