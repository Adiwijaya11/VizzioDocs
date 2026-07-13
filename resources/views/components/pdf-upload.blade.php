<div x-data="pdfUpload({{ $maxFileSize ?? ($adminMaxFileSizeMB * 1024 * 1024) }})"
    class="relative flex flex-col items-center justify-center w-full px-2 sm:px-4 py-6 sm:py-8 md:py-12">
    
    <!-- Upload Area -->
    <div x-show="!uploaded"
        class="relative w-full max-w-4xl group cursor-pointer transition-all duration-500">

        <!-- Main Upload Card -->
        <div class="relative overflow-hidden rounded-3xl bg-white/80 backdrop-blur-sm border-2 border-dashed border-indigo-200/60 
            transition-all duration-500 ease-out
            group-hover:border-indigo-400/80 group-hover:shadow-2xl group-hover:shadow-indigo-500/20
            group-active:scale-[0.99]"
            :class="{ 'border-indigo-500 shadow-2xl shadow-indigo-500/30 bg-indigo-50/60 scale-[1.01]': isDragging }"
            @dragover.prevent="isDragging = true"
            @dragleave="isDragging = false"
            @drop.prevent="handleDrop">
            
            <!-- Animated gradient glow -->
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 
                group-hover:from-indigo-500/15 group-hover:via-purple-500/15 group-hover:to-pink-500/15 
                blur-xl transition-all duration-700 -z-10"></div>

            <input type="file" id="pdf-upload-input" accept="application/pdf" 
                class="absolute inset-0 opacity-0 cursor-pointer z-10"
                @change="handleFileChange">

            <div class="relative px-6 sm:px-10 md:px-14 py-8 sm:py-10 md:py-14 text-center">
                <!-- Premium decorative corner accents -->
                <div class="absolute top-0 left-0 w-16 h-16 sm:w-20 sm:h-20 overflow-hidden pointer-events-none">
                    <div class="absolute -top-8 -left-8 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-indigo-400/20 to-purple-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <svg class="absolute top-3 left-3 w-4 h-4 text-indigo-300/50 group-hover:text-indigo-400/80 transition-all duration-500 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 overflow-hidden pointer-events-none">
                    <div class="absolute -top-8 -right-8 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-bl from-pink-400/20 to-purple-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <svg class="absolute top-3 right-3 w-4 h-4 text-pink-300/50 group-hover:text-pink-400/80 transition-all duration-500 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="absolute bottom-0 left-0 w-16 h-16 sm:w-20 sm:h-20 overflow-hidden pointer-events-none">
                    <div class="absolute -bottom-8 -left-8 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-tr from-purple-400/20 to-indigo-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                </div>
                <div class="absolute bottom-0 right-0 w-16 h-16 sm:w-20 sm:h-20 overflow-hidden pointer-events-none">
                    <div class="absolute -bottom-8 -right-8 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-tl from-indigo-400/20 to-pink-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                </div>

                <!-- Upload Icon with animated rings -->
                <div class="relative mx-auto w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 mb-6 sm:mb-8">
                    <!-- Outer pulse ring -->
                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-indigo-400 to-purple-500 
                        opacity-0 group-hover:opacity-25 blur-xl transition-all duration-700 
                        group-hover:scale-150"></div>
                    <!-- Rotating ring border -->
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-indigo-400/30 to-purple-500/30 
                        group-hover:rotate-180 transition-transform duration-1000 ease-out"></div>
                    <!-- Inner spinning ring -->
                    <div class="absolute inset-2 rounded-full border-2 border-transparent border-t-indigo-300/40 border-r-purple-300/40 
                        animate-spin-slow opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <!-- Icon container -->
                    <div class="relative flex items-center justify-center w-full h-full 
                        bg-gradient-to-br from-white to-indigo-50/80 
                        rounded-full shadow-xl border border-indigo-100/50
                        group-hover:scale-110 group-hover:rotate-6 
                        transition-all duration-500 ease-out
                        group-hover:shadow-2xl group-hover:shadow-indigo-500/30">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 text-indigo-600 
                            group-hover:animate-bounce transition-all duration-300" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <!-- Decorative sparkle dots -->
                    <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-indigo-400 
                        opacity-0 group-hover:opacity-100 transition-all duration-500 
                        group-hover:animate-pulse"></div>
                    <div class="absolute -bottom-1 -left-1 w-2 h-2 rounded-full bg-purple-400 
                        opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100
                        group-hover:animate-pulse"></div>
                    <div class="absolute top-1/2 -right-2 w-1.5 h-1.5 rounded-full bg-pink-400 
                        opacity-0 group-hover:opacity-70 transition-all duration-500 delay-200
                        group-hover:animate-ping"></div>
                </div>
                
                <!-- Text -->
                <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-800 mb-2 sm:mb-3 leading-tight
                    group-hover:text-indigo-700 transition-colors duration-300">
                    Seret & Lepas PDF Anda
                </h3>
                <p class="text-sm sm:text-base md:text-lg text-slate-400 mb-5 sm:mb-6 transition-colors duration-300
                    group-hover:text-slate-500">
                    atau <span class="text-indigo-600 font-semibold group-hover:text-indigo-800 transition-colors">klik untuk memilih file</span>
                </p>
                
                <!-- Info badges -->
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                        bg-white/90 backdrop-blur-sm border border-indigo-100 
                        shadow-md group-hover:shadow-lg group-hover:border-indigo-200 
                        transition-all duration-300 hover:scale-105">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700 text-sm font-bold">PDF</span>
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                        bg-white/90 backdrop-blur-sm border border-indigo-100 shadow-md
                        transition-all duration-300 hover:scale-105">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="text-slate-700 text-sm font-bold" x-text="'Maks ' + Math.round(maxSize / (1024 * 1024)) + 'MB'"></span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                        bg-white/90 backdrop-blur-sm border border-indigo-100 shadow-md
                        transition-all duration-300 hover:scale-105">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="text-slate-700 text-sm font-bold">Aman &amp; Terenkripsi</span>
                    </div>
                </div>

                <!-- Drag indicator -->
                <div x-show="isDragging" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-5 text-xs font-semibold text-indigo-600 
                        bg-indigo-50/80 py-1.5 px-3 rounded-full inline-block">
                    Lepaskan file di sini ✨
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="isLoading" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 backdrop-blur-md z-50 rounded-2xl sm:rounded-3xl shadow-2xl">
        <div class="relative flex items-center justify-center w-20 h-20 mb-6">
            <div class="absolute w-20 h-20 border-4 border-indigo-200 rounded-full animate-spin-slow"></div>
            <div class="absolute w-12 h-12 border-4 border-indigo-400 rounded-full animate-spin-fast delay-150"></div>
            <svg class="w-8 h-8 text-indigo-600 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <p class="text-indigo-800 text-xl font-bold mb-2" x-text="loadingText">Memproses PDF Anda...</p>
        <p class="text-gray-600 text-sm">Harap tunggu sebentar</p>
    </div>

    <!-- PDF Viewer Section (rendered only after upload) -->
    <!-- Uploaded: Show viewer directly -->
    <template x-if="uploaded">
        <div class="w-full relative">
            <div class="w-full relative">
                @include('components.pdf-viewer')
            </div>
        </div>
    </template>
</div>
