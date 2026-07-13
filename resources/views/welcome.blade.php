@extends('layouts.app')

@section('title', 'VizzioDocs — Konversi & Manipulasi Dokumen Online Gratis')

@section('content')
<style>
    /* ════════════════════════════════════════════════
       ╔═█  Premium Hero — Mobile-First Responsive
       ╚═══════════════════════════════════════════════ */

    /* ── Premium Mobile Keyframes ── */
    @keyframes mobileGlowPulse {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.1); }
    }
    @keyframes mobileShimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes mobileOrbDrift {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(2%, -3%) scale(1.05); }
        66% { transform: translate(-1%, 2%) scale(0.95); }
    }

    /* ── Enhanced mobile text rendering ── */
    .hero-section {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .hero-content h1 {
        -webkit-font-smoothing: antialiased;
    }

    /* ── Fix hero height on mobile: account for vd-main-content padding-top ── */
    #hero {
        min-height: calc(100svh - 72px);
    }

    /* ── Soft heading glow for mobile depth ── */
    .hero-content .heading-text::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 80vw;
        height: 40vw;
        transform: translate(-50%, -50%);
        background: radial-gradient(ellipse at center, rgba(99,102,241,0.12) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
        animation: mobileGlowPulse 4s ease-in-out infinite;
    }

    /* ── Tiny phones (<= 380px) ── */
    @media (max-width: 380px) {
        #hero {
            min-height: calc(100dvh - 62px) !important;
        }
        .hero-section .hero-content {
            padding-top: calc(var(--vd-nav-height, 56px) + 2px) !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            padding-bottom: 1.25rem !important;
        }
        .float-file-1, .float-file-2, .float-file-3,
        .float-file-4, .float-file-5 {
            display: none !important;
        }
        .float-el {
            display: none !important;
        }
        .hero-content h1 {
            font-size: 1.65rem !important;
            line-height: 1.1 !important;
            letter-spacing: -0.05em !important;
        }
        .hero-content .heading-text {
            line-height: 1.1;
        }
        .hero-content .heading-text > span {
            display: inline;
        }
        .hero-content .badge-wrap {
            padding: 0.35rem 0.8rem !important;
            font-size: 0.6rem !important;
            margin-bottom: 0.75rem !important;
            border-radius: 999px;
            backdrop-filter: blur(20px) saturate(1.4) !important;
            -webkit-backdrop-filter: blur(20px) saturate(1.4) !important;
        }
        .hero-content .badge-wrap .w-2 {
            width: 0.35rem !important;
            height: 0.35rem !important;
            margin-right: 0.4rem !important;
        }
        .hero-content .badge-wrap .h-4 {
            height: 0.6rem !important;
        }
        .hero-content .subtitle-text {
            font-size: 0.78rem !important;
            line-height: 1.5 !important;
            padding-left: 0.15rem !important;
            padding-right: 0.15rem !important;
            margin-bottom: 1rem !important;
        }
        .hero-content .greeting-text {
            font-size: 0.55rem !important;
            letter-spacing: 0.12em;
            margin-bottom: 0.25rem !important;
        }
        .hero-content .greeting-text svg {
            width: 0.6rem !important;
            height: 0.6rem !important;
        }
        .hero-content .cta-btn {
            padding: 0.6rem 1rem !important;
            font-size: 0.78rem !important;
            border-radius: 0.85rem !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
        }
        .hero-content .cta-btn svg {
            width: 0.9rem !important;
            height: 0.9rem !important;
        }
        .hero-content .cta-stack {
            gap: 0.5rem !important;
        }
        .hero-content .stat-number {
            font-size: 1rem !important;
        }
        .hero-content .stat-label {
            font-size: 0.55rem !important;
            letter-spacing: 0.03em;
        }
        .hero-content .stat-icon {
            width: 1.75rem !important;
            height: 1.75rem !important;
            margin-bottom: 0.25rem !important;
        }
        .hero-content .stat-icon svg {
            width: 0.8rem !important;
            height: 0.8rem !important;
        }
        .hero-content #hero-stats {
            gap: 0.25rem !important;
            margin-top: 1rem !important;
            max-width: 100% !important;
        }
        .hero-section .bg-gradient-to-br.from-indigo-100 {
            background: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(168,85,247,0.05) 50%, rgba(244,114,182,0.03) 100%) !important;
        }
    }

    /* ── Standard phones (381px - 480px) ── */
    @media (min-width: 381px) and (max-width: 480px) {
        #hero {
            min-height: calc(100dvh - 62px) !important;
        }
        .hero-section .hero-content {
            padding-top: calc(var(--vd-nav-height, 60px) + 6px) !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
            padding-bottom: 1.5rem !important;
        }
        .float-file-1, .float-file-3, .float-file-5 {
            display: none !important;
        }
        .float-file-2, .float-file-4 {
            opacity: 0.2 !important;
            transform: scale(0.45) !important;
            animation: mobileOrbDrift 6s ease-in-out infinite !important;
        }
        .hero-content h1 {
            font-size: 2rem !important;
            letter-spacing: -0.04em !important;
        }
        .hero-content .heading-text {
            line-height: 1.12;
        }
        .hero-content .badge-wrap {
            font-size: 0.65rem !important;
            padding: 0.4rem 0.9rem !important;
            margin-bottom: 0.85rem !important;
            backdrop-filter: blur(20px) saturate(1.4) !important;
            -webkit-backdrop-filter: blur(20px) saturate(1.4) !important;
        }
        .hero-content .subtitle-text {
            font-size: 0.88rem !important;
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
            margin-bottom: 1.25rem !important;
        }
        .hero-content .cta-btn {
            padding: 0.7rem 1.2rem !important;
            font-size: 0.88rem !important;
            border-radius: 1rem !important;
        }
        .hero-content .cta-btn.cta-btn-primary {
            box-shadow: 0 4px 20px rgba(99,102,241,0.35), 0 0 40px rgba(99,102,241,0.1) !important;
        }
        .hero-content .cta-stack {
            gap: 0.6rem !important;
        }
        .hero-content .stat-number {
            font-size: 1.25rem !important;
        }
        .hero-content .stat-label {
            font-size: 0.6rem !important;
        }
        .hero-content .stat-icon {
            width: 2rem !important;
            height: 2rem !important;
            margin-bottom: 0.3rem !important;
        }
        .hero-content .stat-icon svg {
            width: 0.9rem !important;
            height: 0.9rem !important;
        }
        .hero-content #hero-stats {
            gap: 0.35rem !important;
            margin-top: 1.25rem !important;
        }
        .hero-content .greeting-text {
            font-size: 0.6rem !important;
        }
        .hero-content .greeting-text svg {
            width: 0.65rem !important;
            height: 0.65rem !important;
        }
        .hero-section .bg-gradient-to-br.from-indigo-100 {
            background: linear-gradient(135deg, rgba(99,102,241,0.1) 0%, rgba(168,85,247,0.06) 50%, rgba(244,114,182,0.04) 100%) !important;
        }
    }

    /* ── Small landscape / large phones (481px - 639px) ── */
    @media (min-width: 481px) and (max-width: 639px) {
        #hero {
            min-height: calc(100dvh - 72px) !important;
        }
        .float-file-1, .float-file-5 {
            display: none !important;
        }
        .float-file-2, .float-file-3, .float-file-4 {
            opacity: 0.3 !important;
            transform: scale(0.6) !important;
            animation: mobileOrbDrift 7s ease-in-out infinite !important;
        }
        .hero-section .hero-content {
            padding-top: calc(var(--vd-nav-height, 64px) + 10px) !important;
            padding-bottom: 0.75rem !important;
        }
        .hero-content h1 {
            font-size: 2.3rem !important;
        }
        .hero-content .stat-number {
            font-size: 1.5rem !important;
        }
        .hero-content .stat-icon {
            width: 2.25rem !important;
            height: 2.25rem !important;
        }
        .hero-content .stat-icon svg {
            width: 1rem !important;
            height: 1rem !important;
        }
        .hero-content .badge-wrap {
            backdrop-filter: blur(20px) saturate(1.4) !important;
            -webkit-backdrop-filter: blur(20px) saturate(1.4) !important;
        }
        .hero-content .cta-btn.cta-btn-primary {
            box-shadow: 0 4px 20px rgba(99,102,241,0.35), 0 0 40px rgba(99,102,241,0.1) !important;
        }
    }

    /* ── Tablet (640px - 899px) ── */
    @media (min-width: 640px) and (max-width: 899px) {
        #hero {
            min-height: calc(100dvh - 72px) !important;
        }
        .hero-section .hero-content {
            padding-top: calc(var(--vd-nav-height, 72px) + 16px) !important;
            padding-bottom: 2rem !important;
        }
        .float-file-1, .float-file-5 {
            opacity: 0.4 !important;
            transform: scale(0.8) !important;
        }
        .float-file-2, .float-file-3, .float-file-4 {
            opacity: 0.5 !important;
            transform: scale(0.85) !important;
        }
    }

    /* ── General mobile refinements (<= 639px) ── */
    @media (max-width: 639px) {
        .hero-content {
            padding-bottom: 0.5rem !important;
        }
        .hero-content .cta-stack > .cta-btn-primary {
            margin-bottom: 0.2rem;
        }
        .hero-content .greeting-text {
            letter-spacing: 0.12em;
        }
        .hero-content .badge-wrap {
            box-shadow: 0 4px 20px rgba(99,102,241,0.12), 0 0 30px rgba(99,102,241,0.05) !important;
        }
        .hero-content .hero-content .subtitle-text {
            max-width: 95% !important;
        }
        .hero-content .heading-text > span {
            display: inline;
        }
        .scroll-indicator-desktop {
            display: none !important;
        }
        .hero-section .w-\\[40rem\\] {
            opacity: 0.2 !important;
            animation: mobileOrbDrift 8s ease-in-out infinite !important;
        }
        .hero-section .w-\\[40rem\\]:nth-child(2) {
            animation-delay: -3s !important;
        }
        .hero-section .w-\\[60rem\\] {
            opacity: 0.15 !important;
            animation: mobileOrbDrift 10s ease-in-out infinite !important;
            animation-delay: -1s !important;
        }
        .stat-item {
            padding: 0.25rem 0.15rem !important;
        }
        .stat-item .stat-label {
            margin-top: 0.15rem !important;
        }
        .cta-btn:active {
            transform: scale(0.97) !important;
            transition: transform 0.1s ease !important;
        }
        .stat-item:active .stat-icon {
            transform: scale(0.92) !important;
            transition: transform 0.1s ease !important;
        }
    }

    /* ── Safe area padding for modern phones ── */
    @supports (padding: env(safe-area-inset-bottom)) {
        .hero-section {
            padding-bottom: env(safe-area-inset-bottom);
        }
    }

    /* ── Touch-friendly: no hover states on touch devices ── */
    @media (hover: none) and (pointer: coarse) {
        .hero-content .cta-btn-primary:hover {
            transform: none !important;
        }
        .hero-content .cta-btn-secondary:hover {
            transform: none !important;
        }
        .stat-item:hover .stat-icon {
            transform: none !important;
        }
        .subtitle-text span:last-child span:first-child {
            transform: scaleX(0) !important;
        }
    }

    /* ── Fix stat grid gap on very small screens ── */
    @media (max-width: 350px) {
        #hero-stats {
            gap: 0.15rem !important;
        }
        .stat-item {
            padding: 0.15rem 0.1rem !important;
        }
        .stat-item .stat-number {
            font-size: 0.85rem !important;
        }
        .stat-item .stat-icon {
            width: 1.5rem !important;
            height: 1.5rem !important;
        }
        .stat-item .stat-icon svg {
            width: 0.7rem !important;
            height: 0.7rem !important;
        }
    }

    /* ── Smooth height animation for hero on mobile ── */
    @media (max-width: 480px) {
        .hero-content .heading-text > span {
            display: inline;
        }
        .hero-content h1 br {
            display: none;
        }
    }

    /* ── Tools Grid - Mobile Responsive ── */
    @media (max-width: 480px) {
        #tools .grid > a {
            height: auto !important;
            min-height: 14rem;
            padding: 1rem 1.25rem !important;
            border-radius: 1.25rem !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1.5px solid rgba(226,232,240,0.6) !important;
        }
        #tools .grid > a:active {
            transform: scale(0.98) !important;
            transition: transform 0.1s ease !important;
        }
        #tools .grid > a h3 {
            font-size: 0.95rem !important;
        }
        #tools .grid > a p {
            font-size: 0.75rem !important;
        }
        #tools .grid > a .icon-wrap {
            width: 2.75rem !important;
            height: 2.75rem !important;
        }
        #tools .grid > a .icon-wrap svg {
            width: 1.25rem !important;
            height: 1.25rem !important;
        }
    }
    @media (max-width: 380px) {
        #tools .grid > a {
            min-height: 12rem;
            padding: 0.85rem 1rem !important;
        }
        #tools .grid > a .icon-wrap {
            width: 2.25rem !important;
            height: 2.25rem !important;
        }
        #tools .grid > a .icon-wrap svg {
            width: 1rem !important;
            height: 1rem !important;
        }
        #tools .grid > a h3 {
            font-size: 0.85rem !important;
        }
        #tools .grid > a p {
            font-size: 0.7rem !important;
        }
    }
