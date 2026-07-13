@extends('layouts.app')

@section('title', 'Tentang Kami — VizzioDocs')

@section('content')
<style>
    /* ════════════════════════════════════════════════
       ╔═█  Halaman Tentang — Mobile Responsive
       ╚═══════════════════════════════════════════════ */

    /* ── Fix background orbs causing overflow on mobile ── */
    @media (max-width: 768px) {
        .about-container > div:first-child > .absolute {
            /* Prevent background orbs from overflowing */
            max-width: 100vw;
            overflow: hidden;
        }
    }

    /* ── Hero Section ── */
    @media (max-width: 640px) {
        .about-hero {
            padding-top: 6rem !important; /* reduced from pt-32 (8rem) */
            padding-bottom: 3rem !important;
        }
        .about-hero h1 {
            font-size: 2.5rem !important; /* 40px instead of 3rem */
            line-height: 1 !important;
        }
        .about-hero h1 br {
            display: none; /* Remove <br> on mobile, let text flow naturally */
        }
        .about-hero .hero-badge {
            padding: 0.5rem 1rem !important;
            margin-bottom: 1.5rem !important;
        }
        .about-hero .hero-desc {
            font-size: 1rem !important; /* 16px */
            margin-bottom: 2rem !important;
            max-width: 100% !important;
        }
        .about-hero .hero-buttons a {
            padding: 0.85rem 1.5rem !important;
            font-size: 0.85rem !important;
        }
        .about-hero .hero-buttons a svg {
            width: 1rem !important;
            height: 1rem !important;
        }
    }
    @media (max-width: 400px) {
        .about-hero h1 {
            font-size: 2rem !important;
        }
        .about-hero {
            padding-top: 5rem !important;
        }
        .about-hero .hero-buttons {
            flex-direction: column !important;
            gap: 0.75rem !important;
        }
        .about-hero .hero-buttons a {
            width: 100% !important;
            justify-content: center !important;
        }
    }

    /* ── Statistics Section ── */
    @media (max-width: 640px) {
        .about-stats-card {
            padding: 1.5rem !important; /* reduced from p-8 */
            border-radius: 1.5rem !important; /* 24px instead of 40px */
        }
        .about-stats-card .stat-number {
            font-size: 2.25rem !important; /* text-4xl equivalent */
        }
        .about-stats-card .stat-suffix {
            font-size: 1.5rem !important;
        }
        .about-stats-card .stat-label {
            font-size: 0.8rem !important;
        }
        .about-stats-grid {
            gap: 1rem !important;
        }
    }
    @media (max-width: 400px) {
        .about-stats-card {
            padding: 1rem !important;
            border-radius: 1.25rem !important;
        }
        .about-stats-card .stat-number {
            font-size: 1.75rem !important;
        }
        .about-stats-card .stat-suffix {
            font-size: 1.25rem !important;
        }
        .about-stats-card .stat-label {
            font-size: 0.7rem !important;
        }
        .about-stats-grid {
            gap: 0.75rem !important;
        }
    }

    /* ── Mission Section ── */
    @media (max-width: 640px) {
        .about-mission {
            padding: 2rem 1.5rem !important; /* reduced from p-10 */
            border-radius: 1.5rem !important; /* 24px instead of 48px */
        }
        .about-mission h2 {
            font-size: 1.75rem !important; /* reduced from 3xl */
        }
        .about-mission p {
            font-size: 0.9rem !important;
        }
        .about-mission .mission-icon {
            width: 3rem !important;
            height: 3rem !important;
            margin-bottom: 1.5rem !important;
        }
        .about-mission .mission-icon svg {
            width: 1.5rem !important;
            height: 1.5rem !important;
        }
    }
    @media (max-width: 400px) {
        .about-mission {
            padding: 1.5rem 1rem !important;
            border-radius: 1.25rem !important;
        }
        .about-mission h2 {
            font-size: 1.4rem !important;
        }
    }

    /* ── Core Values Cards ── */
    @media (max-width: 640px) {
        .values-grid {
            gap: 1rem !important; /* reduced from gap-6 */
        }
        .value-card {
            padding: 1.5rem !important; /* reduced from p-8 */
            border-radius: 1.5rem !important; /* 24px instead of 32px */
        }
        .value-card .value-icon {
            width: 3rem !important;
            height: 3rem !important;
            margin-bottom: 1.25rem !important;
        }
        .value-card .value-icon svg {
            width: 1.4rem !important;
            height: 1.4rem !important;
        }
        .value-card h3 {
            font-size: 1.25rem !important;
            margin-bottom: 0.5rem !important;
        }
        .value-card p {
            font-size: 0.85rem !important;
        }
    }
    @media (max-width: 400px) {
        .value-card {
            padding: 1.25rem !important;
            border-radius: 1.25rem !important;
        }
        .value-card .value-icon {
            width: 2.5rem !important;
            height: 2.5rem !important;
        }
        .value-card .value-icon svg {
            width: 1.2rem !important;
            height: 1.2rem !important;
        }
        .value-card h3 {
            font-size: 1.1rem !important;
        }
    }

    /* ── Timeline Section ── */
    @media (max-width: 640px) {
        .timeline-item .timeline-card {
            padding: 1rem 1.25rem !important;
        }
        .timeline-item .timeline-card h3 {
            font-size: 1.05rem !important;
        }
        .timeline-item .timeline-card p {
            font-size: 0.8rem !important;
        }
        .timeline-item .timeline-badge {
            font-size: 0.65rem !important;
            padding: 0.25rem 0.6rem !important;
            margin-bottom: 0.5rem !important;
        }
        .timeline-space {
            gap: 2.5rem !important; /* reduced from space-y-12 */
        }
    }
    @media (max-width: 400px) {
        .timeline-item {
            padding-left: 3rem !important; /* reduced from pl-14 */
        }
        .timeline-item .timeline-card {
            padding: 0.75rem 1rem !important;
        }
        .timeline-item .timeline-card h3 {
            font-size: 0.95rem !important;
        }
        .timeline-space {
            gap: 2rem !important;
        }
        /* Adjust timeline line and dot positions */
        .about-timeline .timeline-line {
            left: 0.75rem !important;
        }
        .about-timeline .timeline-dot {
            left: 0.5rem !important;
            width: 1rem !important;
            height: 1rem !important;
            border-width: 3px !important;
        }
    }

    /* ── Team Section ── */
    @media (max-width: 640px) {
        .team-grid {
            gap: 1rem !important;
        }
        .team-card {
            padding: 1.25rem !important;
        }
        .team-card .team-avatar {
            width: 4rem !important;
            height: 4rem !important;
            margin-bottom: 1rem !important;
        }
        .team-card .team-avatar svg {
            width: 1.75rem !important;
            height: 1.75rem !important;
        }
        .team-card h3 {
            font-size: 1rem !important;
        }
        .team-card .team-role {
            font-size: 0.75rem !important;
        }
        .team-card .team-desc {
            font-size: 0.7rem !important;
        }
    }
    @media (max-width: 400px) {
        .team-grid {
            grid-template-columns: 1fr !important;
        }
        .team-card {
            padding: 1rem !important;
        }
    }

    /* ── CTA Section ── */
    @media (max-width: 640px) {
        .about-cta {
            padding: 2.5rem 1.5rem !important; /* reduced */
            border-radius: 1.5rem !important; /* 24px instead of 48px */
        }
        .about-cta h2 {
            font-size: 1.75rem !important; /* reduced from 3xl */
        }
        .about-cta p {
            font-size: 0.9rem !important;
            margin-bottom: 1.5rem !important;
        }
        .about-cta .cta-icon {
            width: 3rem !important;
            height: 3rem !important;
            margin-bottom: 1.5rem !important;
        }
        .about-cta .cta-icon svg {
            width: 1.5rem !important;
            height: 1.5rem !important;
        }
        .about-cta .cta-buttons a {
            padding: 0.85rem 1.5rem !important;
            font-size: 0.85rem !important;
        }
        .about-cta .cta-buttons a svg {
            width: 1rem !important;
            height: 1rem !important;
        }
    }
    @media (max-width: 400px) {
        .about-cta {
            padding: 2rem 1rem !important;
            border-radius: 1.25rem !important;
        }
        .about-cta h2 {
            font-size: 1.4rem !important;
        }
        .about-cta .cta-buttons {
            flex-direction: column !important;
            gap: 0.75rem !important;
        }
        .about-cta .cta-buttons a {
            width: 100% !important;
            justify-content: center !important;
        }
    }

    /* ── Section spacing on mobile ── */
    @media (max-width: 640px) {
        .about-section-gap {
            margin-bottom: 4rem !important; /* reduced from mb-24/mb-32 */
        }
    }
    @media (max-width: 400px) {
        .about-section-gap {
            margin-bottom: 3rem !important;
        }
    }

    /* ── Section headers on mobile ── */
    @media (max-width: 640px) {
        .section-header h2 {
            font-size: 1.75rem !important; /* reduced */
        }
        .section-header .section-badge {
            font-size: 0.65rem !important;
            padding: 0.35rem 0.85rem !important;
            margin-bottom: 0.75rem !important;
        }
        .section-header .section-badge svg {
            width: 0.8rem !important;
            height: 0.8rem !important;
        }
        .section-header .section-desc {
            font-size: 0.9rem !important;
        }
        .section-header {
            margin-bottom: 2.5rem !important; /* reduced from mb-14 */
        }
    }
    @media (max-width: 400px) {
        .section-header h2 {
            font-size: 1.5rem !important;
        }
        .section-header {
            margin-bottom: 2rem !important;
        }
    }

    /* ── Section container padding on mobile ── */
    @media (max-width: 640px) {
        .about-container section {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }
</style>
{{-- ═══════════════════════════════════════════════════════════════════
     TENTANG KAMI — Premium About Page
     ═══════════════════════════════════════════════════════════════════ --}}
<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/30 font-sans about-container"
     x-data="aboutPage()"
     x-init="init">

    {{-- ── Animated Background Orbs ── --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#8882_1px,transparent_1px),linear-gradient(to_bottom,#8882_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-[40rem] h-[40rem] bg-gradient-to-br from-indigo-400/30 via-purple-500/20 to-transparent rounded-full mix-blend-multiply blur-3xl animate-blob pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-[40rem] h-[40rem] bg-gradient-to-br from-purple-400/30 via-pink-500/20 to-transparent rounded-full mix-blend-multiply blur-3xl animate-blob animation-delay-2000 pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[60rem] h-[30rem] bg-gradient-to-br from-pink-400/20 via-indigo-500/20 to-transparent rounded-full mix-blend-multiply blur-3xl animate-blob animation-delay-4000 pointer-events-none"></div>

    {{-- Floating Elements --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="float-el absolute w-2 h-2 rounded-full bg-indigo-400/40" style="top:12%;left:8%;animation-delay:0s"></div>
        <div class="float-el absolute w-3 h-3 rounded-full bg-purple-400/30" style="top:25%;left:85%;animation-delay:0.4s"></div>
        <div class="float-el absolute w-2.5 h-2.5 rounded-full bg-pink-400/35" style="top:60%;left:5%;animation-delay:0.8s"></div>
        <div class="float-el absolute w-4 h-4 rounded-full bg-indigo-300/25" style="top:80%;left:75%;animation-delay:1.2s"></div>
        <div class="float-el absolute w-1.5 h-1.5 rounded-full bg-purple-400/40" style="top:40%;left:92%;animation-delay:1.6s"></div>
    </div>

    <div class="relative z-10">
        {{-- ══════════════════════════════════════════════════════════
             HERO SECTION
             ══════════════════════════════════════════════════════════ --}}
        <section class="relative pt-32 pb-20 md:pt-40 md:pb-28 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center about-hero">
            <div x-show="ready"
                 x-transition:enter="transition-all ease-out duration-[1200ms]"
                 x-transition:enter-start="opacity-0 translate-y-16"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- Premium Badge --}}
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-white/80 backdrop-blur-md border border-white/60 shadow-lg shadow-indigo-500/10 mb-10 hover:shadow-xl hover:shadow-indigo-500/15 transition-all duration-300 hero-badge">
                    <span class="relative flex w-2.5 h-2.5">
                        <span class="absolute inset-0 rounded-full bg-indigo-500 animate-ping opacity-75"></span>
                        <span class="relative rounded-full w-2.5 h-2.5 bg-indigo-500"></span>
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-slate-700 tracking-wide uppercase">Tentang VizzioDocs</span>
                </div>

                {{-- Main Heading --}}
                <h1 class="text-5xl sm:text-6xl md:text-8xl font-black tracking-tighter leading-[0.9] mb-8 text-slate-900">
                    Misi Kami Adalah<br/>
                    <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Menyederhanakan PDF.</span>
                </h1>

                <p class="text-lg sm:text-xl md:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed font-medium mb-12 hero-desc">
                    Kami membangun alat yang tidak hanya kuat secara teknis, tapi juga indah secara visual dan menyenangkan untuk digunakan setiap hari.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-5 hero-buttons">
                    <a href="{{ route('fitur') }}" class="group relative inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-size-200 bg-pos-0 hover:bg-pos-100 text-white font-bold text-sm sm:text-base transition-all duration-500 hover:-translate-y-1 shadow-xl shadow-indigo-300/40 hover:shadow-2xl hover:shadow-indigo-400/50 active:translate-y-0 overflow-hidden">
                        <span class="relative z-10">Jelajahi Semua Fitur</span>
                        <svg class="relative z-10 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </a>
                    <a href="mailto:hello@vizziodocs.com" class="group inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl bg-white/80 backdrop-blur-sm border border-slate-200 hover:border-indigo-300 text-slate-700 hover:text-indigo-600 font-bold text-sm sm:text-base transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-200/30 active:translate-y-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Hubungi Kami</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
             STATISTICS COUNTER BAR
             ══════════════════════════════════════════════════════════ --}}
        <section class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-24 md:mb-32 about-section-gap">
            <div class="relative bg-white/50 backdrop-blur-xl border border-white/60 rounded-[40px] p-8 md:p-12 shadow-2xl shadow-indigo-500/5 about-stats-card">
                {{-- Internal Orbs --}}
                <div class="absolute -top-16 -right-16 w-40 h-40 rounded-full bg-indigo-400/15 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-40 h-40 rounded-full bg-pink-400/15 blur-3xl pointer-events-none"></div>

                <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 about-stats-grid">
                    {{-- Stat 1: Years --}}
                    <div class="text-center" data-counter="years">
                        <div class="text-4xl sm:text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 tabular-nums leading-none mb-2 stat-number">
                            <span x-text="counts.years">0</span><span class="text-3xl md:text-4xl stat-suffix">+</span>
                        </div>
                        <p class="text-sm md:text-base text-slate-500 font-semibold stat-label">Tahun Berdiri</p>
                    </div>

                    {{-- Stat 2: Users --}}
                    <div class="text-center" data-counter="users">
                        <div class="text-4xl sm:text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 tabular-nums leading-none mb-2 stat-number">
                            <span x-text="formatNumber(counts.users)">0</span><span class="text-3xl md:text-4xl stat-suffix">+</span>
                        </div>
                        <p class="text-sm md:text-base text-slate-500 font-semibold stat-label">Pengguna Aktif</p>
                    </div>

                    {{-- Stat 3: Tools --}}
                    <div class="text-center" data-counter="tools">
                        <div class="text-4xl sm:text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600 tabular-nums leading-none mb-2 stat-number">
                            <span x-text="counts.tools">0</span>
                        </div>
                        <p class="text-sm md:text-base text-slate-500 font-semibold stat-label">Alat Tersedia</p>
                    </div>

                    {{-- Stat 4: Countries --}}
                    <div class="text-center" data-counter="countries">
                        <div class="text-4xl sm:text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-indigo-600 tabular-nums leading-none mb-2 stat-number">
                            <span x-text="counts.countries">0</span><span class="text-3xl md:text-4xl stat-suffix">+</span>
                        </div>
                        <p class="text-sm md:text-base text-slate-500 font-semibold stat-label">Negara Pengguna</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
             MISSION SECTION
             ══════════════════════════════════════════════════════════ --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-24 md:mb-32 about-section-gap">
            <div class="relative rounded-[48px] overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 p-10 md:p-16 lg:p-20 text-center shadow-2xl shadow-indigo-500/25 about-mission">
                {{-- Decorative grid overlay --}}
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:3rem_3rem] pointer-events-none"></div>

                {{-- Glowing orbs --}}
                <div class="absolute -top-20 -left-20 w-60 h-60 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -right-20 w-60 h-60 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto mb-8 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border border-white/20 mission-icon">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>

                    <h2 class="text-3xl sm:text-4xl md:text-6xl font-black text-white tracking-tighter leading-tight mb-6">
                        Dokumen Hebat untuk Semua.
                    </h2>
                    <p class="text-base sm:text-lg md:text-xl text-indigo-100 max-w-3xl mx-auto leading-relaxed font-medium">
                        VizzioDocs lahir dari visi untuk mendemokratisasi akses ke alat dokumen kelas dunia. 
                        Kami percaya bahwa perangkat lunak yang hebat tidak harus mahal atau membosankan — 
                        dan setiap orang berhak mendapatkan alat yang <em>indah</em> dan <em>bertenaga</em>.
                    </p>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
             CORE VALUES — 3 Premium Cards
             ══════════════════════════════════════════════════════════ --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-24 md:mb-32">
            <div class="text-center mb-14">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-slate-200 shadow-sm text-xs font-bold text-slate-600 mb-4">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span>Apa Yang Membuat Kami Berbeda</span>
                </span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-tight">
                    Nilai-nilai <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Inti</span> Kami
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 values-grid">
                {{-- Card 1: Speed --}}
                <div class="group relative p-8 rounded-[32px] bg-white/60 backdrop-blur-xl border border-white/80 hover:bg-white/90 transition-all duration-500 shadow-xl shadow-indigo-500/5 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-2 overflow-hidden value-card">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-purple-500/0 group-hover:from-indigo-500/5 group-hover:to-purple-500/5 transition-all duration-500 rounded-[32px] pointer-events-none"></div>
                    <div class="absolute bottom-0 left-8 right-8 h-1 bg-gradient-to-r from-transparent via-indigo-400 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-full"></div>

                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center mb-8 border border-indigo-200 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 value-icon">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-slate-800 tracking-tight">Kecepatan Cahaya</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">
                            Pemrosesan instan menggunakan infrastruktur server edge terbaru di seluruh dunia. Tidak ada antrean, tidak ada waktu tunggu.
                        </p>
                    </div>
                </div>

                {{-- Card 2: Privacy --}}
                <div class="group relative p-8 rounded-[32px] bg-white/60 backdrop-blur-xl border border-white/80 hover:bg-white/90 transition-all duration-500 shadow-xl shadow-indigo-500/5 hover:shadow-2xl hover:shadow-purple-500/10 hover:-translate-y-2 overflow-hidden value-card">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-pink-500/0 group-hover:from-purple-500/5 group-hover:to-pink-500/5 transition-all duration-500 rounded-[32px] pointer-events-none"></div>
                    <div class="absolute bottom-0 left-8 right-8 h-1 bg-gradient-to-r from-transparent via-purple-400 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-full"></div>

                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-purple-100 flex items-center justify-center mb-8 border border-purple-200 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 value-icon">
                            <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-slate-800 tracking-tight">Privasi Mutlak</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">
                            Berkas Anda dienkripsi end-to-end dan dihapus secara otomatis dalam waktu 1 jam. Kami tidak pernah melihat data Anda.
                        </p>
                    </div>
                </div>

                {{-- Card 3: Design --}}
                <div class="group relative p-8 rounded-[32px] bg-white/60 backdrop-blur-xl border border-white/80 hover:bg-white/90 transition-all duration-500 shadow-xl shadow-indigo-500/5 hover:shadow-2xl hover:shadow-pink-500/10 hover:-translate-y-2 overflow-hidden value-card">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-500/0 to-rose-500/0 group-hover:from-pink-500/5 group-hover:to-rose-500/5 transition-all duration-500 rounded-[32px] pointer-events-none"></div>
                    <div class="absolute bottom-0 left-8 right-8 h-1 bg-gradient-to-r from-transparent via-pink-400 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-full"></div>

                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-pink-100 flex items-center justify-center mb-8 border border-pink-200 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 value-icon">
                            <svg class="w-8 h-8 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-slate-800 tracking-tight">Desain Premium</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">
                            Antarmuka modern yang dirancang khusus untuk meningkatkan produktivitas dan memberikan pengalaman yang menyenangkan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
             JOURNEY TIMELINE
             ══════════════════════════════════════════════════════════ --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-24 md:mb-32 about-section-gap about-timeline">
            <div class="text-center mb-14">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-slate-200 shadow-sm text-xs font-bold text-slate-600 mb-4">
                    <svg class="w-3.5 h-3.5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Perjalanan Kami</span>
                </span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-tight">
                    Dari Ide Hingga <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Kenyataan</span>
                </h2>
            </div>

            <div class="relative">
                {{-- Timeline Center Line --}}
                <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-400 via-purple-400 to-pink-400 md:-translate-x-px rounded-full"></div>

                {{-- Timeline Items --}}
                <div class="space-y-12 md:space-y-20 timeline-space">
                    {{-- Item 1 (Left) --}}
                    <div class="relative pl-14 md:pl-0 md:flex md:items-start md:justify-between group timeline-item">
                        <div class="md:w-[calc(50%-2rem)] md:text-right">
                            <div class="bg-white/60 backdrop-blur-sm border border-white/80 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 timeline-card">
                                <span class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold mb-3 timeline-badge">2024 — Q1</span>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Ide & Perencanaan</h3>
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    VizzioDocs dimulai sebagai ide sederhana: membuat alat PDF yang powerful tapi tetap sederhana dan indah.
                                </p>
                            </div>
                        </div>
                        <div class="absolute left-2.5 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-white border-4 border-indigo-500 shadow-md shadow-indigo-200 group-hover:scale-125 transition-transform duration-300 z-10"></div>
                        <div class="hidden md:block md:w-[calc(50%-2rem)]"></div>
                    </div>

                    {{-- Item 2 (Right) --}}
                    <div class="relative pl-14 md:pl-0 md:flex md:items-start md:justify-between group timeline-item">
                        <div class="hidden md:block md:w-[calc(50%-2rem)]"></div>
                        <div class="absolute left-2.5 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-white border-4 border-purple-500 shadow-md shadow-purple-200 group-hover:scale-125 transition-transform duration-300 z-10"></div>
                        <div class="md:w-[calc(50%-2rem)]">
                            <div class="bg-white/60 backdrop-blur-sm border border-white/80 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 timeline-card">
                                <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold mb-3 timeline-badge">2024 — Q2</span>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Pengembangan Awal</h3>
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    Tim kecil kami mulai membangun fondasi teknis, fokus pada kecepatan pemrosesan dan keamanan data.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Item 3 (Left) --}}
                    <div class="relative pl-14 md:pl-0 md:flex md:items-start md:justify-between group timeline-item">
                        <div class="md:w-[calc(50%-2rem)] md:text-right">
                            <div class="bg-white/60 backdrop-blur-sm border border-white/80 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 timeline-card">
                                <span class="inline-block px-3 py-1 rounded-full bg-pink-100 text-pink-700 text-xs font-bold mb-3 timeline-badge">2025 — Q1</span>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Peluncuran Publik</h3>
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    VizzioDocs resmi diluncurkan dengan 15 alat PDF premium. Respons pengguna di luar ekspektasi!
                                </p>
                            </div>
                        </div>
                        <div class="absolute left-2.5 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-white border-4 border-pink-500 shadow-md shadow-pink-200 group-hover:scale-125 transition-transform duration-300 z-10"></div>
                        <div class="hidden md:block md:w-[calc(50%-2rem)]"></div>
                    </div>

                    {{-- Item 4 (Right) --}}
                    <div class="relative pl-14 md:pl-0 md:flex md:items-start md:justify-between group timeline-item">
                        <div class="hidden md:block md:w-[calc(50%-2rem)]"></div>
                        <div class="absolute left-2.5 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-white border-4 border-rose-500 shadow-md shadow-rose-200 group-hover:scale-125 transition-transform duration-300 z-10"></div>
                        <div class="md:w-[calc(50%-2rem)]">
                            <div class="bg-white/60 backdrop-blur-sm border border-white/80 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 timeline-card">
                                <span class="inline-block px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-xs font-bold mb-3 timeline-badge">2026 — Sekarang</span>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Terus Bertumbuh</h3>
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    Dengan lebih dari 28 alat dan <span class="font-semibold text-slate-700">{{ number_format($userCount) }}+ pengguna</span> di <span class="font-semibold text-slate-700">{{ $countryCount }}+ negara</span>, kami terus berinovasi untuk memberikan yang terbaik.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
             TEAM / CULTURE SECTION
             ══════════════════════════════════════════════════════════ --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-24 md:mb-32 about-section-gap">
            <div class="text-center mb-14">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-slate-200 shadow-sm text-xs font-bold text-slate-600 mb-4">
                    <svg class="w-3.5 h-3.5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Di Balik Layar</span>
                </span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-tight">
                    Kenali <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Tim</span> Kami
                </h2>
                <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto mt-4 font-medium leading-relaxed">
                    Kami adalah sekelompok kreatif, insinyur, dan pemimpi yang bersatu untuk mengubah cara dunia bekerja dengan dokumen.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 team-grid">
                {{-- Team Card 1: Founder & CEO --}}
                <div class="group relative bg-white/50 backdrop-blur-sm border border-white/80 rounded-3xl p-6 text-center hover:bg-white/80 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/10 team-card">
                    <div class="w-24 h-24 mx-auto mb-5 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 p-0.5 group-hover:scale-105 transition-transform duration-500 team-avatar">
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                            <span class="text-2xl font-black text-indigo-600">AR</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Ahmad Rizki</h3>
                    <p class="text-sm text-indigo-600 font-semibold mb-3 team-role">Founder & CEO</p>
                    <p class="text-xs text-slate-500 leading-relaxed team-desc">Visioner di balik VizzioDocs, berpengalaman 10+ tahun di industri teknologi dokumen.</p>
                </div>

                {{-- Team Card 2: CTO --}}
                <div class="group relative bg-white/50 backdrop-blur-sm border border-white/80 rounded-3xl p-6 text-center hover:bg-white/80 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/10 team-card">
                    <div class="w-24 h-24 mx-auto mb-5 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 p-0.5 group-hover:scale-105 transition-transform duration-500 team-avatar">
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                            <span class="text-2xl font-black text-purple-600">SN</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Siti Nurhaliza</h3>
                    <p class="text-sm text-purple-600 font-semibold mb-3 team-role">Chief Technology Officer</p>
                    <p class="text-xs text-slate-500 leading-relaxed team-desc">Pakar arsitektur sistem dan keamanan data. Mantan engineer di Google dan Gojek.</p>
                </div>

                {{-- Team Card 3: Head of Design --}}
                <div class="group relative bg-white/50 backdrop-blur-sm border border-white/80 rounded-3xl p-6 text-center hover:bg-white/80 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-pink-500/10 team-card">
                    <div class="w-24 h-24 mx-auto mb-5 rounded-full bg-gradient-to-br from-pink-400 to-rose-600 p-0.5 group-hover:scale-105 transition-transform duration-500 team-avatar">
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center">
                            <span class="text-2xl font-black text-pink-600">DP</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Dimas Pratama</h3>
                    <p class="text-sm text-pink-600 font-semibold mb-3 team-role">Head of Design</p>
                    <p class="text-xs text-slate-500 leading-relaxed team-desc">Menciptakan pengalaman visual premium dengan sentuhan modern dan intuitif.</p>
                </div>

                {{-- Team Card 4: Head of Product --}}
                <div class="group relative bg-white/50 backdrop-blur-sm border border-white/80 rounded-3xl p-6 text-center hover:bg-white/80 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-orange-500/10 team-card">
                    <div class="w-24 h-24 mx-auto mb-5 rounded-full bg-gradient-to-br from-orange-400 to-red-600 p-0.5 group-hover:scale-105 transition-transform duration-500 team-avatar">
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                            <span class="text-2xl font-black text-orange-600">RW</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Rina Wijaya</h3>
                    <p class="text-sm text-orange-600 font-semibold mb-3 team-role">Head of Product</p>
                    <p class="text-xs text-slate-500 leading-relaxed team-desc">Memastikan setiap fitur yang dirilis memberikan dampak nyata bagi pengguna.</p>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
             CTA SECTION
             ══════════════════════════════════════════════════════════ --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-32 md:pb-40">
            <div class="relative rounded-[48px] overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 p-10 md:p-16 lg:p-20 text-center shadow-2xl shadow-indigo-900/30">
                {{-- Decorative orbs --}}
                <div class="absolute -top-32 -right-32 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 rounded-full bg-purple-500/10 blur-3xl pointer-events-none"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto mb-8 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center border border-white/10">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>

                    <h2 class="text-3xl sm:text-4xl md:text-6xl font-black text-white tracking-tighter leading-tight mb-6">
                        Siap Untuk <span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">Memulai</span>?
                    </h2>
                    <p class="text-base sm:text-lg text-indigo-200/80 max-w-2xl mx-auto mb-10 font-medium leading-relaxed">
                        Bergabunglah dengan <span class="font-semibold text-indigo-200">{{ number_format($userCount) }}+ pengguna</span> yang sudah merasakan kemudahan mengelola dokumen bersama VizzioDocs.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-5">
                        <a href="{{ route('fitur') }}" class="group relative inline-flex items-center gap-2.5 px-10 py-5 rounded-2xl bg-white text-slate-900 font-bold text-sm sm:text-base transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-white/20 active:translate-y-0 overflow-hidden">
                            <span class="relative z-10">Mulai Sekarang</span>
                            <svg class="relative z-10 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="mailto:hello@vizziodocs.com" class="group inline-flex items-center gap-2.5 px-10 py-5 rounded-2xl border border-white/20 bg-white/5 backdrop-blur text-white font-bold text-sm sm:text-base transition-all duration-300 hover:-translate-y-1 hover:bg-white/10 hover:border-white/30 active:translate-y-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Hubungi Tim</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         SCRIPTS
         ══════════════════════════════════════════════════════════════ --}}
