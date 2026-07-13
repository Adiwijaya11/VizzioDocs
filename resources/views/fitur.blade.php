@extends('layouts.app')

@section('title', 'Semua Fitur — VizzioDocs')

@section('content')
{{-- ════════════════════════════════════════════════════════════════
     ╔═█  PHASE 1 INTEGRATION — Global Notifications & Progress Tracking
     ╚═══════════════════════════════════════════════════════════════ --}}
@include('components.notifications')

{{-- Load Phase 1 PDF Processing Utilities --}}
<script src="{{ asset('js/pdf-processing-utils.js') }}" defer></script>

{{--
    Fitur header + search + filter section.
    Setiap kartu alat di #features-grid memakai:
      class="feature-card" data-categories="populer manipulasi" data-feature-name="gabungkan pdf merge combine"
    data-categories boleh diisi lebih dari satu kategori (dipisah spasi). Filter "all" selalu cocok.
    Semua logika pencarian, filter, accordion FAQ, dan animasi ada dalam satu script terpadu
    di bagian @push('scripts') paling bawah.
--}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

<style>
    /* ═══ PREMIUM SMOOTH HOVER — cubic-bezier yang bikin transisi jauh lebih natural ═══ */
    .feature-card {
        transition: all 600ms cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        will-change: transform, box-shadow, border-color;
    }
    .feature-card .transition-all,
    .feature-card .transition-transform,
    .feature-card .transition-colors {
        transition-duration: 500ms !important;
        transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }
    .feature-card .transition-all.duration-300,
    .feature-card [class*="duration-300"] {
        transition-duration: 400ms !important;
        transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }
    /* Efek tambahan: border glow halus pas hover */
    .feature-card::after {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        background: inherit;
        opacity: 0;
        transition: opacity 600ms cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: -1;
    }
    .feature-card:hover::after {
        opacity: 1;
    }
    /* Icon container — scale lebih dramatis tapi tetap halus */
    .feature-card:hover [class*="rounded-2xl"] {
        transform: scale(1.12) rotate(6deg) !important;
    }
    /* Pastiin badge "POPULER" ga ikut ke-transform */
    .feature-card [class*="rounded-full"],
    .feature-card [class*="top-4"] {
        transform: none !important;
    }
    /* Hover search-card juga lebih smoothe */
    .search-card-wrapper {
        transition: all 400ms cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }
    /* Pill hover lebih smoothe */
    .filter-pill {
        transition: all 250ms cubic-bezier(0.34, 1.56, 0.64, 1);
    }
</style>

<div class="relative min-h-screen bg-gradient-to-br from-[#F8FAFC] via-[#EFF2FE] to-[#FDF4FF] py-10 md:py-14 overflow-hidden font-[Inter]">

    {{-- Soft decorative background mesh --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_60%_40%_at_50%_0%,rgba(99,102,241,0.12),transparent),radial-gradient(ellipse_50%_50%_at_85%_55%,rgba(167,139,250,0.1),transparent),radial-gradient(ellipse_50%_40%_at_15%_95%,rgba(244,63,94,0.08),transparent)] pointer-events-none"></div>
    <div class="absolute top-[15%] left-[-5%] w-[350px] h-[350px] rounded-full bg-indigo-400/10 blur-3xl pointer-events-none"></div>
    <div class="absolute top-[45%] right-[-5%] w-[400px] h-[400px] rounded-full bg-purple-400/8 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-[10%] left-[10%] w-[450px] h-[450px] rounded-full bg-pink-400/8 blur-3xl pointer-events-none"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#00000004_1px,transparent_1px),linear-gradient(to_bottom,#00000004_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="flex items-center space-x-2 text-sm text-slate-500 mb-6" id="fitur-breadcrumb">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 11.5L12 4l9 7.5M5 10v9a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-9"/>
                </svg>
                Halaman Utama
            </a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-800 font-semibold">Semua Fitur</span>
        </div>

        {{-- Heading --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8 mb-9">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm text-xs font-bold text-slate-700 mb-4">
                    <span class="relative flex w-2 h-2">
                        <span class="absolute inset-0 rounded-full bg-indigo-500 animate-ping opacity-75"></span>
                        <span class="relative rounded-full w-2 h-2 bg-indigo-500"></span>
                    </span>
                    <span><span class="font-mono tabular-nums" id="tool-count" data-target="28">0</span> alat premium &mdash; gratis selamanya</span>
                </div>
                <h1 class="font-['Space_Grotesk'] text-4xl sm:text-5xl font-semibold text-[#14122B] tracking-tight leading-[1.1] mb-3">
                    Jelajahi <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-pink-600 bg-clip-text text-transparent">semua fitur</span> eksklusif
                </h1>
                <p class="text-slate-500 text-sm sm:text-base max-w-2xl leading-relaxed">
                    Temukan solusi lengkap untuk mengonversi, mengompres, menggabungkan, dan memanipulasi dokumen &mdash; cepat, aman, tanpa biaya.
                </p>
            </div>

            {{-- Quick trust strip --}}
            <div class="flex items-center gap-5 sm:gap-6 shrink-0">
                <div class="text-left">
                    <p class="font-['Space_Grotesk'] text-2xl font-semibold text-[#14122B] tabular-nums" data-count-to="28">0</p>
                    <p class="text-xs text-slate-500 font-medium">Alat tersedia</p>
                </div>
                <div class="w-px h-9 bg-slate-200"></div>
                <div class="text-left">
                    <p class="font-['Space_Grotesk'] text-2xl font-semibold text-[#14122B] tabular-nums" data-count-to="100">0<span>%</span></p>
                    <p class="text-xs text-slate-500 font-medium">Gratis dipakai</p>
                </div>
                <div class="w-px h-9 bg-slate-200"></div>
                <div class="text-left">
                    <p class="font-['Space_Grotesk'] text-2xl font-semibold text-[#14122B] tabular-nums" data-count-to="2">0<span>d</span></p>
                    <p class="text-xs text-slate-500 font-medium">Rata-rata proses</p>
                </div>
            </div>
        </div>

        {{-- Search & filter card --}}
        <div class="relative mt-6 mb-8">
            <!-- Soft glow behind card -->
            <div class="absolute -inset-2 bg-gradient-to-r from-slate-200/40 via-slate-100/30 to-slate-200/40 rounded-3xl blur-xl pointer-events-none"></div>

            <div class="search-card-wrapper relative bg-white border border-slate-200 rounded-2xl shadow-[0_2px_16px_rgba(15,23,42,0.06),0_1px_4px_rgba(15,23,42,0.04)] transition-all duration-300 hover:shadow-[0_4px_24px_rgba(15,23,42,0.08)] hover:border-slate-300 p-5 sm:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">

                    {{-- Search Input --}}
                    <div class="lg:col-span-5 relative group">
                        <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-xl transition-all duration-250 group-focus-within:border-slate-400 group-focus-within:bg-white group-focus-within:shadow-[0_0_0_3px_rgba(148,163,184,0.15)]">
                            <svg class="w-4.5 h-4.5 text-slate-400 ml-4 shrink-0 group-focus-within:text-slate-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                type="text"
                                id="fitur-search"
                                class="w-full bg-transparent pl-3 pr-3 py-3 text-slate-800 placeholder-slate-400 text-sm font-medium outline-none"
                                placeholder="Cari fitur... misal: gabung, kompres, word"
                                autocomplete="off"
                            >
                            <button id="search-clear" type="button" class="hidden mr-3 w-6 h-6 flex items-center justify-center rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all" title="Hapus pencarian">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Filter Pills --}}
                    <div class="lg:col-span-7">
                        <div id="pill-row" class="relative flex flex-wrap items-center gap-2 lg:justify-end">

                            <button class="filter-pill active" data-filter="all" aria-pressed="true">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                                Semua
                            </button>
                            <button class="filter-pill" data-filter="populer" aria-pressed="false">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2c1 3-3 4-3 8a3 3 0 006 0c0-1-1-2-1-2s2 1 2 4a5 5 0 11-10 0c0-4 3-6 3-6s-1 1-1 3"/></svg>
                                Populer
                            </button>
                            <button class="filter-pill" data-filter="manipulasi" aria-pressed="false">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                                Edit
                            </button>
                            <button class="filter-pill" data-filter="gambar" aria-pressed="false">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/></svg>
                                Gambar
                            </button>
                            <button class="filter-pill" data-filter="konversi" aria-pressed="false">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 1l4 4-4 4M3 11V9a4 4 0 014-4h14M7 23l-4-4 4-4M21 13v2a4 4 0 01-4 4H3"/></svg>
                                Konversi
                            </button>
                            <button class="filter-pill" data-filter="keamanan" aria-pressed="false">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                Keamanan
                            </button>
                            <button class="filter-pill" data-filter="lanjutan" aria-pressed="false">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L4 14h6l-1 8 9-12h-6z"/></svg>
                                Lainnya
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Results counter (single source of truth — live updated by script below) --}}
        <div class="flex items-center justify-between mb-8 px-2 mt-6">
            <p id="results-count" class="text-sm font-semibold text-slate-500" aria-live="polite">
                Menampilkan <span id="visible-count" class="text-indigo-600 font-black">28</span> fitur
            </p>
            <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-100/80 backdrop-blur rounded-lg px-3 py-2 border border-slate-200">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                </svg>
                <span>Diurutkan berdasarkan popularitas</span>
            </div>
        </div>

        {{-- ═══ SKELETON LOADING GRID ═══ --}}
        <div id="skeleton-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 hidden">
            @for ($i = 0; $i < 8; $i++)
            <div class="bg-white/80 backdrop-blur-sm border-2 border-slate-200 p-6 rounded-3xl flex flex-col justify-between h-64 overflow-hidden">
                <div class="animate-pulse space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-slate-200/80"></div>
                    <div class="space-y-2.5">
                        <div class="h-5 bg-slate-200/80 rounded w-3/4"></div>
                        <div class="space-y-2">
                            <div class="h-3 bg-slate-200/80 rounded w-full"></div>
                            <div class="h-3 bg-slate-200/80 rounded w-2/3"></div>
                        </div>
                    </div>
                </div>
                <div class="animate-pulse">
                    <div class="h-4 bg-slate-200/80 rounded w-1/3"></div>
                </div>
            </div>
            @endfor
        </div>

        @php
        $toolRouteMapping = [
            '/merge' => 'merge.index',
            '/compress' => 'compress.index',
            '/split' => 'split.index',
            '/jpg-to-pdf' => 'jpg-to-pdf.index',
            '/png-to-pdf' => 'png-to-pdf.index',
            '/pdf-to-jpg' => 'pdf-to-jpg.index',
            '/rotate' => 'rotate.index',
            '/word-to-pdf' => 'word-to-pdf.index',
            '/pdf-to-word' => 'pdf-to-word.index',
            '/excel-to-pdf' => 'excel-to-pdf.index',
            '/crop' => 'crop.index',
            '/pdf-to-txt' => 'pdf-to-txt.index',
            '/pdf-to-markdown' => 'pdf-to-markdown.index',
            '/remove-pages' => 'remove-pages.index',
            '/extract-pages' => 'extract-pages.index',
            '/organize-pdf' => 'organize-pdf.index',
            '/watermark-pdf' => 'watermark-pdf.index',
            '/protect-pdf' => 'protect-pdf.index',
            '/unlock-pdf' => 'unlock-pdf.index',
            '/pdf-to-excel' => 'pdf-to-excel.index',
            '/html-to-pdf' => 'html-to-pdf.index',
            '/scan-to-pdf' => 'scan-to-pdf.index',
            '/optimize-pdf' => 'optimize-pdf.index',
            '/repair-pdf' => 'repair-pdf.index',
            '/page-numbers' => 'page-numbers.index',
            '/pdf-to-pptx' => 'pdf-to-pptx.index',
            '/pptx-to-pdf' => 'pptx-to-pdf.index',
            '/pdf-to-pdfa' => 'pdf-to-pdfa.index',
        ];
        $toolLocksByRoute = $toolLocks->keyBy('tool_route')->map->is_locked;
        @endphp

        {{-- ═══ UNIFIED FEATURES GRID ═══ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="features-grid">

            {{-- 1. Gabungkan PDF --}}
            <a href="{{ route('merge.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-purple-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-purple-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="gabungkan pdf merge combine" data-categories="populer manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-pink-500/0 group-hover:from-purple-500/5 group-hover:to-pink-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 text-purple-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">01</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black shadow-md tracking-wider">POPULER</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg shadow-purple-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-purple-600 transition-colors mb-2">Gabungkan PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Gabungkan beberapa file PDF jadi satu dokumen rapi.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-purple-600 group-hover:text-pink-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Gabungkan</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 2. Kompres PDF --}}
            <a href="{{ route('compress.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-indigo-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="kompres pdf compress shrink" data-categories="populer manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-purple-500/0 group-hover:from-indigo-500/5 group-hover:to-purple-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">02</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black shadow-md tracking-wider">POPULER</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/50 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2">Kompres PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Kecilkan ukuran PDF tanpa mengurangi kualitas.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-indigo-600 group-hover:text-purple-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Kompres</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 3. JPG ke PDF --}}
            <a href="{{ route('jpg-to-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-emerald-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-emerald-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="jpg gambar image convert" data-categories="populer konversi gambar">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-green-500/0 group-hover:from-emerald-500/5 group-hover:to-green-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-emerald-100 to-green-100 text-emerald-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">03</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black shadow-md tracking-wider">POPULER</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors mb-2">JPG ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi gambar JPG jadi dokumen PDF berkualitas.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-emerald-600 group-hover:text-green-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Konversi</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 4. PDF ke Word --}}
            <a href="{{ route('pdf-to-word.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-blue-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-blue-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="word docx convert" data-categories="populer konversi">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-indigo-500/0 group-hover:from-blue-500/5 group-hover:to-indigo-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">04</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black shadow-md tracking-wider">POPULER</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors mb-2">PDF ke Word</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ubah file PDF jadi dokumen Word (.docx) yang bisa diedit.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-blue-600 group-hover:text-indigo-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Konversi</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 5. Optimize PDF --}}
            <a href="{{ route('optimize-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-lime-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-lime-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="optimize optimasi kompres pdf" data-categories="populer manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-lime-500/0 to-green-500/0 group-hover:from-lime-500/5 group-hover:to-green-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-lime-100 to-green-100 text-lime-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">05</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-lime-400 to-green-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-lime-500 to-green-600 text-white shadow-lg shadow-lime-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-lime-600 transition-colors mb-2">Optimize PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Optimasi ukuran PDF jadi lebih kecil tanpa kehilangan kualitas.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-lime-600 group-hover:text-green-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Optimasi</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 6. Pisahkan PDF --}}
            <a href="{{ route('split.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-sky-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-sky-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="pisahkan split extract" data-categories="manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-cyan-500/0 group-hover:from-sky-500/5 group-hover:to-cyan-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-sky-100 to-cyan-100 text-sky-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">06</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-cyan-600 text-white shadow-lg shadow-sky-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 11-4.243 4.243 3 3 0 014.243-4.243zm0-5.758a3 3 0 11-4.243-4.243 3 3 0 014.243 4.243z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-sky-600 transition-colors mb-2">Pisahkan PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekstrak halaman tertentu atau pisahkan per halaman.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-sky-600 group-hover:text-cyan-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Pisahkan</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 7. Crop PDF --}}
            <a href="{{ route('crop.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-rose-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-rose-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="crop potong edit" data-categories="manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-rose-500/0 to-pink-500/0 group-hover:from-rose-500/5 group-hover:to-pink-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-rose-100 to-pink-100 text-rose-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">07</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-rose-400 to-pink-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-lg shadow-rose-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-rose-600 transition-colors mb-2">Crop PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Potong area tertentu dari halaman PDF secara interaktif.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-rose-600 group-hover:text-pink-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Crop</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 8. Putar PDF --}}
            <a href="{{ route('rotate.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-amber-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-amber-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="putar rotate pdf" data-categories="manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/10 group-hover:to-orange-500/10 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">08</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/50 group-hover:scale-110 group-hover:rotate-12 transition-all duration-700">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-amber-600 transition-colors mb-2">Putar PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Putar halaman PDF 90°, 180°, atau 270° dengan mudah.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-amber-600 group-hover:text-orange-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 9. Hapus Halaman --}}
            <a href="{{ route('remove-pages.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-red-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-red-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="hapus halaman delete remove" data-categories="manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/0 to-rose-500/0 group-hover:from-red-500/5 group-hover:to-rose-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-red-100 to-rose-100 text-red-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">09</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-red-600 transition-colors mb-2">Hapus Halaman</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Hapus halaman tertentu dari PDF dengan mudah.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-red-600 group-hover:text-rose-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 10. Ekstrak Halaman --}}
            <a href="{{ route('extract-pages.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-indigo-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="ekstrak halaman extract pages" data-categories="manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-blue-500/0 group-hover:from-indigo-500/5 group-hover:to-blue-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-indigo-100 to-blue-100 text-indigo-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">10</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-indigo-400 to-blue-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white shadow-lg shadow-indigo-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2">Ekstrak Halaman</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekstrak halaman tertentu menjadi PDF baru.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-indigo-600 group-hover:text-blue-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 11. Atur Halaman --}}
            <a href="{{ route('organize-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-purple-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-purple-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="atur halaman organize reorder" data-categories="manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-pink-500/0 group-hover:from-purple-500/5 group-hover:to-pink-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 text-purple-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">11</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg shadow-purple-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-purple-600 transition-colors mb-2">Atur Halaman</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Drag & drop untuk ubah urutan halaman PDF.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-purple-600 group-hover:text-pink-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 12. PNG ke PDF --}}
            <a href="{{ route('png-to-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-teal-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-teal-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="png gambar image convert" data-categories="konversi gambar">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-500/0 to-cyan-500/0 group-hover:from-teal-500/5 group-hover:to-cyan-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-teal-100 to-cyan-100 text-teal-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">12</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 text-white shadow-lg shadow-teal-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-teal-600 transition-colors mb-2">PNG ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ubah gambar PNG Anda jadi dokumen PDF berkualitas tinggi.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-teal-600 group-hover:text-cyan-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 13. Scan ke PDF --}}
            <a href="{{ route('scan-to-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-orange-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-orange-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="scan kamera camera pdf" data-categories="konversi gambar">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/0 to-amber-500/0 group-hover:from-orange-500/5 group-hover:to-amber-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 text-orange-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">13</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-orange-400 to-amber-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 text-white shadow-lg shadow-orange-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-orange-600 transition-colors mb-2">Scan ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Scan dokumen langsung dari kamera ke PDF.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-orange-600 group-hover:text-amber-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 14. HTML ke PDF --}}
            <a href="{{ route('html-to-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-sky-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-sky-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="html web page convert" data-categories="konversi gambar">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-blue-500/0 group-hover:from-sky-500/5 group-hover:to-blue-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-sky-100 to-blue-100 text-sky-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">14</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-sky-400 to-blue-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-sky-600 transition-colors mb-2">HTML ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi halaman web HTML ke dokumen PDF.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-sky-600 group-hover:text-blue-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 15. PowerPoint ke PDF --}}
            <a href="{{ route('pptx-to-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-orange-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-orange-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="powerpoint pptx presentasi slide" data-categories="konversi gambar">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/0 to-red-500/0 group-hover:from-orange-500/5 group-hover:to-red-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-orange-100 to-red-100 text-orange-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">15</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 text-white shadow-lg shadow-orange-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0l-2-2m2 2l2-2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-orange-600 transition-colors mb-2">PowerPoint ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi presentasi PowerPoint ke PDF.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-orange-600 group-hover:text-red-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 16. Word ke PDF --}}
            <a href="{{ route('word-to-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-blue-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-blue-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="word doc docx convert" data-categories="konversi">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-indigo-500/0 group-hover:from-blue-500/5 group-hover:to-indigo-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">16</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors mb-2">Word ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi dokumen Word (.docx) ke file PDF profesional.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-blue-600 group-hover:text-indigo-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 17. Excel ke PDF --}}
            <a href="{{ route('excel-to-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-green-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-green-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="excel spreadsheet csv tabel" data-categories="konversi">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/0 to-emerald-500/0 group-hover:from-green-500/5 group-hover:to-emerald-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 text-green-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">17</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-green-600 transition-colors mb-2">Excel ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi spreadsheet Excel (.xlsx) ke file PDF.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-green-600 group-hover:text-emerald-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 18. PDF ke JPG --}}
            <a href="{{ route('pdf-to-jpg.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-cyan-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-cyan-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="pdf ke jpg gambar extract" data-categories="konversi gambar">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-sky-500/0 group-hover:from-cyan-500/5 group-hover:to-sky-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-cyan-100 to-sky-100 text-cyan-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">18</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-teal-600 text-white shadow-lg shadow-cyan-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21zm16.5-13.5h.008v.008h-.008V7.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-cyan-600 transition-colors mb-2">PDF ke JPG</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Setiap halaman PDF jadi gambar JPG berkualitas tinggi.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-cyan-600 group-hover:text-sky-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 19. PDF ke TXT --}}
            <a href="{{ route('pdf-to-txt.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-slate-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-slate-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="pdf ke txt text extract" data-categories="konversi">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-500/0 to-slate-700/0 group-hover:from-slate-500/5 group-hover:to-slate-700/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">19</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-slate-500 to-slate-700 text-white shadow-lg shadow-slate-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-slate-600 transition-colors mb-2">PDF ke TXT</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekstrak teks dari PDF ke file teks biasa (.txt).</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-slate-600 group-hover:text-slate-800 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 20. PDF ke Markdown --}}
            <a href="{{ route('pdf-to-markdown.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-violet-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-violet-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="pdf markdown text extract" data-categories="konversi">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-500/0 to-fuchsia-500/0 group-hover:from-violet-500/5 group-hover:to-fuchsia-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-violet-100 to-fuchsia-100 text-violet-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">20</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-fuchsia-600 text-white shadow-lg shadow-violet-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-violet-600 transition-colors mb-2">PDF ke Markdown</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi PDF ke Markdown murni. Cocok untuk developer.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-violet-600 group-hover:text-fuchsia-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 21. PDF ke Excel --}}
            <a href="{{ route('pdf-to-excel.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-emerald-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-emerald-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="pdf excel spreadsheet tabel" data-categories="konversi">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-green-500/0 group-hover:from-emerald-500/5 group-hover:to-green-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-emerald-100 to-green-100 text-emerald-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">21</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-emerald-400 to-green-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 0v.375"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors mb-2">PDF ke Excel</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekstrak tabel dari PDF ke spreadsheet Excel.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-emerald-600 group-hover:text-green-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 22. PDF ke PowerPoint --}}
            <a href="{{ route('pdf-to-pptx.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-orange-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-orange-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="pdf powerpoint pptx presentasi" data-categories="konversi">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/0 to-red-500/0 group-hover:from-orange-500/5 group-hover:to-red-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-orange-100 to-red-100 text-orange-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">22</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 text-white shadow-lg shadow-orange-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12V6m0 0l-2 2m2-2l2 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-orange-600 transition-colors mb-2">PDF ke PowerPoint</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi PDF ke presentasi PowerPoint yang dapat diedit.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-orange-600 group-hover:text-red-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 23. PDF ke PDF/A --}}
            <a href="{{ route('pdf-to-pdfa.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-teal-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-teal-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="pdf pdfa archive arsip" data-categories="konversi">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-500/0 to-cyan-500/0 group-hover:from-teal-500/5 group-hover:to-cyan-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-teal-100 to-cyan-100 text-teal-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">23</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-teal-400 to-cyan-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 text-white shadow-lg shadow-teal-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-teal-600 transition-colors mb-2">PDF ke PDF/A</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi PDF ke format arsip jangka panjang (PDF/A).</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-teal-600 group-hover:text-cyan-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 24. Proteksi PDF --}}
            <a href="{{ route('protect-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-red-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-red-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="proteksi protect password keamanan" data-categories="keamanan">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/0 to-rose-500/0 group-hover:from-red-500/5 group-hover:to-rose-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-red-100 to-rose-100 text-red-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">24</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-red-600 transition-colors mb-2">Proteksi PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Lindungi PDF dengan password agar aman.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-red-600 group-hover:text-rose-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 25. Buka Kunci PDF --}}
            <a href="{{ route('unlock-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-green-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-green-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="buka kunci unlock password" data-categories="keamanan">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/0 to-emerald-500/0 group-hover:from-green-500/5 group-hover:to-emerald-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 text-green-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">25</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h16.5a1.5 1.5 0 001.5-1.5V12a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 12v8.25a1.5 1.5 0 001.5 1.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-green-600 transition-colors mb-2">Buka Kunci PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Hapus password dan batasan dari PDF terkunci.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-green-600 group-hover:text-emerald-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 26. Watermark PDF --}}
            <a href="{{ route('watermark-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-blue-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-blue-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="watermark tanda air cap" data-categories="keamanan manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-indigo-500/0 group-hover:from-blue-500/5 group-hover:to-indigo-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">26</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors mb-2">Watermark PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Tambahkan tanda air teks atau gambar ke PDF.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-blue-600 group-hover:text-indigo-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 27. Nomor Halaman --}}
            <a href="{{ route('page-numbers.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-amber-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-amber-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="nomor halaman page number" data-categories="keamanan manipulasi">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-yellow-500/0 group-hover:from-amber-500/5 group-hover:to-yellow-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-amber-100 to-yellow-100 text-amber-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">27</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-amber-400 to-yellow-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-yellow-600 text-white shadow-lg shadow-amber-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h2m-2 4h6m-4-2v4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-amber-600 transition-colors mb-2">Nomor Halaman</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Tambahkan nomor halaman ke setiap halaman PDF.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-amber-600 group-hover:text-yellow-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            {{-- 28. Perbaiki PDF --}}
            <a href="{{ route('repair-pdf.index') }}" class="feature-card group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-slate-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-slate-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden" data-feature-name="perbaiki repair fix rusak" data-categories="lanjutan">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-500/0 to-gray-500/0 group-hover:from-slate-500/5 group-hover:to-gray-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-slate-100 to-gray-100 text-slate-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">28</div>
                <div class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full bg-gradient-to-r from-slate-400 to-gray-500 text-white text-[10px] font-black shadow-md tracking-wider">BARU</div>
                <div class="relative space-y-4">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-slate-500 to-gray-700 text-white shadow-lg shadow-slate-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A1.5 1.5 0 0021 17.25l-5.83-5.83m0 0a2.75 2.75 0 10-3.88-3.88m3.88 3.88L12 14M10.12 7.88L3 15v3a3 3 0 003 3h3l7.12-7.12m-7.12 7.12l1.41-1.41"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-slate-600 transition-colors mb-2">Perbaiki PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Deteksi dan perbaiki file PDF yang rusak.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-slate-600 group-hover:text-gray-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Gunakan Alat</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

        </div>

        {{-- No Results State --}}
        <div id="no-results" class="hidden text-center py-20">
            <div class="w-20 h-20 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-5 border border-slate-200">
                <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1.5">Fitur tidak ditemukan</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto mb-5">Maaf, fitur yang kamu cari tidak ditemukan. Coba gunakan kata kunci lain atau pilih kategori berbeda.</p>
            <button id="reset-search" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">Reset pencarian</button>
        </div>
    </div>
