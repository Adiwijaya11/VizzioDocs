@extends('layouts.app')

@section('title', 'Pusat Bantuan — VizzioDocs')

@section('content')
<style>
    /* ════════════════════════════════════════════════
       ╔═█  Pusat Bantuan — Premium Responsive
       ╚═══════════════════════════════════════════════ */

    /* ── Smooth accordion animation ── */
    .faq-content {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .faq-content.open {
        grid-template-rows: 1fr;
    }
    .faq-content > div {
        overflow: hidden;
    }

    /* ── Search highlight ── */
    .search-highlight {
        background: linear-gradient(120deg, rgba(99,102,241,0.15) 0%, rgba(139,92,246,0.15) 100%);
        border-radius: 0.25rem;
        padding: 0 0.125rem;
    }

    /* ── Staggered card entrance ── */
    .help-card {
        opacity: 0;
        transform: translateY(20px);
        animation: helpCardFadeIn 0.5s ease-out forwards;
    }
    .help-card:nth-child(1) { animation-delay: 0.05s; }
    .help-card:nth-child(2) { animation-delay: 0.1s; }
    .help-card:nth-child(3) { animation-delay: 0.15s; }
    .help-card:nth-child(4) { animation-delay: 0.2s; }
    .help-card:nth-child(5) { animation-delay: 0.25s; }
    .help-card:nth-child(6) { animation-delay: 0.3s; }
    .help-card:nth-child(7) { animation-delay: 0.35s; }
    .help-card:nth-child(8) { animation-delay: 0.4s; }

    @keyframes helpCardFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Soft heading glow ── */
    .heading-glow::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        border-radius: 2px;
        background: linear-gradient(90deg, #6366f1, #a855f7);
    }

    /* ── Beautiful shimmer on category cards ── */
    .cat-card {
        position: relative;
        overflow: hidden;
    }
    .cat-card::before {
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
    .cat-card:hover::before {
        left: 125%;
    }

    /* ── Mobile optimizations ── */
    @media (max-width: 640px) {
        .help-hero-title {
            font-size: 2rem !important;
        }
        .help-hero-subtitle {
            font-size: 0.95rem !important;
        }
        .faq-question {
            font-size: 0.9rem !important;
            padding: 1rem 1rem !important;
        }
        .faq-answer {
            font-size: 0.85rem !important;
            padding: 0 1rem 1rem 1rem !important;
        }
    }
</style>

<div class="relative bg-white" x-data="helpCenter()" x-init="init()">
    <!-- ════ Background Orbs ════ -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-purple-500/5 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/3 left-1/4 w-[300px] h-[300px] bg-pink-500/5 rounded-full blur-[100px]"></div>
    </div>

    <!-- ════════════════════════════════════════════════
         HERO SECTION
         ════════════════════════════════════════════════ -->
    <section class="relative pt-28 pb-16 sm:pt-32 sm:pb-20 lg:pt-40 lg:pb-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/60 via-white to-white pointer-events-none"></div>
        <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-indigo-200/60 to-transparent"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100/60 text-indigo-700 text-xs sm:text-sm font-bold tracking-wide mb-6 sm:mb-8 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Siap membantu Anda 24/7</span>
            </div>

            <!-- Title -->
            <h1 class="help-hero-title text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-tight">
                Ada yang bisa kami <br class="hidden sm:block"/>
                <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 bg-clip-text text-transparent">bantu</span>?
            </h1>

            <p class="help-hero-subtitle mt-4 sm:mt-6 text-base sm:text-lg text-slate-500 font-medium max-w-2xl mx-auto leading-relaxed">
                Temukan jawaban untuk pertanyaan Anda seputar penggunaan alat-alat VizzioDocs, 
                tips & trik, serta informasi lainnya.
            </p>

            <!-- Search Bar -->
            <div class="mt-8 sm:mt-10 max-w-xl mx-auto">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery"
                        class="block w-full pl-12 pr-12 py-4 sm:py-5 bg-white border-2 border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl transition-all outline-none text-slate-900 font-semibold placeholder-slate-400 shadow-lg shadow-slate-200/50"
                        placeholder="Cari bantuan... misal: cara kompres PDF">
                    <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Search suggestions -->
                <div class="flex flex-wrap justify-center gap-2 mt-4">
                    <span class="text-xs text-slate-400 font-semibold mr-1">Populer:</span>
                    <template x-for="suggestion in popularSearches" :key="suggestion">
                        <button @click="searchQuery = suggestion; search()"
                            class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200"
                            x-text="suggestion">
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════
         SEARCH RESULTS (shown when searching)
         ════════════════════════════════════════════════ -->
    <section x-cloak x-show="searchQuery.length > 0" class="relative -mt-4 pb-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <template x-if="filteredFaqs.length === 0">
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-100 mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 mb-1">Hasil tidak ditemukan</h3>
                        <p class="text-sm text-slate-400">Coba gunakan kata kunci lain atau jelajahi kategori di bawah.</p>
                    </div>
                </template>

                <template x-for="faq in filteredFaqs" :key="faq.id">
                    <div class="mb-3">
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                            <button @click="toggleFaq(faq.id)"
                                class="faq-question w-full flex items-center justify-between px-5 py-4 text-left text-sm font-bold text-slate-700 hover:text-indigo-600 transition-colors duration-200">
                                <span x-html="highlightMatch(faq.question)"></span>
                                <svg class="w-4 h-4 flex-shrink-0 ml-3 text-slate-400 transition-transform duration-300"
                                    :class="{ 'rotate-180 text-indigo-600': openFaq === faq.id }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="faq-content" :class="{ 'open': openFaq === faq.id }">
                                <div>
                                    <div class="faq-answer px-5 pb-4 text-sm text-slate-500 leading-relaxed" x-html="highlightMatch(faq.answer)"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════
         CATEGORIES SECTION
         ════════════════════════════════════════════════ -->
    <section class="relative py-12 sm:py-16 lg:py-20" x-cloak x-show="searchQuery.length === 0">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-14">
                <h2 class="relative inline-block text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-slate-900 heading-glow pb-4">
                    Jelajahi Topik Bantuan
                </h2>
                <p class="mt-4 text-sm sm:text-base text-slate-500 font-medium max-w-xl mx-auto">
                    Pilih kategori di bawah untuk menemukan solusi yang Anda butuhkan.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 max-w-5xl mx-auto">
                <template x-for="(cat, index) in categories" :key="index">
                    <button @click="activeCategory = cat.id; scrollToFaqs()"
                        class="cat-card relative flex flex-col items-center gap-2 sm:gap-3 px-3 sm:px-5 py-5 sm:py-7 rounded-2xl border-2 transition-all duration-300 hover:-translate-y-1"
                        :class="activeCategory === cat.id 
                            ? 'border-indigo-500 bg-indigo-50/70 shadow-lg shadow-indigo-500/10' 
                            : 'border-slate-100 bg-white hover:border-indigo-200 hover:shadow-md hover:shadow-indigo-500/5'">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-xl text-xl sm:text-2xl shadow-sm"
                            :class="activeCategory === cat.id ? cat.bgActive : cat.bg">
                            <span x-html="cat.icon"></span>
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-center text-slate-700 leading-tight" x-text="cat.name"></span>
                    </button>
                </template>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════
         FAQ SECTION (per category)
         ════════════════════════════════════════════════ -->
    <section class="relative pb-16 sm:pb-20 lg:pb-28" id="faq-section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <!-- Category header -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl text-lg shadow-sm"
                        :class="currentCategoryBg">
                        <span x-html="currentCategoryIcon"></span>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900" x-text="currentCategoryName"></h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium" x-text="currentCategoryDesc"></p>
                    </div>
                </div>

                <!-- FAQ Items -->
                <template x-if="filteredFaqs.length === 0">
                    <div class="text-center py-16 bg-slate-50 rounded-3xl border border-slate-100">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-sm font-bold text-slate-500">Belum ada pertanyaan untuk kategori ini.</p>
                    </div>
                </template>

                <template x-for="faq in filteredFaqs" :key="faq.id">
                    <div class="help-card mb-3">
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                            <button @click="toggleFaq(faq.id)"
                                class="faq-question w-full flex items-center justify-between px-5 sm:px-6 py-4 sm:py-5 text-left text-sm sm:text-base font-bold text-slate-700 hover:text-indigo-600 transition-colors duration-200 gap-3">
                                <span x-text="faq.question"></span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 text-slate-400 transition-transform duration-300"
                                    :class="{ 'rotate-180 text-indigo-600': openFaq === faq.id }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="faq-content" :class="{ 'open': openFaq === faq.id }">
                                <div>
                                    <div class="faq-answer px-5 sm:px-6 pb-5 sm:pb-6 text-sm sm:text-base text-slate-500 leading-relaxed" x-text="faq.answer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Still need help -->
                <div class="mt-10 text-center p-6 sm:p-8 bg-gradient-to-br from-indigo-50/80 via-purple-50/60 to-pink-50/60 rounded-3xl border border-indigo-100/60">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 mb-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-slate-800 mb-1">Masih butuh bantuan?</h4>
                    <p class="text-sm text-slate-500 mb-4">Tim support kami siap merespon pertanyaan Anda dalam waktu 1x24 jam.</p>
                    <a href="mailto:support@vizziocs.com" 
                        class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all duration-200 text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Hubungi Support
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════
         QUICK LINKS SECTION
         ════════════════════════════════════════════════ -->
    <section class="relative pb-16 sm:pb-20 lg:pb-28" x-cloak x-show="searchQuery.length === 0">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('fitur') }}" class="help-card flex items-center gap-4 p-4 sm:p-5 bg-white border border-slate-200 rounded-2xl hover:border-indigo-200 hover:shadow-md transition-all duration-200 group">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">Fitur & Alat</span>
                            <p class="text-xs text-slate-400">Lihat semua alat yang tersedia</p>
                        </div>
                    </a>
                    <a href="{{ route('about') }}" class="help-card flex items-center gap-4 p-4 sm:p-5 bg-white border border-slate-200 rounded-2xl hover:border-purple-200 hover:shadow-md transition-all duration-200 group">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-800 group-hover:text-purple-600 transition-colors">Tentang Kami</span>
                            <p class="text-xs text-slate-400">Kenali VizzioDocs lebih jauh</p>
                        </div>
                    </a>
                    <a href="mailto:support@vizziocs.com" class="help-card flex items-center gap-4 p-4 sm:p-5 bg-white border border-slate-200 rounded-2xl hover:border-pink-200 hover:shadow-md transition-all duration-200 group">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-pink-50 text-pink-600 group-hover:bg-pink-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19C3 19 5 17 11 17C17 17 19 19 19 19M9 9C9 10.6569 10.3431 12 12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9Z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-800 group-hover:text-pink-600 transition-colors">Hubungi Tim</span>
                            <p class="text-xs text-slate-400">support@vizziocs.com</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    function helpCenter() {
        return {
            searchQuery: '',
            openFaq: null,
            activeCategory: 'memulai',

            categories: [
                {
                    id: 'memulai',
                    name: 'Memulai',
                    desc: 'Panduan awal menggunakan VizzioDocs',
                    icon: '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                    bg: 'bg-indigo-50 text-indigo-600',
                    bgActive: 'bg-indigo-100 text-indigo-700',
                },
                {
                    id: 'kompres',
                    name: 'Kompres PDF',
                    desc: 'Cara mengecilkan ukuran PDF',
                    icon: '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>',
                    bg: 'bg-emerald-50 text-emerald-600',
                    bgActive: 'bg-emerald-100 text-emerald-700',
                },
                {
                    id: 'gabung',
                    name: 'Gabung PDF',
                    desc: 'Menggabungkan beberapa PDF',
                    icon: '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5C4 3.89543 4.89543 3 6 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21H6C4.89543 21 4 20.1046 4 19V5Z"/></svg>',
                    bg: 'bg-blue-50 text-blue-600',
                    bgActive: 'bg-blue-100 text-blue-700',
                },
                {
                    id: 'pisah',
                    name: 'Pisah PDF',
                    desc: 'Memisahkan halaman PDF',
                    icon: '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5L14 19M10 5L10 19M6 5H18C19.1046 5 20 5.89543 20 7V17C20 18.1046 19.1046 19 18 19H6C4.89543 19 4 18.1046 4 17V7C4 5.89543 4.89543 5 6 5Z"/></svg>',
                    bg: 'bg-amber-50 text-amber-600',
                    bgActive: 'bg-amber-100 text-amber-700',
                },
                {
                    id: 'konversi',
                    name: 'Konversi Format',
                    desc: 'Ubah format file ke/dari PDF',
                    icon: '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>',
                    bg: 'bg-purple-50 text-purple-600',
                    bgActive: 'bg-purple-100 text-purple-700',
                },
                {
                    id: 'edit',
                    name: 'Edit PDF',
                    desc: 'Memutar, watermark & lainnya',
                    icon: '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
                    bg: 'bg-rose-50 text-rose-600',
                    bgActive: 'bg-rose-100 text-rose-700',
                },
                {
                    id: 'keamanan',
                    name: 'Keamanan & Privasi',
                    desc: 'Bagaimana data Anda dilindungi',
                    icon: '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                    bg: 'bg-cyan-50 text-cyan-600',
                    bgActive: 'bg-cyan-100 text-cyan-700',
                },
                {
                    id: 'akun',
                    name: 'Akun & Tagihan',
                    desc: 'Kelola akun dan langganan',
                    icon: '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
                    bg: 'bg-orange-50 text-orange-600',
                    bgActive: 'bg-orange-100 text-orange-700',
                },
            ],

            faqs: [
                // ── Memulai ──
                { id: 1, category: 'memulai', question: 'Apa itu VizzioDocs?', answer: 'VizzioDocs adalah platform online gratis yang menyediakan berbagai alat untuk mengelola dokumen PDF. Anda dapat mengompres, menggabungkan, memisahkan, mengonversi, dan mengedit file PDF langsung dari browser tanpa perlu menginstal software apapun.' },
                { id: 2, category: 'memulai', question: 'Apakah VizzioDocs benar-benar gratis?', answer: 'Ya! VizzioDocs gratis digunakan untuk semua fitur dasar. Kami menyediakan berbagai alat PDF tanpa biaya. Ke depannya, mungkin akan ada fitur premium dengan kapasitas lebih besar dan fitur tambahan untuk pengguna dengan kebutuhan lebih tinggi.' },
                { id: 3, category: 'memulai', question: 'Apakah perlu mendaftar akun?', answer: 'Untuk menggunakan alat-alat dasar, Anda tidak perlu mendaftar. Namun, dengan mendaftar akun gratis, Anda bisa menyimpan riwayat file, mengakses file lebih besar, dan menikmati fitur tambahan lainnya.' },
                { id: 4, category: 'memulai', question: 'Apa saja alat yang tersedia di VizzioDocs?', answer: 'Saat ini VizzioDocs memiliki 28 alat! Mulai dari kompres PDF, gabung PDF, pisah PDF, konversi JPG/PNG/Word/Excel ke PDF, PDF ke JPG, rotasi PDF, watermark, dan masih banyak lagi. Lihat halaman Fitur untuk daftar lengkapnya.' },
                { id: 5, category: 'memulai', question: 'Bagaimana cara mulai menggunakan alat VizzioDocs?', answer: 'Cukup kunjungi halaman alat yang diinginkan (misal Kompres PDF), unggah file Anda, dan sistem akan memprosesnya secara otomatis. Proses berlangsung cepat dan file Anda aman.' },

                // ── Kompres PDF ──
                { id: 6, category: 'kompres', question: 'Bagaimana cara mengompres PDF?', answer: 'Buka halaman Kompres PDF, unggah file PDF yang ingin dikompres, pilih tingkat kompresi (ringan/sedang/kuat), lalu klik tombol kompres. File hasil akan langsung terunduh setelah selesai.' },
                { id: 7, category: 'kompres', question: 'Seberapa kecil ukuran file setelah dikompres?', answer: 'Tergantung pada tingkat kompresi yang dipilih. Kompresi ringan mengurangi ukuran sekitar 20-30%, sedang 40-60%, dan kuat bisa mencapai 70-80% tanpa mengurangi kualitas visual secara signifikan.' },
                { id: 8, category: 'kompres', question: 'Apakah kualitas PDF berkurang setelah dikompres?', answer: 'Algoritma kompresi kami dirancang untuk mempertahankan kualitas gambar dan teks sebisa mungkin. Untuk kompresi ringan dan sedang, perubahan kualitas hampir tidak terlihat. Kompresi kuat mungkin mengurangi resolusi gambar namun tetap mempertahankan keterbacaan teks.' },
                { id: 9, category: 'kompres', question: 'Apakah ada batas ukuran file?', answer: 'Untuk pengguna gratis, batas ukuran file adalah {{ $adminMaxFileSizeMB }}MB. Pengguna premium mendapatkan kuota hingga {{ $adminMaxFileSizePremiumMB }}MB. Jika Anda perlu mengompres file yang lebih besar, coba tingkatkan ke akun premium atau kompres terlebih dahulu dengan tingkat rendah.' },

                // ── Gabung PDF ──
                { id: 10, category: 'gabung', question: 'Bagaimana cara menggabungkan beberapa PDF?', answer: 'Buka halaman Gabung PDF, unggah dua atau lebih file PDF, atur urutan file sesuai keinginan (seret untuk mengubah posisi), lalu klik "Gabung". File hasil gabungan akan langsung terunduh.' },
                { id: 11, category: 'gabung', question: 'Berapa maksimal file yang bisa digabung?', answer: 'Anda dapat menggabungkan hingga 10 file PDF dalam satu sesi untuk pengguna gratis. Pengguna terdaftar dapat menggabungkan hingga 20 file.' },
                { id: 12, category: 'gabung', question: 'Bisakah menggabungkan PDF dengan format lain?', answer: 'Saat ini alat Gabung PDF hanya mendukung file PDF. Jika Anda ingin menggabungkan JPG atau gambar, gunakan alat Konversi untuk mengubahnya ke PDF terlebih dahulu, lalu gabungkan.' },

                // ── Pisah PDF ──
                { id: 13, category: 'pisah', question: 'Bagaimana cara memisahkan halaman PDF?', answer: 'Buka halaman Pisah PDF, unggah file PDF, lalu pilih mode pemisahan: pisah per halaman, pisah berdasarkan rentang halaman, atau ekstrak halaman tertentu. Klik "Pisah" dan file hasil akan siap diunduh.' },
                { id: 14, category: 'pisah', question: 'Apa itu mode "Pisah Per Halaman"?', answer: 'Mode ini akan memisahkan setiap halaman dalam PDF menjadi file PDF terpisah. Misalnya PDF 5 halaman akan menghasilkan 5 file PDF masing-masing 1 halaman.' },
                { id: 15, category: 'pisah', question: 'Bisakah mengekstrak halaman tertentu saja?', answer: 'Ya! Pilih opsi "Ekstrak Halaman" dan masukkan nomor halaman yang ingin diekstrak. Contoh: 1,3,5-7 untuk mengekstrak halaman 1, 3, 5, 6, dan 7.' },

                // ── Konversi Format ──
                { id: 16, category: 'konversi', question: 'Format apa saja yang didukung untuk konversi?', answer: 'Kami mendukung konversi JPG ke PDF, PNG ke PDF, Word ke PDF, Excel ke PDF, PDF ke JPG, dan masih banyak lagi. Setiap alat konversi memiliki halaman khusus dengan petunjuk penggunaannya.' },
                { id: 17, category: 'konversi', question: 'Apakah hasil konversi Word ke PDF rapi?', answer: 'Ya, mesin konversi kami mempertahankan format asli dokumen Word termasuk font, tabel, gambar, dan tata letak halaman dengan akurasi tinggi.' },
                { id: 18, category: 'konversi', question: 'Bisakah mengubah PDF kembali ke Word?', answer: 'Saat ini alat PDF ke Word sedang dalam pengembangan. Nantikan update terbaru dari kami!' },

                // ── Edit PDF ──
                { id: 19, category: 'edit', question: 'Bagaimana cara memutar halaman PDF?', answer: 'Buka halaman Putar PDF, unggah file, pilih halaman yang ingin diputar, lalu pilih arah rotasi (90°, 180°, 270°). File hasil akan langsung terunduh.' },
                { id: 20, category: 'edit', question: 'Apakah bisa menambahkan watermark di PDF?', answer: 'Ya, alat Watermark PDF memungkinkan Anda menambahkan teks atau watermark ke setiap halaman PDF. Anda bisa mengatur posisi, opacity, ukuran, dan rotasi watermark.' },
                { id: 21, category: 'edit', question: 'Apakah ada alat untuk menandatangani PDF?', answer: 'Fitur tanda tangan digital sedang dalam tahap pengembangan. Kami akan mengumumkannya segera di halaman pembaruan fitur.' },

                // ── Keamanan & Privasi ──
                { id: 22, category: 'keamanan', question: 'Apakah file saya aman di VizzioDocs?', answer: 'Keamanan adalah prioritas utama kami. Semua file diproses menggunakan koneksi terenkripsi (HTTPS/SSL). File Anda secara otomatis dihapus dari server kami dalam waktu 1 jam setelah diproses. Kami tidak pernah menyimpan atau membagikan dokumen Anda.' },
                { id: 23, category: 'keamanan', question: 'Apakah Anda melihat atau menyimpan file saya?', answer: 'Tidak. Sistem kami memproses file secara otomatis tanpa campur tangan manusia. File dihapus secara permanen dari server setelah 1 jam. Kami tidak mengakses, membaca, atau menyimpan konten file Anda.' },
                { id: 24, category: 'keamanan', question: 'Sertifikat keamanan apa yang dimiliki VizzioDocs?', answer: 'Seluruh situs diamankan dengan SSL/TLS 256-bit encryption. Server kami menggunakan firewall dan protokol keamanan standar industri. Kami juga secara rutin melakukan audit keamanan.' },

                // ── Akun & Tagihan ──
                { id: 25, category: 'akun', question: 'Bagaimana cara mendaftar akun?', answer: 'Klik tombol "Daftar" di pojok kanan atas halaman. Isi form pendaftaran dengan nama, email, password, dan informasi lainnya. Setelah mendaftar, Anda bisa langsung login dan menikmati fitur tambahan.' },
                { id: 26, category: 'akun', question: 'Apa keuntungan mendaftar akun?', answer: 'Dengan akun terdaftar, Anda mendapatkan: batas ukuran file lebih besar, riwayat file tersimpan, akses ke lebih banyak alat, dan prioritas pemrosesan yang lebih cepat.' },
                { id: 27, category: 'akun', question: 'Bagaimana cara menghapus akun?', answer: 'Untuk menghapus akun, silakan hubungi tim support kami melalui email support@vizziocs.com. Kami akan memproses penghapusan akun dan data Anda dalam waktu 3x24 jam.' },
                { id: 28, category: 'akun', question: 'Lupa password?', answer: 'Klik "Lupa Password?" pada halaman login. Masukkan email Anda dan kami akan mengirim tautan reset password. Ikuti petunjuk di email untuk membuat password baru.' },
            ],

            popularSearches: ['cara kompres pdf', 'gabung pdf', 'batas ukuran file', 'apakah aman', 'daftar akun'],

            get filteredFaqs() {
                const query = this.searchQuery.toLowerCase().trim();
                if (query) {
                    return this.faqs.filter(faq =>
                        faq.question.toLowerCase().includes(query) ||
                        faq.answer.toLowerCase().includes(query)
                    );
                }
                return this.faqs.filter(faq => faq.category === this.activeCategory);
            },

            get currentCategoryIcon() {
                const cat = this.categories.find(c => c.id === this.activeCategory);
                return cat ? cat.icon : '';
            },

            get currentCategoryName() {
                const cat = this.categories.find(c => c.id === this.activeCategory);
                return cat ? cat.name : '';
            },

            get currentCategoryDesc() {
                const cat = this.categories.find(c => c.id === this.activeCategory);
                return cat ? cat.desc : '';
            },

            get currentCategoryBg() {
                const cat = this.categories.find(c => c.id === this.activeCategory);
                return cat ? cat.bg : 'bg-slate-100 text-slate-600';
            },

            toggleFaq(id) {
                this.openFaq = this.openFaq === id ? null : id;
            },

            scrollToFaqs() {
                this.openFaq = null;
                this.$nextTick(() => {
                    const el = document.getElementById('faq-section');
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            },

            highlightMatch(text) {
                const query = this.searchQuery.toLowerCase().trim();
                if (!query) return text;
                const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                return text.replace(regex, '<span class="search-highlight font-semibold">$1</span>');
            },

            init() {
                // Smooth scroll for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        const href = this.getAttribute('href');
                        if (href === '#') return;
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });
            }
        };
    }
</script>
@endpush
@endsection