@push('scripts')
<style>
    /* Page-specific animations */
    .animate-wave-1 { animation: wave 20s linear infinite; }
    .animate-wave-2 { animation: wave 15s linear infinite reverse; }
    .animate-wave-4 { animation: wave 12s linear infinite; animation-delay: -5s; }

    @keyframes wave {
        0% { transform: translateX(0); }
        50% { transform: translateX(-25%); }
        100% { transform: translateX(0); }
    }

    .float-el {
        animation: float 6s infinite ease-in-out;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Timeline dot pulse */
    .timeline-dot-pulse::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 9999px;
        background-color: rgba(129, 140, 248, 0.4);
        animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
</style>

<script>
    function aboutPage() {
        return {
            ready: false,
            counts: {
                years: 0,
                users: 0,
                tools: 0,
                countries: 0
            },
            targets: {
                years: 2026,
                users: {{ $userCount }},
                tools: 28,
                countries: {{ $countryCount }}
            },
            animationStarted: {
                years: false,
                users: false,
                tools: false,
                countries: false
            },
            animationFrames: [],

            init() {
                // Trigger hero entrance animation
                setTimeout(() => {
                    this.ready = true;
                }, 100);

                // Set up counter observer & parallax tilt
                this.$nextTick(() => {
                    this.initIntersectionObserver();
                    this.initParallaxTilt();
                });
            },

            formatNumber(num) {
                if (num >= 1000) {
                    return (num / 1000).toFixed(num >= 10000 ? 0 : 1) + 'K';
                }
                return num.toString();
            },

            animateCounter(key, target, start) {
                if (this.animationStarted[key]) return;
                this.animationStarted[key] = true;

                const duration = 2000;
                const startTime = performance.now();
                const startVal = start || 0;

                const easeOutQuart = (t) => 1 - Math.pow(1 - t, 4);

                const animate = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const easedProgress = easeOutQuart(progress);
                    const currentVal = startVal + (target - startVal) * easedProgress;

                    this.counts[key] = Math.floor(currentVal);

                    if (progress < 1) {
                        this.animationFrames.push(requestAnimationFrame(animate));
                    } else {
                        this.counts[key] = target;
                    }
                };

                this.animationFrames.push(requestAnimationFrame(animate));
            },

            initIntersectionObserver() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const key = entry.target.dataset.counter;
                            if (key && this.targets[key] !== undefined) {
                                const start = key === 'users' ? 25 : 0;
                                this.animateCounter(key, this.targets[key], start);
                                observer.unobserve(entry.target);
                            }
                        }
                    });
                }, { threshold: 0.3 });

                document.querySelectorAll('[data-counter]').forEach(el => observer.observe(el));
            },

            initParallaxTilt() {
                const cards = document.querySelectorAll('[data-tilt]');
                cards.forEach(card => {
                    card.addEventListener('mousemove', (e) => {
                        const rect = card.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        const centerX = rect.width / 2;
                        const centerY = rect.height / 2;
                        const rotateX = (y - centerY) / centerY * -8;
                        const rotateY = (x - centerX) / centerX * 8;
                        card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02,1.02,1.02)`;
                    });
                    card.addEventListener('mouseleave', () => {
                        card.style.transform = '';
                    });
                });
            }
        }
    }
</script>
@endpush
@endsection