</style>
<!-- ════════════════════════════════════════════════════════════════
     ╔═█  Premium Hero Section — Full-Screen, Interactive, Responsive
     ╚═══════════════════════════════════════════════════════════════ -->
<div id="hero" class="hero-section relative" style="overflow:hidden;">
    {{-- Particle-like floating elements (decorative) --}}
    <div id="hero-floating-elements" class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="float-el absolute w-2 h-2 rounded-full bg-indigo-400/40" style="top:12%;left:8%;animation-delay:0s"></div>
        <div class="float-el absolute w-3 h-3 rounded-full bg-purple-400/30" style="top:25%;left:85%;animation-delay:0.4s"></div>
        <div class="float-el absolute w-2.5 h-2.5 rounded-full bg-pink-400/35" style="top:60%;left:5%;animation-delay:0.8s"></div>
        <div class="float-el absolute w-4 h-4 rounded-full bg-indigo-300/25" style="top:80%;left:75%;animation-delay:1.2s"></div>
        <div class="float-el absolute w-1.5 h-1.5 rounded-full bg-purple-400/40" style="top:40%;left:92%;animation-delay:1.6s"></div>
        <div class="float-el absolute w-3.5 h-3.5 rounded-full bg-rose-400/20" style="top:70%;left:20%;animation-delay:2s"></div>
        <div class="float-el absolute w-2 h-2 rounded-full bg-violet-400/35" style="top:15%;left:50%;animation-delay:2.4s"></div>
        <div class="float-el absolute w-2.5 h-2.5 rounded-full bg-pink-300/30" style="top:90%;left:55%;animation-delay:2.8s"></div>
    </div>

    <div class="relative flex items-center justify-center w-full h-full min-h-[inherit] bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 px-4 sm:px-6 lg:px-8 overflow-hidden">
        {{-- Floating Premium File Icons (decorative) --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0" aria-hidden="true">
            {{-- ═══ PDF Icon — top-left area ═══ --}}
            <div class="float-file-1 absolute" style="top:10%;left:2.5%;width:clamp(78px,11vw,150px);height:clamp(100px,14vw,190px)">
                <div class="file-card file-card-pdf w-full h-full p-3 sm:p-3.5">
                    <img src="/images/icon-pdf.svg" alt="PDF" class="w-full h-[70%] object-contain drop-shadow-[0_8px_12px_rgba(225,29,72,0.35)]">
                    <span class="text-[9px] sm:text-[11px] font-black text-rose-600 mt-1.5 tracking-[0.2em] uppercase">PDF</span>
                    <span class="glow-dot" style="background:#e11d48;box-shadow:0 0 6px #e11d48"></span>
                </div>
            </div>

            {{-- ═══ Word Icon — top-right area ═══ --}}
            <div class="float-file-2 absolute" style="top:8%;right:2.5%;width:clamp(74px,10.5vw,145px);height:clamp(96px,13.5vw,185px)">
                <div class="file-card file-card-word w-full h-full p-3 sm:p-3.5">
                    <img src="/images/icon-word.svg" alt="Word" class="w-full h-[70%] object-contain drop-shadow-[0_8px_12px_rgba(37,99,235,0.35)]">
                    <span class="text-[9px] sm:text-[11px] font-black text-blue-600 mt-1.5 tracking-[0.2em] uppercase">Word</span>
                    <span class="glow-dot" style="background:#2563eb;box-shadow:0 0 6px #2563eb"></span>
                </div>
            </div>

            {{-- ═══ Excel Icon — bottom-left area ═══ --}}
            <div class="float-file-3 absolute" style="bottom:18%;left:3.5%;width:clamp(70px,10vw,140px);height:clamp(92px,13vw,180px)">
                <div class="file-card file-card-excel w-full h-full p-3 sm:p-3.5">
                    <img src="/images/icon-excel.svg" alt="Excel" class="w-full h-[70%] object-contain drop-shadow-[0_8px_12px_rgba(5,150,105,0.35)]">
                    <span class="text-[9px] sm:text-[11px] font-black text-emerald-600 mt-1.5 tracking-[0.2em] uppercase">Excel</span>
                    <span class="glow-dot" style="background:#059669;box-shadow:0 0 6px #059669"></span>
                </div>
            </div>

            {{-- ═══ Folder Icon — bottom-right area ═══ --}}
            <div class="float-file-4 absolute" style="bottom:15%;right:2.5%;width:clamp(68px,9.5vw,135px);height:clamp(86px,12vw,170px)">
                <div class="file-card file-card-folder w-full h-full p-3 sm:p-3.5">
                    <img src="/images/icon-folder.svg" alt="Folder" class="w-full h-[70%] object-contain drop-shadow-[0_8px_12px_rgba(245,158,11,0.35)]">
                    <span class="text-[9px] sm:text-[11px] font-black text-amber-600 mt-1.5 tracking-[0.2em] uppercase">Folder</span>
                    <span class="glow-dot" style="background:#f59e0b;box-shadow:0 0 6px #f59e0b"></span>
                </div>
            </div>

            {{-- ═══ File Icon — middle-left area ═══ --}}
            <div class="float-file-5 absolute" style="top:46%;left:1.5%;width:clamp(58px,8vw,115px);height:clamp(76px,10.5vw,150px)">
                <div class="file-card file-card-file w-full h-full p-3 sm:p-3.5">
                    <img src="/images/icon-file.svg" alt="File" class="w-full h-[70%] object-contain drop-shadow-[0_8px_12px_rgba(71,85,105,0.35)]">
                    <span class="text-[9px] sm:text-[11px] font-black text-slate-600 mt-1.5 tracking-[0.2em] uppercase">File</span>
                    <span class="glow-dot" style="background:#475569;box-shadow:0 0 6px #475569"></span>
                </div>
            </div>
        </div>
        {{-- Animated Background Orbs --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8882_1px,transparent_1px),linear-gradient(to_bottom,#8882_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_50%,#000_70%,transparent_100%)]"></div>
        <div class="absolute -top-40 -left-40 w-[40rem] h-[40rem] bg-gradient-to-br from-indigo-400/30 via-purple-500/20 to-transparent rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute -top-40 -right-40 w-[40rem] h-[40rem] bg-gradient-to-br from-purple-400/30 via-pink-500/20 to-transparent rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[60rem] h-[30rem] bg-gradient-to-br from-pink-400/20 via-indigo-500/20 to-transparent rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
        
        <div class="hero-content relative z-10 w-full max-w-5xl mx-auto text-center py-10 sm:py-12 md:py-20">
            {{-- Premium Badge with typewriter effect data --}}
            <div class="badge-wrap inline-flex items-center px-5 py-2.5 rounded-full bg-white/80 backdrop-blur-md border border-white/60 shadow-lg shadow-indigo-500/10 mb-8 md:mb-10 hover:shadow-xl hover:shadow-indigo-500/20 transition-shadow duration-500">
                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 mr-2.5 animate-pulse"></span>
                <span id="badge-text" class="text-xs sm:text-sm font-bold text-slate-700 tracking-wide"></span>
                <span class="ml-1.5 w-1 h-4 bg-indigo-600 animate-blink inline-block"></span>
            </div>

            <div class="space-y-2 mb-6 md:mb-8">
                {{-- Greeting line with fade-up --}}
                <p id="hero-greeting" class="greeting-text text-sm md:text-base font-semibold text-slate-500 tracking-widest uppercase opacity-0 translate-y-4 transition-all duration-700 ease-out">
                    <span class="inline-flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-400"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> Platform Manajemen Dokumen Terdepan</span>
                </p>

                {{-- Main Heading with word-by-word reveal --}}
                <h1 class="heading-text text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black tracking-tight leading-[1.1]">
                    <span class="text-slate-900 inline-block opacity-0 translate-y-8 transition-all duration-700 ease-out delay-100">Solusi</span>
                    <span class="text-slate-900 inline-block opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200">Dokumen</span>
                    <span class="text-slate-900 inline-block opacity-0 translate-y-8 transition-all duration-700 ease-out delay-300">Modern</span>
                    <br>
                    <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent inline-block opacity-0 translate-y-8 transition-all duration-700 ease-out delay-[400ms]">
                        Cepat, Akurat, Andal
                    </span>
                </h1>
            </div>
            
            {{-- CTA Buttons --}}
            <div class="cta-stack flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-5 opacity-0 translate-y-6 transition-all duration-700 ease-out delay-700" id="hero-ctas">
                <a href="#tools" class="cta-btn cta-btn-primary group relative overflow-hidden rounded-2xl shadow-2xl shadow-indigo-500/40 hover:shadow-indigo-600/60 transition-all duration-500 hover:scale-105 w-full sm:w-auto active:scale-95">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full bg-gradient-to-r from-transparent via-white/30 to-transparent transition-transform duration-1000"></div>
                    <span class="relative flex items-center justify-center space-x-3 py-4 px-8 sm:px-10 text-base sm:text-lg font-bold text-white">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 group-hover:rotate-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        <span>Mulai Sekarang</span>
                    </span>
                </a>
                <a href="{{ route('fitur') }}" class="cta-btn cta-btn-secondary group flex items-center justify-center space-x-2 w-full sm:w-auto px-8 sm:px-10 py-4 rounded-2xl font-bold text-slate-700 bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-indigo-300 hover:text-indigo-600 hover:bg-white shadow-lg hover:shadow-xl transition-all duration-300 active:scale-95">
                    <span>Lihat Semua Alat</span>
                    <svg class="w-5 h-5 mr-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-3 gap-3 sm:gap-8 mt-10 md:mt-16 max-w-lg mx-auto" id="hero-stats">
                <div class="stat-item text-center px-2 opacity-0 translate-y-6 transition-all duration-700 ease-out delay-[900ms] group">
                    <div class="stat-icon inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-indigo-500/10 text-indigo-600 mb-1.5 sm:mb-2.5 transform group-hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7m-1.293-1.293a1 1 0 00-1.414 0L9 17.586V19h1.414l6.293-6.293a1 1 0 000-1.414l-4-4z" />
                        </svg>
                    </div>
                    <div class="stat-number text-2xl sm:text-3xl md:text-4xl font-black bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        <span class="counter" data-target="28">0</span>+
                    </div>
                    <div class="stat-label text-xs sm:text-sm font-semibold text-slate-500 mt-1">Alat Eksklusif</div>
                </div>
                <div class="stat-item text-center px-2 opacity-0 translate-y-6 transition-all duration-700 ease-out delay-[1100ms] group">
                    <div class="stat-icon inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-purple-500/10 text-purple-600 mb-1.5 sm:mb-2.5 transform group-hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15V3m0 0l-4 4m4-4l4 4M4 18h16a2 2 0 012 2v2H2v-2a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <div class="stat-number text-2xl sm:text-3xl md:text-4xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                        <span class="counter" data-target="100">0</span>%
                    </div>
                    <div class="stat-label text-xs sm:text-sm font-semibold text-slate-500 mt-1">Keamanan Data</div>
                </div>
                <div class="stat-item text-center px-2 opacity-0 translate-y-6 transition-all duration-700 ease-out delay-[1300ms] group">
                    <div class="stat-icon inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-pink-500/10 text-pink-600 mb-1.5 sm:mb-2.5 transform group-hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="stat-number text-2xl sm:text-3xl md:text-4xl font-black bg-gradient-to-r from-pink-600 to-indigo-600 bg-clip-text text-transparent">
                        <span class="counter" data-target="{{ $adminMaxFileSizeMB }}">0</span>MB
                    </div>
                    <div class="stat-label text-xs sm:text-sm font-semibold text-slate-500 mt-1">Berkas Maksimal</div>
                </div>
            </div>

            {{-- Scroll Indicator --}}
            <div class="scroll-indicator-desktop absolute bottom-4 md:bottom-6 left-1/2 -translate-x-1/2 hidden sm:block">
                <div class="flex flex-col items-center space-y-2 animate-bounce">
                    <span class="text-[10px] font-semibold text-slate-400 tracking-[0.2em] uppercase">Scroll</span>
                    <svg class="w-4 h-4 text-slate-400 opacity-70 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ═══ Premium Aurora Wave Divider ═══ --}}
        <div class="absolute bottom-0 left-0 right-0 w-full pointer-events-none z-[5] overflow-hidden leading-none" style="height:clamp(50px,9vw,110px);-webkit-mask-image:linear-gradient(to bottom,transparent 0%,#000 20%);mask-image:linear-gradient(to bottom,transparent 0%,#000 20%)" aria-hidden="true">

            {{-- Gradient Orbs for Depth (Aurora glow) --}}
            <div class="absolute w-[40%] h-[140%] rounded-full bg-indigo-500/15 blur-[80px] animate-orb-a top-[-20%] left-[-5%]"></div>
            <div class="absolute w-[32%] h-[120%] rounded-full bg-purple-500/10 blur-[70px] animate-orb-b top-[-10%] left-[30%]"></div>
            <div class="absolute w-[28%] h-[100%] rounded-full bg-pink-400/8 blur-[60px] animate-orb-c top-[-5%] left-[70%]"></div>

            {{-- Wave Layer 1 — Deep Indigo Gradient (back) --}}
            <svg class="absolute bottom-0 left-0 w-[200%] h-full animate-wave-a" viewBox="0 0 2000 200" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="wa" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#4f46e5" stop-opacity="0.25"/>
                        <stop offset="50%"  stop-color="#7c3aed" stop-opacity="0.20"/>
                        <stop offset="100%" stop-color="#6366f1" stop-opacity="0.22"/>
                    </linearGradient>
                </defs>
                <path d="M0,170 C180,90 360,190 540,125 C720,65 900,195 1080,115 C1260,50 1440,190 1620,120 C1800,70 1920,175 2000,145 L2000,200 L0,200 Z" fill="url(#wa)"/>
            </svg>

            {{-- Wave Layer 2 — Purple/Violet Gradient --}}
            <svg class="absolute bottom-0 left-0 w-[200%] h-full animate-wave-b" viewBox="0 0 2000 200" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="wb" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#a855f7" stop-opacity="0.18"/>
                        <stop offset="50%"  stop-color="#8b5cf6" stop-opacity="0.15"/>
                        <stop offset="100%" stop-color="#c084fc" stop-opacity="0.12"/>
                    </linearGradient>
                </defs>
                <path d="M0,185 C250,120 500,190 750,150 C1000,95 1250,195 1500,140 C1750,85 1900,185 2000,160 L2000,200 L0,200 Z" fill="url(#wb)"/>
            </svg>

            {{-- Wave Layer 3 — Soft Pink Gradient (foreground, highest opacity) --}}
            <svg class="absolute bottom-0 left-0 w-[200%] h-full animate-wave-c" viewBox="0 0 2000 200" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="wc" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#f9a8d4" stop-opacity="0.12"/>
                        <stop offset="50%"  stop-color="#f472b6" stop-opacity="0.15"/>
                        <stop offset="100%" stop-color="#fb7185" stop-opacity="0.10"/>
                    </linearGradient>
                </defs>
                <path d="M0,195 C300,150 600,200 900,165 C1200,120 1500,200 1800,155 L2000,175 L2000,200 L0,200 Z" fill="url(#wc)"/>
            </svg>

            {{-- Subtle Shimmer Sweep --}}
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/8 to-transparent -skew-y-6 animate-shimmer pointer-events-none"></div>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════════════
     ╔═█  Hero Interaction JavaScript
     ╚═══════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function() {
    'use strict';

    // ── 1. Typewriter Effect for Badge ────────────────────────
    const badgeEl = document.getElementById('badge-text');
    const badgePhrases = [
        '#1 Platform Manajemen Dokumen Online',
        'Solusi Premium — Terpercaya & Andal',
        'Konversi, Gabung, Kompres & Kelola PDF',
        'Produktivitas Dokumen Tanpa Batas'
    ];
    let phraseIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let typeTimer;

    function typeBadge() {
        const currentPhrase = badgePhrases[phraseIndex];
        
        if (isDeleting) {
            badgeEl.textContent = currentPhrase.substring(0, charIndex - 1);
            charIndex--;
        } else {
            badgeEl.textContent = currentPhrase.substring(0, charIndex + 1);
            charIndex++;
        }

        let delay = isDeleting ? 25 : 60;

        if (!isDeleting && charIndex === currentPhrase.length) {
            delay = 3000;
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            phraseIndex = (phraseIndex + 1) % badgePhrases.length;
            delay = 500;
        }

        typeTimer = setTimeout(typeBadge, delay);
    }

    // ── 2. Intersection Observer for Entrance Animations ──────
    function animateEntrance() {
        // Greeting
        const greeting = document.getElementById('hero-greeting');
        if (greeting) { greeting.classList.remove('opacity-0', 'translate-y-4'); }

        // Heading words
        document.querySelectorAll('#hero h1 span').forEach(el => {
            el.classList.remove('opacity-0', 'translate-y-8');
        });

        // Subtitle
        const subtitle = document.getElementById('hero-subtitle');
        if (subtitle) { subtitle.classList.remove('opacity-0', 'translate-y-6'); }

        // CTAs
        const ctas = document.getElementById('hero-ctas');
        if (ctas) { ctas.classList.remove('opacity-0', 'translate-y-6'); }

        // Stats with delay
        document.querySelectorAll('.stat-item').forEach(el => {
            el.classList.remove('opacity-0', 'translate-y-6');
        });
    }

    // ── 3. Counter Animation ──────────────────────────────────
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = Math.ceil(target / (duration / 16));
            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current >= target) {
                    counter.textContent = target;
                    return;
                }
                counter.textContent = current;
                requestAnimationFrame(updateCounter);
            };

            updateCounter();
        });
    }

    // ── 4. Mouse Parallax Effect on Floating Elements ─────────
    function initParallax() {
        const hero = document.getElementById('hero');
        const floats = document.querySelectorAll('.float-el');
        
        hero.addEventListener('mousemove', (e) => {
            const rect = hero.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
            const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
            
            floats.forEach((el, i) => {
                const speed = 10 + (i * 3);
                const moveX = x * speed;
                const moveY = y * speed;
                el.style.transform = `translate(${moveX}px, ${moveY}px)`;
            });
        });
    }

    // ── 5. Smooth scroll for anchor links ─────────────────────
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    // ── 6. Floating Elements Tilt on Device Tilt (mobile) ─────
    function initDeviceTilt() {
        if (!window.DeviceOrientationEvent) return;
        
        let tiltActive = false;
        let tiltX = 0, tiltY = 0;
        const floats = document.querySelectorAll('.float-el');

        window.addEventListener('deviceorientation', (e) => {
            if (e.gamma !== null && e.beta !== null) {
                tiltX = (e.gamma || 0) / 45;
                tiltY = ((e.beta || 0) - 45) / 45;
                tiltActive = true;
            }
        }, { passive: true });

        // Apply tilt every frame if active
        function applyTilt() {
            if (tiltActive) {
                floats.forEach((el, i) => {
                    const speed = 8 + (i * 2);
                    el.style.transform = `translate(${tiltX * speed}px, ${tiltY * speed}px)`;
                });
            }
            requestAnimationFrame(applyTilt);
        }
        requestAnimationFrame(applyTilt);
    }

    // ── 7. Navbar scroll shadow effect on hero visibility ─────
    function initNavbarEffect() {
        const hero = document.getElementById('hero');
        const header = document.querySelector('.vd-header');
        if (!hero || !header) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) {
                    header.style.boxShadow = '0 4px 30px rgba(0,0,0,0.08)';
                } else {
                    header.style.boxShadow = '0 1px 0 rgba(0,0,0,.04)';
                }
            });
        }, { threshold: [0, 0.5, 1] });

        observer.observe(hero);
    }

    // ── Init ──────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        // Start entrance animations immediately
        setTimeout(animateEntrance, 100);
        
        // Start typewriter after entrance
        setTimeout(() => {
            typeBadge();
        }, 1200);

        // Start counters when stats become visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const statsEl = document.getElementById('hero-stats');
        if (statsEl) statsObserver.observe(statsEl);

        // Parallax
        initParallax();
        
        // Smooth scroll
        initSmoothScroll();

        // Device tilt
        initDeviceTilt();

        // Navbar effect
        initNavbarEffect();
    });

})();
</script>
@endpush

    <!-- Grid of Features (12 Tools) -->
    <div id="tools" class="scroll-mt-8 py-20 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 space-y-3">
            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-600 mb-2">Paling Laris</span>
            <h2 class="text-4xl font-black text-slate-900 tracking-tight">
                Alat <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Terpopuler</span>
            </h2>
            <p class="text-slate-500 text-lg font-medium max-w-2xl mx-auto">
                Coba alat unggulan kami di bawah, atau jelajahi <a href="{{ route('fitur') }}" class="text-indigo-600 font-semibold underline hover:text-purple-600 transition-colors">semua alat</a> yang tersedia.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            <!-- 1. Compress PDF -->
            <a href="{{ route('compress.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-indigo-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-purple-500/0 group-hover:from-indigo-500/5 group-hover:to-purple-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    01
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2">Kompres PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Kurangi ukuran file PDF Anda tanpa mengurangi kualitas dokumen secara signifikan.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-indigo-600 group-hover:text-purple-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Kompres</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 2. Merge PDF -->
            <a href="{{ route('merge.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-purple-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-purple-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-pink-500/0 group-hover:from-purple-500/5 group-hover:to-pink-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-gradient-to-r from-orange-400 to-pink-500 text-white text-xs font-black shadow-lg animate-pulse-slow">
                    POPULER
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg shadow-purple-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-purple-600 transition-colors mb-2">Gabungkan PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Gabungkan beberapa file PDF menjadi satu file dokumen teratur dan rapi.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-purple-600 group-hover:text-pink-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Gabungkan</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 3. Split PDF -->
            <a href="{{ route('split.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-blue-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-blue-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-cyan-500/0 group-hover:from-blue-500/5 group-hover:to-cyan-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-cyan-100 text-blue-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    03
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg shadow-blue-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 11-4.243 4.243 3 3 0 014.243-4.243zm0-5.758a3 3 0 11-4.243-4.243 3 3 0 014.243 4.243z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors mb-2">Pisahkan PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekstrak halaman tertentu atau pisahkan seluruh halaman PDF menjadi file terpisah.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-blue-600 group-hover:text-cyan-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Pisahkan</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 4. Crop PDF -->
            <a href="{{ route('crop.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-rose-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-rose-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-rose-500/0 to-pink-500/0 group-hover:from-rose-500/5 group-hover:to-pink-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-gradient-to-r from-rose-400 to-pink-500 text-white text-xs font-black shadow-lg animate-pulse-slow">
                    BARU
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-lg shadow-rose-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-rose-600 transition-colors mb-2">Crop PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Potong area tertentu dari halaman PDF secara interaktif dengan crop box.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-rose-600 group-hover:text-pink-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Crop</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 5. JPG to PDF -->
            <a href="{{ route('jpg-to-pdf.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-emerald-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-emerald-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-green-500/0 group-hover:from-emerald-500/5 group-hover:to-green-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-emerald-100 to-green-100 text-emerald-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    05
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors mb-2">JPG ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ubah dan satukan gambar format JPG/JPEG Anda menjadi sebuah file PDF.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-emerald-600 group-hover:text-green-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Konversi</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 6. PDF to JPG -->
            <a href="{{ route('pdf-to-jpg.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-teal-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-teal-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-500/0 to-cyan-500/0 group-hover:from-teal-500/5 group-hover:to-cyan-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-teal-100 to-cyan-100 text-teal-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    06
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 text-white shadow-lg shadow-teal-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-teal-600 transition-colors mb-2">PDF ke JPG</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekstrak setiap halaman dari file PDF Anda menjadi gambar berformat JPG.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-teal-600 group-hover:text-cyan-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Ekstrak</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 7. Rotate PDF -->
            <a href="{{ route('rotate.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-amber-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-amber-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/10 group-hover:to-orange-500/10 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    07
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/50 group-hover:scale-110 group-hover:rotate-12 transition-all duration-700">
                        <svg class="w-8 h-8 group-hover:animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-amber-600 transition-colors mb-2">Putar PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Putar posisi halaman PDF Anda sesuai keinginan (90°, 180°, atau 270°).</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-amber-600 group-hover:text-orange-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Putar</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 8. Word to PDF -->
            <a href="{{ route('word-to-pdf.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-blue-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-blue-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-indigo-500/0 group-hover:from-blue-500/5 group-hover:to-indigo-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    08
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors mb-2">Word ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi dokumen Microsoft Word (.docx) Anda menjadi file PDF yang rapi.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-blue-600 group-hover:text-indigo-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Konversi</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 9. PDF to Word -->
            <a href="{{ route('pdf-to-word.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-violet-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-violet-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-500/0 to-purple-500/0 group-hover:from-violet-500/5 group-hover:to-purple-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-violet-100 to-purple-100 text-violet-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    09
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 text-white shadow-lg shadow-violet-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-violet-600 transition-colors mb-2">PDF ke Word</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekstrak teks dari file PDF Anda dan ubah menjadi dokumen Word (.docx).</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-violet-600 group-hover:text-purple-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Konversi</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 10. Excel to PDF -->
            <a href="{{ route('excel-to-pdf.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-rose-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-rose-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-rose-500/0 to-pink-500/0 group-hover:from-rose-500/5 group-hover:to-pink-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-rose-100 to-pink-100 text-rose-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    10
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-lg shadow-rose-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-rose-600 transition-colors mb-2">Excel ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Konversi tabel Microsoft Excel (.xlsx/.xls) menjadi PDF dengan layout rapi.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-rose-600 group-hover:text-pink-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Konversi</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 11. PNG to PDF -->
            <a href="{{ route('png-to-pdf.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-sky-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-sky-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-blue-500/0 group-hover:from-sky-500/5 group-hover:to-blue-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-sky-100 to-blue-100 text-sky-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    11
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-sky-600 transition-colors mb-2">PNG ke PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ubah gambar format PNG transparan Anda menjadi file PDF secara cepat.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-sky-600 group-hover:text-blue-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Konversi</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <!-- 12. PDF to TXT -->
            <a href="{{ route('pdf-to-txt.index') }}" class="opacity-0 translate-y-6 group relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-orange-400 p-6 rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-orange-500/30 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between h-64 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/0 to-amber-500/0 group-hover:from-orange-500/5 group-hover:to-amber-500/5 transition-all duration-500 rounded-3xl"></div>
                <div class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 text-orange-600 text-xs font-black group-hover:scale-110 transition-transform duration-300">
                    12
                </div>
                <div class="relative space-y-4">
                    <div class="icon-wrap w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 text-white shadow-lg shadow-orange-500/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-orange-600 transition-colors mb-2">PDF ke TXT</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekstrak teks dari file PDF Anda dan simpan sebagai file teks (.txt) biasa.</p>
                    </div>
                </div>
                <div class="relative flex items-center text-sm font-bold text-orange-600 group-hover:text-amber-600 group-hover:translate-x-2 transition-all duration-300">
                    <span>Mulai Ekstrak</span>
                    <svg class="w-5 h-5 ml-2 group-hover:ml-3 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>
        </div>
        
        <!-- Lihat Semua Alat -->
        <div class="text-center mt-12">
            <a href="{{ route('fitur') }}" class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg rounded-2xl shadow-xl shadow-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
                <span>Lihat Semua Alat</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</div>

        </div>
    </div>
</div>
@push('scripts')
<script>
(function() {
    'use strict';
    document.addEventListener('DOMContentLoaded', function() {
        var toolCards = document.querySelectorAll('#tools .grid > a');
        if (!toolCards.length) return;
        
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var card = entry.target;
                    var index = Array.from(card.parentElement.children).indexOf(card);
                    card.style.transitionDelay = (index * 60) + 'ms';
                    card.classList.remove('opacity-0', 'translate-y-6');
                    observer.unobserve(card);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        
        toolCards.forEach(function(card) {
            observer.observe(card);
        });
    });
})();
</script>
@endpush
@endsection