</div>



<style>
/* Premium search card wrapper glow transition on focus */
.search-card-wrapper {
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.stackwrap:has(#fitur-search:focus) .search-card-wrapper,
.stackwrap.is-focused .search-card-wrapper {
    background-image: linear-gradient(to right, rgba(99, 102, 241, 0.7), rgba(168, 85, 247, 0.6), rgba(236, 72, 153, 0.7));
    box-shadow: 0 25px 60px -10px rgba(99, 102, 241, 0.25);
    transform: translateY(-2px);
}

/* Premium Refinements for Feature Cards */
#features-grid .feature-card {
    background-color: #ffffff !important;
    background-image: none !important; /* FIX: cegah residu gradient/utility class bg-* ikut nempel di card */
    -webkit-backdrop-filter: none !important;
    backdrop-filter: none !important;
    border-width: 1px !important;
    border-color: rgba(226, 232, 240, 0.8) !important;
    border-radius: 24px !important;
    box-shadow: 0 4px 20px -4px rgba(148, 163, 184, 0.15), 0 2px 8px -2px rgba(148, 163, 184, 0.1) !important;
    isolation: isolate; /* FIX: setiap card jadi own stacking/compositing context, cegah blending antar sibling */
    transform: translateZ(0); /* FIX: paksa GPU layer konsisten untuk semua card, bukan cuma sebagian */
    backface-visibility: hidden;
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), 
                border-color 0.4s ease, 
                box-shadow 0.4s ease, 
                background-color 0.4s ease !important;
}

