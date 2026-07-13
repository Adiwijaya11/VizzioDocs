@props([
    'title',
    'description',
    'action',
    'tool',
    'accept' => '.pdf',
    'mimes' => 'PDF',
    'multiple' => false,
    'hideDefaultUpload' => false,
    'optionsFirst' => false
])

<div x-data="sidebarManager()" @keydown.window.escape="close()" class="relative {{ $tool === 'crop' ? 'crop-tool-layout' : '' }}"><!-- Premium Background Wrapper -->
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
            <div @click.stop="toggle()" class="flex items-center gap-3 cursor-pointer select-none" :title="sidebarOpen ? 'Sembunyikan menu' : 'Buka menu'">
                <div class="admin-sidebar-logo">
                    {{-- VizzioDocs Document Icon --}}
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4a2 2 0 012-2h6l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" fill="white" stroke="rgba(255,255,255,0.2)" stroke-width="0.5"/>
                        <path d="M12 2v6h6" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.2" stroke-linejoin="round"/>
                        <rect x="7" y="9.5" width="8" height="1.5" rx="0.75" fill="rgba(255,255,255,0.7)"/>
                        <rect x="7" y="12.5" width="5.5" height="1.5" rx="0.75" fill="rgba(255,255,255,0.5)"/>
                        <rect x="7" y="15.5" width="7" height="1.5" rx="0.75" fill="rgba(255,255,255,0.5)"/>
                        <circle cx="17.5" cy="16.5" r="1.8" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="0.6"/>
                        <circle cx="17.5" cy="16.5" r="0.7" fill="white"/>
                    </svg>
                </div>
                <div class="admin-sidebar-brand">
                    <h3>Vizzio<span style="background:linear-gradient(135deg,#6366f1,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Docs</span></h3>
                </div>
            </div>
            <!-- Mobile Close Button -->
            <button @click.stop="close()" class="sidebar-mobile-close ml-auto w-8 h-8 rounded-lg flex items-center justify-center text-white/50 hover:text-white hover:bg-white/10 transition-all duration-200" aria-label="Tutup sidebar">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Inner Scrollable Area -->
        <div id="admin-sidebar-inner" class="admin-sidebar-inner" x-init="if (localStorage.getItem('vizzio_sidebar_scroll')) $el.scrollTop = parseInt(localStorage.getItem('vizzio_sidebar_scroll'))">
            <!-- Tool Name Badge (Collapsed shows icon only, Open shows text) -->
            <div class="px-3 mt-4 mb-2">
                <div class="admin-sidebar-tool-badge">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="admin-sidebar-tool-label">{{ ucwords(str_replace(['-', 'pdf', 'jpg', 'png', 'docx', 'xlsx'], [' ', 'PDF', 'JPG', 'PNG', 'DOCX', 'XLSX'], $tool)) }}</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="admin-sidebar-nav">
                <span class="admin-sidebar-nav-section-label">Menu Alat</span>

                <a href="{{ route('compress.index') }}" class="admin-sidebar-nav-item {{ $tool === 'compress' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Kompres PDF</span>
                    <span class="ml-auto text-[10px] font-bold text-indigo-400/60 bg-indigo-400/10 px-2 py-0.5 rounded-full">Pro</span>
                </a>
                <a href="{{ route('merge.index') }}" class="admin-sidebar-nav-item {{ $tool === 'merge' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Gabung PDF</span>
                </a>
                <a href="{{ route('split.index') }}" class="admin-sidebar-nav-item {{ $tool === 'split' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 8a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zm12 0a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Pisah PDF</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Konversi</span>

                <a href="{{ route('jpg-to-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'jpg-to-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">JPG ke PDF</span>
                </a>
                <a href="{{ route('png-to-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'png-to-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PNG ke PDF</span>
                </a>
                <a href="{{ route('pdf-to-jpg.index') }}" class="admin-sidebar-nav-item {{ $tool === 'pdf-to-jpg' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke JPG</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Lainnya</span>

                <a href="{{ route('rotate.index') }}" class="admin-sidebar-nav-item {{ $tool === 'rotate' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Putar PDF</span>
                </a>
                <a href="{{ route('word-to-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'word-to-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Word ke PDF</span>
                </a>
                <a href="{{ route('pdf-to-word.index') }}" class="admin-sidebar-nav-item {{ $tool === 'pdf-to-word' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke Word</span>
                </a>
                <a href="{{ route('excel-to-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'excel-to-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Excel ke PDF</span>
                </a>
                <a href="{{ route('crop.index') }}" class="admin-sidebar-nav-item {{ $tool === 'crop' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Crop PDF</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Ekstrak &amp; Konversi Teks</span>

                <a href="{{ route('pdf-to-txt.index') }}" class="admin-sidebar-nav-item {{ $tool === 'pdf-to-txt' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke TXT</span>
                </a>
                <a href="{{ route('pdf-to-markdown.index') }}" class="admin-sidebar-nav-item {{ $tool === 'pdf-to-markdown' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke Markdown</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Kelola Halaman</span>

                <a href="{{ route('remove-pages.index') }}" class="admin-sidebar-nav-item {{ $tool === 'remove-pages' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Hapus Halaman</span>
                </a>
                <a href="{{ route('extract-pages.index') }}" class="admin-sidebar-nav-item {{ $tool === 'extract-pages' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Ekstrak Halaman</span>
                </a>
                <a href="{{ route('organize-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'organize-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Atur PDF</span>
                </a>
                <a href="{{ route('page-numbers.index') }}" class="admin-sidebar-nav-item {{ $tool === 'page-numbers' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Nomor Halaman</span>
                </a>
                <a href="{{ route('watermark-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'watermark-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Watermark</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Keamanan</span>

                <a href="{{ route('protect-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'protect-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Proteksi PDF</span>
                </a>
                <a href="{{ route('unlock-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'unlock-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Buka Kunci PDF</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Konversi Lanjutan</span>

                <a href="{{ route('pdf-to-excel.index') }}" class="admin-sidebar-nav-item {{ $tool === 'pdf-to-excel' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke Excel</span>
                </a>
                <a href="{{ route('html-to-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'html-to-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    <span class="admin-sidebar-nav-text">HTML ke PDF</span>
                </a>
                <a href="{{ route('scan-to-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'scan-to-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Scan ke PDF</span>
                </a>
                <a href="{{ route('pdf-to-pptx.index') }}" class="admin-sidebar-nav-item {{ $tool === 'pdf-to-pptx' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke PPT</span>
                </a>
                <a href="{{ route('pptx-to-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'pptx-to-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PPT ke PDF</span>
                </a>
                <a href="{{ route('pdf-to-pdfa.index') }}" class="admin-sidebar-nav-item {{ $tool === 'pdf-to-pdfa' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">PDF ke PDF/A</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <span class="admin-sidebar-nav-section-label">Perbaikan</span>

                <a href="{{ route('optimize-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'optimize-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Optimasi PDF</span>
                </a>
                <a href="{{ route('repair-pdf.index') }}" class="admin-sidebar-nav-item {{ $tool === 'repair-pdf' ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                    <span class="admin-sidebar-nav-text">Perbaiki PDF</span>
                </a>

                <div class="admin-sidebar-divider"></div>
                <button type="button" @click="toggle()" class="admin-sidebar-nav-item w-full cursor-pointer flex items-center gap-3 text-slate-500 hover:text-indigo-600 transition-all duration-200 mt-2" style="background:none;border:none;text-align:left;outline:none;" :title="sidebarOpen ? 'Sembunyikan menu' : 'Buka menu'">
                    <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="sidebarOpen ? 'rotate-0' : 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    <span class="admin-sidebar-nav-text" x-show="sidebarOpen">Sembunyikan Menu</span>
                </button>
            </nav>

            <!-- Sidebar Footer: Submit Button & File Info -->
            <div class="admin-sidebar-footer">
                <!-- File Info (compact) -->
                <div class="admin-sidebar-file-info" id="sidebar-file-info">
                    @if($tool === 'crop')
                    <!-- When file is selected (simple inline style matching other tools) -->
                    <div x-show="$store.crop.filename"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] font-medium">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate text-slate-400 max-w-[120px]" x-text="$store.crop.filename">—</span>
                        <button @click="window.dispatchEvent(new CustomEvent('clear-crop-file'))"
                            class="inline-flex items-center gap-1 text-rose-400 hover:text-rose-600 font-bold transition-colors duration-150">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                    <!-- When no file is selected -->
                    <div x-show="!$store.crop.filename"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="flex items-center gap-2 text-[11px] text-slate-400 font-medium">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="truncate">Belum ada file dipilih</span>
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-[11px] text-slate-400 font-medium">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="truncate" x-text="$store.crop.filename || 'Belum ada file dipilih'">Belum ada file dipilih</span>
                    </div>
                    @endif
                </div>

                <!-- Submit Button (links to form via form="tool-form") -->
                <button type="submit" id="submit-btn" form="tool-form"
                    {{ $tool === 'crop' ? ':disabled="!$store.crop.filename"' : 'disabled' }}
                    class="admin-sidebar-submit-btn disabled:opacity-40 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span id="submit-text" class="admin-sidebar-submit-text">Mulai Konversi</span>
                </button>
            </div>
        </div>
            </div>
        </div>
        {{-- Script for handling locked sidebar items --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lockedPaths = @json($lockedPaths ?? []);
                const sidebarNavItems = document.querySelectorAll('.admin-sidebar-nav-item');
                const lockSvg = `
                    <svg class="w-3.5 h-3.5 text-red-400 ml-auto flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                `;

                sidebarNavItems.forEach(item => {
                    const href = item.getAttribute('href');
                    const path = new URL(href).pathname;

                    if (lockedPaths.includes(path)) {
                        // Add class for styling (e.g., grayscale, cursor not-allowed)
                        item.classList.add('locked-tool');
                        // Append lock icon
                        item.insertAdjacentHTML('beforeend', lockSvg);
                        // Prevent navigation and show premium popup
                        item.addEventListener('click', function(event) {
                            event.preventDefault();
                            // Get tool name from nav text
                            const toolSpan = this.querySelector('.admin-sidebar-nav-text');
                            const toolName = toolSpan ? toolSpan.textContent.trim() : 'Alat ini';
                            // Call Alpine store to show modal
                            Alpine.store('lockModal').show(toolName, path);
                        });
                    }
                });
            });
        </script>
    </aside>

    <!-- ===== CROP RIGHT SIDEBAR TELEPORT TARGET (renders at layout level, outside upload area) ===== -->
    <div id="crop-right-sidebar-target"></div>

    <!-- ===== MOBILE SIDEBAR OVERLAY ===== -->
    <div @click="close()"
        x-show="sidebarOpen && isMobile"
        x-cloak
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-[var(--vd-nav-height,70px)] bottom-0 left-0 right-0 bg-black/50 backdrop-blur-sm z-[55] lg:hidden"></div>

    <!-- ===== MAIN CONTENT AREA ===== -->
    <div id="admin-main" class="admin-main" :class="sidebarOpen ? '' : 'collapsed'">
        <div class="relative py-6 sm:py-10 px-4 sm:px-6 lg:px-8 mx-auto transition-all duration-[350ms] ease-in-out"
             :class="sidebarOpen ? 'max-w-6xl' : 'max-w-8xl'">
            <!-- Tool form and content here -->

            <form id="tool-form" action="{{ $action }}" method="POST" enctype="multipart/form-data" data-tool="{{ $tool }}">
                @csrf

                <!-- ===== MAIN CONTENT ===== -->
                <div class="relative bg-white/70 backdrop-blur-xl rounded-3xl border border-white/60 shadow-2xl overflow-hidden">
                    <!-- Gradient Glow Effect -->
                    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full filter blur-3xl opacity-20 pointer-events-none"></div>
                    
                    <!-- Header -->
                    <div class="relative p-6 sm:p-8 border-b border-white/40 text-center">
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-indigo-500/30 mb-4">
                            <svg class="w-4 h-4 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Alat Premium
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight mb-3">
                            <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                                {{ $title }}
                            </span>
                        </h1>
                        <p class="text-slate-500 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">{{ $description }}</p>
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        @if($hideDefaultUpload)
                            {{ $slot }}
                        @else
                        <!-- Options Slot (if optionsFirst, render BEFORE upload) -->
                        @if (isset($optionsSlot) && $optionsFirst)
                        <div class="bg-gradient-to-br from-indigo-50/30 to-purple-50/30 backdrop-blur-sm rounded-2xl p-6 border border-indigo-100/30">
                            <div class="flex items-center space-x-2 mb-4">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Pengaturan</span>
                            </div>
                            <div class="space-y-4">
                                {{ $optionsSlot }}
                            </div>
                        </div>
                        @endif

                        <!-- Upload Dropzone (hidden if hideDefaultUpload) -->
                        @unless($hideDefaultUpload)
                        <div id="dropzone" class="relative group cursor-pointer transition-all duration-300">
                            <input type="file" id="file-input" name="{{ $multiple ? 'files[]' : 'file' }}" class="hidden" accept="{{ $accept }}" {{ $multiple ? 'multiple' : '' }}>
                            
                            <!-- Main Dropzone Card -->
                            <div class="relative overflow-hidden rounded-2xl bg-white/80 backdrop-blur-sm border-2 border-dashed border-indigo-200/60 
                                transition-all duration-500 ease-out
                                group-hover:border-indigo-400/80 group-hover:shadow-2xl group-hover:shadow-indigo-500/20
                                group-active:scale-[0.99]"
                                :class="{ 'border-indigo-500 shadow-2xl shadow-indigo-500/30 bg-indigo-50/60 scale-[1.01]': isDragging }"
                                x-data="{ isDragging: false }"
                                @dragover.prevent="isDragging = true"
                                @dragleave="isDragging = false"
                                @drop.prevent="isDragging = false">
                                
                                <!-- Animated gradient glow on hover -->
                                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 
                                    group-hover:from-indigo-500/10 group-hover:via-purple-500/10 group-hover:to-pink-500/10 
                                    blur-xl transition-all duration-700 -z-10"></div>

                                <div class="relative p-8 sm:p-10 text-center">
                                    <!-- Upload Icon with animated ring -->
                                    <div class="relative mx-auto w-20 h-20 sm:w-24 sm:h-24 mb-6">
                                        <!-- Pulse ring on hover -->
                                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-indigo-400 to-purple-500 
                                            opacity-0 group-hover:opacity-30 blur-xl transition-all duration-500 
                                            group-hover:scale-150"></div>
                                        <!-- Icon container -->
                                        <div class="relative flex items-center justify-center w-full h-full 
                                            bg-gradient-to-br from-indigo-50 to-purple-50 
                                            rounded-2xl shadow-lg border border-indigo-100/50
                                            group-hover:scale-110 group-hover:rotate-3 
                                            transition-all duration-500 ease-out
                                            group-hover:shadow-indigo-500/30">
                                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-indigo-600 
                                                group-hover:animate-bounce transition-all duration-300" 
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                        <!-- Decorative dots -->
                                        <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-indigo-400 
                                            opacity-0 group-hover:opacity-100 transition-all duration-500 
                                            group-hover:animate-pulse"></div>
                                        <div class="absolute -bottom-1 -left-1 w-2 h-2 rounded-full bg-purple-400 
                                            opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100
                                            group-hover:animate-pulse"></div>
                                    </div>
                                    
                                    <!-- Text -->
                                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2 
                                        group-hover:text-indigo-700 transition-colors duration-300">
                                        Tarik & Lepas File Anda
                                    </h3>
                                    <p class="text-slate-400 text-sm sm:text-base mb-5 transition-colors duration-300
                                        group-hover:text-slate-500">
                                        atau <span class="text-indigo-600 font-semibold group-hover:text-indigo-800 transition-colors">klik untuk memilih</span>
                                    </p>
                                    
                                    <!-- Format Badge -->
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                                        bg-white/90 backdrop-blur-sm border border-indigo-100 
                                        shadow-md group-hover:shadow-lg group-hover:border-indigo-200 
                                        transition-all duration-300">
                                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-slate-700 text-sm font-bold">Format: {{ $mimes }}</span>
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 
                                            group-hover:animate-pulse"></span>
                                    </div>

                                    <!-- Drag indicator (hidden by default, shown during drag) -->
                                    <div x-show="isDragging" 
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="mt-4 text-xs font-semibold text-indigo-600 
                                            bg-indigo-50/80 py-1.5 px-3 rounded-full inline-block">
                                        Lepaskan file di sini ✨
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File List Preview (hidden initially) -->
                        <div id="file-list-container" class="hidden space-y-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">File Terpilih</p>
                            </div>
                            <div id="files-list" class="space-y-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                                <!-- Javascript will render item list here -->
                            </div>
                        </div>
                        @endunless

                        <!-- ===== MOBILE SUBMIT BUTTON (visible only on mobile/tablet, inside upload area) ===== -->
                        <div id="mobile-submit-wrapper" class="lg:hidden">
                            <button type="submit" id="mobile-submit-btn" form="tool-form"
                                {{ $tool === 'crop' ? ':disabled="!$store.crop.filename"' : 'disabled' }}
                                class="mobile-submit-btn disabled:opacity-40 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span id="mobile-submit-text">Mulai Konversi</span>
                            </button>
                        </div>

                        <!-- Tool Options Slot (normal position, after upload) -->
                        @if (isset($optionsSlot) && !$optionsFirst)
                        <div class="bg-gradient-to-br from-indigo-50/30 to-purple-50/30 backdrop-blur-sm rounded-2xl p-6 border border-indigo-100/30">
                            <div class="flex items-center space-x-2 mb-4">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Pengaturan</span>
                            </div>
                            <div class="space-y-4">
                                {{ $optionsSlot }}
                            </div>
                        </div>
                        @endif

                        <!-- Processing State -->
                        <div id="processing-section" class="hidden bg-gradient-to-br from-indigo-50/50 to-purple-50/50 backdrop-blur-sm rounded-2xl p-8 border border-indigo-100/50 text-center space-y-5 animate-fade-in-scale">
                            <div class="flex flex-col items-center space-y-4">
                                <div class="relative w-14 h-14">
                                    <div class="absolute inset-0 border-4 border-indigo-200 rounded-full"></div>
                                    <div class="absolute inset-0 border-4 border-transparent border-t-indigo-600 border-r-purple-600 rounded-full animate-spin"></div>
                                </div>
                                <span id="progress-status" class="text-base font-bold text-slate-700">Sedang memproses berkas...</span>
                            </div>
                            <div class="relative w-full bg-slate-200/60 rounded-full h-2.5 overflow-hidden backdrop-blur-sm">
                                <div id="progress-bar" class="progress-fill h-full rounded-full transition-all duration-300 shadow-lg shadow-indigo-500/50" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Success State -->
                        <div id="success-section" class="hidden bg-gradient-to-br from-emerald-50/50 to-teal-50/50 backdrop-blur-sm rounded-2xl p-8 border border-emerald-100/50 text-center space-y-6 animate-fade-in-scale">
                            <div class="flex flex-col items-center">
                                <div class="relative w-16 h-16 mb-3">
                                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full blur-xl opacity-50"></div>
                                    <div class="relative w-full h-full rounded-full bg-white border-4 border-emerald-500 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <h2 class="text-2xl font-black bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Berhasil!</h2>
                                <p class="text-slate-500 text-sm">File Anda telah siap didownload</p>
                            </div>

                            <!-- Dynamic Preview Slot -->
                            <div id="result-preview"></div>

                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                <a href="" id="view-btn" target="_blank" class="group relative flex-grow w-full sm:w-auto overflow-hidden rounded-xl shadow-xl shadow-indigo-500/30 hover:shadow-indigo-600/50 transition-all duration-300 hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600"></div>
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <span class="relative flex items-center justify-center space-x-2 py-3.5 px-5 font-bold text-white text-sm">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Pratinjau</span>
                                    </span>
                                </a>
                                
                                <a href="" id="download-btn" class="group relative flex-grow w-full sm:w-auto overflow-hidden rounded-xl shadow-xl shadow-emerald-500/30 hover:shadow-emerald-600/50 transition-all duration-300 hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <span class="relative flex items-center justify-center space-x-2 py-3.5 px-5 font-bold text-white text-sm">
                                        <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <span>Unduh</span>
                                    </span>
                                </a>
                                
                                <a href="{{ url()->current() }}" class="w-full sm:w-auto px-5 py-3.5 rounded-xl font-bold text-slate-600 bg-white/80 backdrop-blur-sm border-2 border-slate-200 hover:border-indigo-300 hover:text-indigo-600 hover:bg-white shadow-lg hover:shadow-xl transition-all duration-300 text-sm">
                                    Konversi Lagi
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </form>
    </div>
    </div>
    
    <!-- Mobile Sidebar Toggle (Floating) - teleported to body to bypass parent stacking context -->
    <template x-teleport="body">
        <button @click="toggle()" type="button"
            x-show="!sidebarOpen"
            style="display: none"
            class="lg:hidden fixed bottom-6 right-6 z-[70] w-14 h-14 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white shadow-2xl shadow-indigo-500/40 hover:shadow-indigo-600/60 hover:scale-105 active:scale-95 transition-all duration-300 flex items-center justify-center" aria-label="Buka menu alat">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </template>

    {{-- ===== LOCKED TOOL VALIDATION POPUP (PREMIUM MODAL) ===== --}}
    <template x-teleport="body">
        <div x-show="$store.lockModal.open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
             @click="$store.lockModal.close()"
             x-cloak>
            {{-- Backdrop with blur --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>
            
            {{-- Modal Card --}}
            <div @click.stop
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative w-full max-w-md rounded-3xl bg-white shadow-2xl shadow-indigo-500/20 overflow-hidden">
                
                {{-- Gradient top bar --}}
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                
                {{-- Decorative glow --}}
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-indigo-400/20 to-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                {{-- Content --}}
                <div class="relative p-6 sm:p-8 text-center">
                    {{-- Lock Icon with animated ring --}}
                    <div class="relative mx-auto w-20 h-20 mb-6">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 blur-xl opacity-30 animate-pulse"></div>
                        <div class="relative w-full h-full rounded-full bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200/60 flex items-center justify-center">
                            <svg class="w-10 h-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-2xl font-black text-slate-800 mb-2">
                        Alat Terkunci 🔒
                    </h3>
                    
                    {{-- Tool Name Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100 mb-4">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                        <span class="text-sm font-bold text-indigo-700" x-text="$store.lockModal.toolName"></span>
                    </div>

                    {{-- Description --}}
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">
                        Maaf, alat <strong class="text-slate-700" x-text="$store.lockModal.toolName"></strong> sedang dalam perbaikan. Silakan coba lagi nanti atau gunakan alat lainnya yang tersedia. 🙏
                    </p>

                    {{-- Action Button --}}
                    <button @click="$store.lockModal.close()"
                        class="w-full py-3.5 px-6 rounded-2xl font-bold text-white text-sm
                               bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600
                               hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700
                               shadow-lg shadow-indigo-500/30 hover:shadow-indigo-600/50
                               active:scale-[0.98] transition-all duration-300">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Mengerti, Tutup
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>