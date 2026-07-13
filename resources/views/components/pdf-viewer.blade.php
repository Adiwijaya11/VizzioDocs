<div x-data="pdfViewer()" x-init="initPdfViewer(Alpine.store('crop').pdfUrl)" class="relative w-full">
    <!-- Main PDF Canvas Area -->
    <div class="relative flex flex-col bg-gradient-to-br from-slate-50/80 to-white/80 rounded-3xl overflow-hidden border border-slate-200/60 shadow-inner min-h-[450px] lg:min-h-[600px] w-full max-w-4xl mx-auto">
        <!-- Canvas Container -->
        <div id="pdf-canvas-container" class="relative flex-1 overflow-auto"
            @mousedown="startCrop"
            @mousemove="doCrop"
            @mouseup="endCrop"
            @mouseleave="endCrop"
            @touchstart="startCrop"
            @touchmove="doCrop"
            @touchend="endCrop">
            <div class="min-h-full flex items-start justify-center p-4">
                <!-- Coordinate Wrapper: inline-block ensures tight wrap around canvas for proper overlay alignment -->
                <div id="crop-wrapper" class="relative inline-block leading-[0]">
                    <canvas id="pdf-canvas" class="shadow-2xl rounded-lg" style="display: block; max-width: none; width: auto; height: auto;"></canvas>
 
                    <!-- Dark Overlay (outside crop box) -->
                    <div id="crop-overlay" class="absolute inset-0 pointer-events-none"
                        x-show="cssBox.width > 0 && cssBox.height > 0">
                        <div class="absolute inset-0 bg-black/40 transition-opacity duration-200"
                            :style="cssBox.width > 0 && cssBox.height > 0 ? {
                                clipPath: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%, 0% 0%, ' + cssBox.x + 'px ' + cssBox.y + 'px, ' + cssBox.x + 'px ' + (cssBox.y + cssBox.height) + 'px, ' + (cssBox.x + cssBox.width) + 'px ' + (cssBox.y + cssBox.height) + 'px, ' + (cssBox.x + cssBox.width) + 'px ' + cssBox.y + 'px, ' + cssBox.x + 'px ' + cssBox.y + 'px)'
                            } : {}">
                        </div>
                    </div>
 
                    <!-- Crop Box Overlay (positioned in CSS pixel space) -->
                    <div id="crop-box" class="absolute border-2 border-indigo-400 rounded-sm shadow-lg"
                        :style="{
                            left: cssBox.x + 'px',
                            top: cssBox.y + 'px',
                            width: cssBox.width + 'px',
                            height: cssBox.height + 'px',
                            display: cssBox.width > 0 && cssBox.height > 0 ? 'block' : 'none'
                        }">
                        <!-- Drag Handle (center area) -->
                        <div class="absolute inset-0 cursor-move" @mousedown.stop="startDragCrop($event)" @touchstart.stop="startDragCrop($event)"></div>
                        <!-- Resize Handles -->
                        <div class="resize-handle top-left" @mousedown.stop="startResize($event, 'tl')" @touchstart.stop="startResize($event, 'tl')"></div>
                        <div class="resize-handle top-right" @mousedown.stop="startResize($event, 'tr')" @touchstart.stop="startResize($event, 'tr')"></div>
                        <div class="resize-handle bottom-left" @mousedown.stop="startResize($event, 'bl')" @touchstart.stop="startResize($event, 'bl')"></div>
                        <div class="resize-handle bottom-right" @mousedown.stop="startResize($event, 'br')" @touchstart.stop="startResize($event, 'br')"></div>
                        <!-- Dimension badge (show crop size in PDF points) -->
                        <div class="absolute -top-9 left-1/2 -translate-x-1/2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-[11px] font-bold px-3 py-1 rounded-full whitespace-nowrap shadow-lg shadow-indigo-500/30 border border-indigo-400/30 backdrop-blur-sm"
                            x-show="cropBox.width > 0 && cropBox.height > 0"
                            x-text="Math.round(cropBox.width) + ' × ' + Math.round(cropBox.height) + ' px'">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div x-show="errorMessage" x-text="errorMessage" class="mx-4 mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 text-sm font-medium rounded-xl"></div>
    </div>
    {{-- Toggle button for mobile/tablet to open sidebar --}}
    {{-- Removed: the >> toggle button, sidebar will be inline on mobile --}}

    <!-- ===== CROP RIGHT SIDEBAR (Fixed Right) ===== -->
    {{-- Teleported to tool-template level so it appears outside upload area --}}
    <template x-teleport="#crop-right-sidebar-target">
    <aside id="crop-right-sidebar"
        class="crop-right-sidebar"
        :class="rightOpen ? '' : 'closed'">

        {{-- Top glow accent --}}
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 shadow-lg shadow-purple-500/20"></div>

        {{-- Header --}}
        <div class="crop-right-sidebar-header flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="crop-right-sidebar-logo">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                </div>
                <div class="crop-right-sidebar-brand">
                    <p class="!text-transparent !bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Alat Potong</p>
                    <span>Pemilih Area PDF</span>
                </div>
            </div>
        </div>

        {{-- Inner Scrollable --}}
        <div class="crop-right-sidebar-inner">

            {{-- ── Navigasi Halaman ── --}}
            <span class="crop-right-sidebar-section-label">
                <svg class="w-3 h-3 mr-1.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Navigasi Halaman
            </span>
            <div class="px-3 pb-1">
                <div class="flex items-center justify-between gap-1.5 p-2.5 bg-gradient-to-br from-white to-indigo-50/30 rounded-xl border border-indigo-100/60 shadow-sm hover:shadow-md hover:border-indigo-200/80 transition-all duration-300">
                    <button @click="previousPage" :disabled="Number(currentPage) <= 1"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white text-slate-600 hover:bg-gradient-to-r hover:from-indigo-500 hover:to-purple-600 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-indigo-500/25"
                        title="Halaman Sebelumnya">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="flex items-center justify-center bg-white rounded-xl px-2 py-1.5 border border-slate-100 shadow-inner flex-1 max-w-[90px]">
                        <input type="number" x-model.lazy="currentPage" @change="goToPage" min="1" :max="totalPages"
                            class="w-8 text-center bg-transparent border-none text-sm font-bold text-indigo-700 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none outline-none">
                        <span class="text-xs text-slate-400 font-medium mx-0.5">/</span>
                        <span x-text="totalPages" class="text-xs font-bold text-slate-500 w-5 text-center">—</span>
                    </div>
                    <button @click="nextPage" :disabled="Number(currentPage) >= Number(totalPages)"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white text-slate-600 hover:bg-gradient-to-r hover:from-purple-600 hover:to-indigo-500 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-purple-500/25"
                        title="Halaman Selanjutnya">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="crop-right-sidebar-divider"></div>

            {{-- ── Pembesar (Zoom) ── --}}
            <span class="crop-right-sidebar-section-label">
                <svg class="w-3 h-3 mr-1.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                </svg>
                Pembesar
                <span class="ml-auto font-bold text-xs px-2 py-0.5 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-sm" x-text="Math.round(zoom * 100) + '%'">100%</span>
            </span>
            <div class="px-3 pb-1">
                <div class="flex items-center gap-2 p-2.5 bg-gradient-to-br from-white to-purple-50/30 rounded-xl border border-purple-100/60 shadow-sm hover:shadow-md hover:border-purple-200/80 transition-all duration-300">
                    <button @click="zoomOut" :disabled="zoom <= 0.5"
                        class="flex items-center justify-center w-9 h-9 rounded-lg bg-white text-slate-600 hover:bg-indigo-500 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-indigo-500/25">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                        </svg>
                    </button>
                    <input type="range" x-model="zoom" min="0.5" max="3" step="0.05"
                        class="flex-1 h-1.5 bg-indigo-200 rounded-full appearance-none cursor-pointer accent-indigo-600 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-gradient-to-r [&::-webkit-slider-thumb]:from-indigo-500 [&::-webkit-slider-thumb]:to-purple-600 [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:shadow-indigo-500/30 [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white">
                    <button @click="zoomIn" :disabled="zoom >= 3"
                        class="flex items-center justify-center w-9 h-9 rounded-lg bg-white text-slate-600 hover:bg-purple-500 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-purple-500/25">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="crop-right-sidebar-divider"></div>

            {{-- ── Tindakan Potong ── --}}
            <span class="crop-right-sidebar-section-label">
                <svg class="w-3 h-3 mr-1.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
                Tindakan Potong
            </span>
            <div class="px-3 pb-1 space-y-2">

                {{-- Dimensi crop --}}
                <div class="flex items-center gap-2 p-2.5 bg-gradient-to-br from-white to-amber-50/30 rounded-xl border border-amber-100/60 shadow-sm hover:shadow-md hover:border-amber-200/80 transition-all duration-300">
                    <div class="flex-1 text-center">
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Lebar</p>
                        <p class="text-lg font-black text-indigo-700" x-text="Math.round(cropBox.width) || '—'">—</p>
                    </div>
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="flex-1 text-center">
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Tinggi</p>
                        <p class="text-lg font-black text-indigo-700" x-text="Math.round(cropBox.height) || '—'">—</p>
                    </div>
                </div>

                {{-- Reset - Fixed: call resetCrop() directly --}}
                <button id="crop-right-sidebar-reset-btn" @click="resetCrop()"
                    class="relative w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 hover:from-amber-100 hover:to-orange-100 border border-amber-200/60 hover:border-amber-300/80 transition-all duration-200 text-sm font-bold group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-400/0 via-amber-400/5 to-orange-400/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:-rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="relative">Atur Ulang Pilihan</span>
                </button>

                {{-- Potong halaman ini --}}
                <button @click="cropCurrentPage"
                    class="relative w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 text-sm font-bold shadow-md shadow-indigo-500/25 hover:shadow-lg hover:shadow-indigo-600/35 active:scale-[0.98] group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/15 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <svg class="w-4 h-4 relative" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                    <span class="relative">Potong Halaman Ini</span>
                </button>

                {{-- Potong semua halaman --}}
                <button @click="cropAllPages"
                    class="relative w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white text-slate-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 border border-slate-200 hover:border-purple-300/60 transition-all duration-200 text-sm font-bold shadow-sm hover:shadow-md active:scale-[0.98] group overflow-hidden">
                    <svg class="w-4 h-4 text-purple-500 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Potong Semua Halaman</span>
                </button>
            </div>

            {{-- Error msg --}}
            <div class="mx-3 mt-2 px-3 py-2.5 bg-red-50 border border-red-200 text-red-600 text-xs font-medium rounded-xl"
                x-show="errorMessage" x-text="errorMessage"></div>

            <div class="crop-right-sidebar-divider"></div>

            {{-- ── Info File ── --}}
            <div class="px-3 pb-2">
                <div class="flex items-center gap-2.5 p-2.5 bg-gray-50/80 rounded-xl shadow-sm">
                    <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-lg bg-gradient-to-br from-violet-400 to-purple-500 shadow-md shadow-violet-200/50">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <template x-if="Alpine.store('crop')?.filename">
                            <div>
                                <p class="text-xs font-bold text-slate-800 truncate" x-text="Alpine.store('crop').filename"></p>
                                <p class="text-[10px] font-medium text-violet-500" x-show="totalPages > 0" x-text="totalPages + ' halaman'"></p>
                            </div>
                        </template>
                        <template x-if="!Alpine.store('crop')?.filename">
                            <div>
                                <p class="text-xs font-semibold text-slate-400">Tidak ada file</p>
                                <p class="text-[10px] font-medium text-slate-300">Unggah PDF untuk mulai</p>
                            </div>
                        </template>
                    </div>
                    <button @click="window.dispatchEvent(new CustomEvent('clear-crop-file'))"
                        x-show="Alpine.store('crop')?.filename"
                        class="file-remove-btn"
                        title="Hapus file">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="crop-right-sidebar-divider"></div>

            {{-- ── Download CTA ── --}}
            <div class="px-3 pb-4">
                <button @click="savePdf"
                    class="relative w-full group overflow-hidden rounded-2xl shadow-xl shadow-indigo-500/25 hover:shadow-indigo-600/45 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-purple-700 to-pink-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full bg-gradient-to-r from-transparent via-white/25 to-transparent transition-transform duration-1000"></div>
                    <div class="absolute inset-0 rounded-2xl ring-1 ring-white/20 inset-[1px] pointer-events-none"></div>
                    <span class="relative flex items-center justify-center gap-2 py-3.5 px-6 text-sm font-bold text-white">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Unduh PDF
                    </span>
                </button>
            </div>

        </div>{{-- end inner --}}
    </aside>

    {{-- Floating tab (FAB) to open/close sidebar --}}
    <button id="crop-right-sidebar-fab"
        @click="rightOpen = !rightOpen"
        class="crop-right-sidebar-fab"
        :class="rightOpen ? 'sidebar-open-fab' : ''"
        :title="rightOpen ? 'Sembunyikan panel' : 'Buka panel potong'">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <!-- Closed state: show left chevron (<) pointing towards the screen to open -->
            <path x-show="!rightOpen" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            <!-- Open state: show right chevron (>) pointing towards the screen edge to close -->
            <path x-show="rightOpen" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" style="display: none;"/>
        </svg>
    </button>
    </template>

    <!-- Hidden listener for reset-crop event - kept for potential external triggers -->
    <div x-on:reset-crop.window="resetCrop()"></div>
</div>

<!-- CROP SIDEBAR MOBILE/TABLET VERSION (shown below upload area on small screens) -->
<div x-data="pdfViewer()" class="lg:hidden crop-sidebar-mobile"></div>

<style>
    .resize-handle {
        width: 14px;
        height: 14px;
        background: white;
        border: 2.5px solid #6366f1;
        border-radius: 3px;
        position: absolute;
        pointer-events: all;
        z-index: 10;
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.3);
        transition: transform 0.15s ease;
    }
    .resize-handle:hover {
        transform: scale(1.3);
    }
    .top-left { top: -7px; left: -7px; cursor: nw-resize; }
    .top-right { top: -7px; right: -7px; cursor: ne-resize; }
    .bottom-left { bottom: -7px; left: -7px; cursor: sw-resize; }
    .bottom-right { bottom: -7px; right: -7px; cursor: se-resize; }

    /* Custom range slider for Firefox */
    input[type="range"]::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #6366f1;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.3);
    }
    input[type="range"]::-moz-range-track {
        height: 6px;
        border-radius: 3px;
        background: #e2e8f0;
    }

    /* Scrollbar styling for sidebar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 3px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
</style>