#features-grid .feature-card:hover {
    transform: translateY(-6px) translateZ(0) !important;
    background-color: rgba(255, 255, 255, 0.98) !important;
    border-width: 1px !important;
    /* Hover border-color and shadow-color are handled dynamically by Tailwind's hover classes */
}

/* FIX: overlay gradient hover (anak pertama tiap card) harus selalu mulai transparan murni,
   tidak boleh ada residu warna dari class lain yang ke-apply tanpa sengaja */
#features-grid .feature-card > .absolute.inset-0:first-child {
    background-color: transparent !important;
}

/* FIX: definisikan state AWAL entrance animation.
   Sebelumnya hanya ada state akhir (.entered) tanpa state awal,
   sehingga transition opacity/transform di JS tidak pernah benar-benar terlihat. */
#features-grid .feature-card {
    opacity: 0;
    transform: translateY(16px) translateZ(0);
}
#features-grid .feature-card.entered {
    opacity: 1 !important;
    transform: translateY(0) translateZ(0) !important;
}

/* Card entrance animation final state (kept for specificity / backward compatibility) */
.feature-card.entered {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

/* Make sure inner icons transition beautifully, isolated from backdrop-filter blur */
#features-grid .feature-card .w-14 {
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    will-change: transform;
    isolation: isolate;
    overflow: hidden;
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.5s ease !important;
}
#features-grid .feature-card:hover .w-14 {
    transform: translateZ(0) scale(1.08) rotate(4deg) !important;
    box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.15) !important;
}
/* Ensure SVG icons render crisply inside icon containers */
#features-grid .feature-card .w-14 svg {
    display: block;
}
/* Prevent layout shift before JS relocates badges */
#features-grid .feature-card > .absolute.top-4.left-4.z-20 {
    display: none !important;
}

