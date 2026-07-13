@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Crop Halaman PDF — VizzioDocs')

@section('content')
<!-- Premium Background Wrapper -->
<div x-data="sidebarManager()" class="relative overflow-hidden">
    <!-- Light Mode Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50"></div>
    <!-- Animated Gradient Orbs Background -->
    <div class="fixed top-20 left-0 w-96 h-96 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob pointer-events-none"></div>
    <div class="fixed top-40 right-0 w-96 h-96 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000 pointer-events-none"></div>
    <div class="fixed -bottom-20 left-1/2 transform -translate-x-1/2 w-96 h-96 bg-gradient-to-br from-pink-400 to-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-blob animation-delay-4000 pointer-events-none"></div>
    
    <!-- Decorative Grid Pattern -->
    <div class="fixed inset-0 bg-[linear-gradient(to_right,#8882_1px,transparent_1px),linear-gradient(to_bottom,#8882_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <!-- ===== ADMIN SIDEBAR (FIXED LEFT) ===== -->
    <aside id="admin-sidebar"
        class="admin-sidebar"
        :class="sidebarOpen ? 'open' : 'collapsed'">
        
        <!-- Sidebar Header with Logo (click to toggle) -->
        <div class="admin-sidebar-header">
            <div @click.stop="toggle()" class="flex items-center gap-3 cursor-pointer select-none">
                <div class="admin-sidebar-logo">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="admin-sidebar-brand">
                    <p>PDF Tools</p>
                </div>
            </div>

        </div>

        <!-- Inner Scrollable Area -->
        <div class="admin-sidebar-inner">
            <!-- Tool Name Badge -->
            <div class="px-3 mt-4 mb-2">
                <div class="admin-sidebar-tool-badge">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="admin-sidebar-tool-label">Crop PDF</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="admin-sidebar-nav">
                <span class="admin-sidebar-nav-section-label">Menu Alat</span>

                <a href="{{ route('compress.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Kompres PDF</span>
                    <span class="ml-auto text-[10px] font-bold text-indigo-400/60 bg-indigo-400/10 px-2 py-0.5 rounded-full">Pro</span>
                </a>
                <a href="{{ route('merge.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Gabung PDF</span>
                </a>
                <a href="{{ route('split.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 8a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zm12 0a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Pisah PDF</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Convert</span>

                <a href="{{ route('jpg-to-pdf.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">JPG ke PDF</span>
                </a>
                <a href="{{ route('png-to-pdf.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PNG ke PDF</span>
                </a>
                <a href="{{ route('pdf-to-jpg.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke JPG</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Lainnya</span>

                <a href="{{ route('rotate.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Putar PDF</span>
                </a>
                <a href="{{ route('word-to-pdf.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Word ke PDF</span>
                </a>
                <a href="{{ route('pdf-to-word.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke Word</span>
                </a>
                <a href="{{ route('excel-to-pdf.index') }}" class="admin-sidebar-nav-item">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Excel ke PDF</span>
                </a>
                <a href="{{ route('crop.index') }}" class="admin-sidebar-nav-item active">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Crop PDF</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="admin-sidebar-footer">
                <div class="admin-sidebar-file-info" id="sidebar-file-info">
                    <div class="flex items-center gap-2 text-[11px] text-indigo-200/70">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="truncate">Belum ada file dipilih</span>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===== SIDEBAR OVERLAY (Mobile/Tablet) ===== -->
    <div class="admin-sidebar-overlay"
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    <!-- ===== MAIN CONTENT AREA ===== -->
    <div id="admin-main" class="admin-main" :class="sidebarOpen ? '' : 'collapsed'">
        <!-- Mobile/Tablet Toolbar -->
        <div class="admin-mobile-toolbar">
            <button @click="toggle()" class="sidebar-toggle-mobile" aria-label="Buka/tutup sidebar">
                <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <span class="admin-mobile-title">Crop PDF</span>
            <button class="admin-mobile-convert-btn" @click="window.submitCrop?.()">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span>Konversi</span>
            </button>
        </div>
        <div class="relative min-h-screen py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
            <!-- Crop Application Area -->
            <div class="w-full flex flex-col h-full">
                @include('components.pdf-upload')
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/pdfjs/pdf.min.js') }}"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ asset('js/pdfjs/pdf.worker.min.js') }}';
</script>
@endpush
@endsection