/* ── Filter Pills ─────────────────────────────────────────── */
.filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px !important;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    background-color: #ffffff !important;
    color: #475569 !important;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    z-index: 10;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
}
.filter-pill:hover {
    background-color: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #1e293b !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
}
.filter-pill.active {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4) !important;
    transform: translateY(-1px);
}
.filter-pill svg { transition: color 0.2s ease; color: #94a3b8; }
.filter-pill:hover svg { color: #475569; }
.filter-pill.active svg { color: #ffffff !important; }
.faq-icon.rotate-180 { transform: rotate(180deg); }

@media (prefers-reduced-motion: reduce) {
  .feature-card {
    transition: none !important;
    opacity: 1 !important;
    transform: none !important;
  }
}

/* ── Locked tool overlay ──────────────────────────────────── */
.feature-card.locked {
    pointer-events: none !important;
    cursor: not-allowed !important;
    /* Removed filter: grayscale(0.6) !important; */
    opacity: 1 !important; /* Keep original opacity */
    border-color: rgba(148, 163, 184, 0.5) !important;
    box-shadow: none !important;
    transform: none !important;
}
.feature-card.locked:hover {
    transform: none !important;
    box-shadow: none !important;
    border-color: rgba(148, 163, 184, 0.5) !important;
}
.feature-card.locked .locked-overlay {
    position: absolute;
    inset: 0;
    z-index: 30;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.6); /* Increased background opacity for better visibility */
    backdrop-filter: blur(2px);
    border-radius: inherit;
    opacity: 1; /* Always visible */
    transition: opacity 0.3s ease;
}
.feature-card.locked:hover .locked-overlay {
    opacity: 1; /* No change on hover, already visible */
}
.feature-card.locked .locked-overlay svg {
    width: 36px;
    height: 36px;
    color: rgba(100, 116, 139, 0.9); /* Stronger color for visibility */
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)); /* Stronger shadow */
}
</style>
@endsection

@push('scripts')
<script>
(function(){

    /* ── Element refs ─────────────────────────────────────────── */
    var searchInput   = document.getElementById('fitur-search');
    var clearBtn       = document.getElementById('search-clear');
    var pillRow         = document.getElementById('pill-row');
    var pills            = Array.prototype.slice.call(document.querySelectorAll('.filter-pill'));
    var indicator         = document.getElementById('pill-indicator');
    var visibleCountEl     = document.getElementById('visible-count');
    var noResults            = document.getElementById('no-results');
    var resetBtn              = document.getElementById('reset-search');
    var stackwrap               = document.querySelector('.stackwrap');
    var faqItems                  = Array.prototype.slice.call(document.querySelectorAll('.faq-item'));
    var featureCards                = Array.prototype.slice.call(document.querySelectorAll('.feature-card'));
    var activeFilter = 'all';

    if (!searchInput || !pillRow) {
        return;
    }

    // Reposition status badges (POPULER, BARU) to be below the icon container in the DOM flow
    featureCards.forEach(function(card) {
        var badge = card.querySelector('.absolute.top-4.left-4.z-20');
        if (badge) {
            var iconContainer = card.querySelector('.w-14.h-14');
            if (iconContainer) {
                badge.classList.remove('absolute', 'top-4', 'left-4', 'z-20');
                badge.classList.add('inline-block', 'w-max');
                iconContainer.parentNode.insertBefore(badge, iconContainer.nextSibling);
            }
        }
    });

    /* ── Pill sliding indicator ───────────────────────────────── */
    function moveIndicator(btn){
        if(!btn || !indicator) return;
        var rowRect = pillRow.getBoundingClientRect();
        var btnRect = btn.getBoundingClientRect();
        indicator.style.width = btnRect.width + 'px';
        indicator.style.left = (btnRect.left - rowRect.left) + 'px';
        indicator.style.top = (btnRect.top - rowRect.top) + 'px';
    }

    pills.forEach(function(btn){
        btn.addEventListener('click', function(){
            pills.forEach(function(p){
                p.classList.remove('active');
                p.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');
            activeFilter = btn.dataset.filter;
            moveIndicator(btn);
            applyFilters();
        });
    });

    /* ── Skeleton loader refs ─────────────────────────────────── */
    var skeletonGrid = document.getElementById('skeleton-grid');
    var featuresGrid = document.getElementById('features-grid');
    var filterTimeout = null;
    var isInitialLoad = true;

    /* ── Search + filter with loading skeleton ─────────────────── */
    function applyFilters(showSkeleton){
        var q = (searchInput.value || '').trim().toLowerCase();

        // Tampilkan skeleton cuma pas user interaksi (bukan pas initial load)
        if (showSkeleton !== false && skeletonGrid && !isInitialLoad) {
            skeletonGrid.classList.remove('hidden');
            featureCards.forEach(function(card){ card.style.display = 'none'; });
        }

        // Clear timeout sebelumnya biar ga double
        if (filterTimeout) clearTimeout(filterTimeout);

        filterTimeout = setTimeout(function(){
            var visible = 0;
            featureCards.forEach(function(card){
                var name = (card.dataset.featureName || '').toLowerCase();
                var cats = (card.dataset.categories || '').split(' ').map(function(cat){ return cat.trim(); }).filter(function(cat){ return cat !== ''; });
                var matchesFilter = activeFilter === 'all' || cats.indexOf(activeFilter) !== -1;
                var matchesSearch = !q || name.indexOf(q) !== -1;
                var show = matchesFilter && matchesSearch;
                card.style.display = show ? '' : 'none';
                if(show) {
                    visible++;
                    if (observer) {
                        observer.observe(card);
                    }
                }
            });
            if (visibleCountEl) visibleCountEl.textContent = visible;
            if (noResults) noResults.classList.toggle('hidden', visible !== 0);

            // Sembunyikan skeleton setelah real cards siap
            if (skeletonGrid) skeletonGrid.classList.add('hidden');
            isInitialLoad = false;
        }, 200);
    }

    searchInput.addEventListener('input', function(){
        if (clearBtn) clearBtn.classList.toggle('hidden', searchInput.value.length === 0);
        applyFilters();
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function(){
            searchInput.value = '';
            clearBtn.classList.add('hidden');
            searchInput.focus();
            applyFilters();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function(){
            searchInput.value = '';
            if (clearBtn) clearBtn.classList.add('hidden');
            pills[0].click();
        });
    }

    if (stackwrap) {
        searchInput.addEventListener('focus', function(){ stackwrap.classList.add('is-focused'); });
        searchInput.addEventListener('blur', function(){ stackwrap.classList.remove('is-focused'); });
    }

    /* ── Count-up animation for hero stats ────────────────────── */
    function countUp(el){
        var target = parseFloat(el.dataset.target || el.dataset.countTo || el.textContent) || 0;
        var suffixEl = el.querySelector('span');
        var suffix = suffixEl ? suffixEl.outerHTML : '';
        var duration = 900;
        var startTime = null;
        function step(ts){
            if(!startTime) startTime = ts;
            var progress = Math.min((ts - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.innerHTML = Math.round(target * eased) + suffix;
            if(progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
    document.querySelectorAll('[data-count-to], #tool-count').forEach(countUp);

    /* ── FAQ accordion ─────────────────────────────────────────── */
    faqItems.forEach(function(item){
        item.addEventListener('click', function(){
            var answer = item.querySelector('.faq-answer');
            var icon = item.querySelector('.faq-icon');
            var isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

            faqItems.forEach(function(i){
                var ans = i.querySelector('.faq-answer');
                var ic = i.querySelector('.faq-icon');
                ans.style.maxHeight = null;
                ic.classList.remove('rotate-180');
            });

            if(!isOpen){
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.classList.add('rotate-180');
            }
        });
    });

    /* ── Entrance animation on scroll (Staggered fade-in & slide-up) ── */
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if (entry.isIntersecting) {
                    entry.target.classList.add('entered');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.05, rootMargin: '0px 0px -20px 0px' });

        featureCards.forEach(function(card, index){
            card.style.transition = 'opacity 0.4s ease-out ' + (index * 0.015) + 's, transform 0.4s ease-out ' + (index * 0.015) + 's';
            observer.observe(card);
        });
    } else {
        // FIX: fallback browser tanpa IntersectionObserver — paksa semua card tampil
        featureCards.forEach(function(card){ card.classList.add('entered'); });
    }

    /* ── Init ──────────────────────────────────────────────────── */
    window.addEventListener('resize', function(){
        var current = document.querySelector('.filter-pill.active');
        if(current) moveIndicator(current);
    });

    window.addEventListener('load', function(){
        moveIndicator(pills[0]);
        applyFilters();

        /* ── Lock overlay injection ────────────────────────── */
        var pathToRoute = @json($toolRouteMapping);
        var lockedByRoute = @json($toolLocksByRoute);

        featureCards.forEach(function(card) {
            var href = card.getAttribute('href');
            if (!href) return;
            try {
                var url = new URL(href, window.location.origin);
                var path = url.pathname.replace(/\/$/, ''); // remove trailing slash
                var route = pathToRoute[path];
                if (route && lockedByRoute[route]) {
                    card.classList.add('locked');
                    // Inject lock icon directly into the card's inner HTML (visible by default)
                    card.insertAdjacentHTML('beforeend', `
                        <div class="locked-overlay">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    `);
                    // Prevent click navigation and show alert
                    card.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        alert('Sedang dalam perbaikan'); // Display "Sedang dalam perbaikan" alert
                    });
                }
            } catch(e) { /* console.error("Error processing feature card:", e); */ }
        });
    });

})();
</script>
@endpush