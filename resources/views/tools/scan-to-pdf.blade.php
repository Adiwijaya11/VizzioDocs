@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Scan ke PDF — VizzioDocs')

@section('content')
<x-tool-template
    title="Scan ke PDF"
    description="Foto dokumen menggunakan kamera atau unggah gambar, lalu ubah jadi PDF."
    action="{{ route('scan-to-pdf.process') }}"
    tool="scan-to-pdf"
    :hideDefaultUpload="true"
    :lockedPaths="$lockedPaths" 
    :lockMap="$lockMap">

    {{-- All content goes into $slot since hideDefaultUpload=true --}}
    <div x-data="scanToPdf()">

        {{-- Source Toggle --}}
        <div class="flex justify-center mb-6">
            <div class="inline-flex bg-white/60 backdrop-blur rounded-2xl p-1 shadow border border-white/50 gap-1 w-full max-w-[320px]">
                <button type="button" id="btn-camera-tab"
                    @click="switchSource('camera')"
                    :class="source === 'camera' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700'"
                    class="flex-1 flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Kamera
                </button>
                <button type="button" id="btn-file-tab"
                    @click="switchSource('file')"
                    :class="source === 'file' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700'"
                    class="flex-1 flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload File
                </button>
            </div>
        </div>

        {{-- ============ CAMERA PANEL ============ --}}
        <div x-show="source === 'camera'" x-transition>
            <div class="rounded-2xl border border-white/60 overflow-hidden mb-4 shadow-inner bg-slate-900/5">

                {{-- Camera Viewfinder --}}
                <div class="relative bg-slate-950 w-full h-[50vh] min-h-[320px] max-h-[480px] flex items-center justify-center overflow-hidden">
                    <video id="camera-video"
                        x-ref="video"
                        autoplay playsinline muted
                        x-show="cameraActive && !capturedImage"
                        class="w-full h-full object-contain mx-auto block"></video>

                    {{-- Corner guides --}}
                    <div x-show="cameraActive && !capturedImage" class="absolute inset-4 sm:inset-8 pointer-events-none z-10">
                        <div class="absolute top-0 left-0 w-7 h-7 border-t-2 border-l-2 border-indigo-400 rounded-tl-lg"></div>
                        <div class="absolute top-0 right-0 w-7 h-7 border-t-2 border-r-2 border-indigo-400 rounded-tr-lg"></div>
                        <div class="absolute bottom-0 left-0 w-7 h-7 border-b-2 border-l-2 border-indigo-400 rounded-bl-lg"></div>
                        <div class="absolute bottom-0 right-0 w-7 h-7 border-b-2 border-r-2 border-indigo-400 rounded-br-lg"></div>
                    </div>

                    {{-- Scanner laser animation line --}}
                    <div x-show="cameraActive && !capturedImage" 
                        class="absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-75 shadow-[0_0_8px_rgba(99,102,241,0.8)] animate-scan pointer-events-none z-10"></div>

                    {{-- Captured preview --}}
                    <img x-show="capturedImage" :src="capturedImage"
                        class="w-full h-full object-contain mx-auto block">

                    {{-- Placeholder --}}
                    <div x-show="!cameraActive && !capturedImage"
                        class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 gap-3">
                        <svg class="w-14 h-14 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm font-medium">Klik "Buka Kamera" untuk mulai</p>
                    </div>

                    {{-- Error --}}
                    <div x-show="cameraError"
                        class="absolute inset-0 flex flex-col items-center justify-center text-red-400 gap-3 bg-slate-900/80 px-6 z-20">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                        <p class="text-sm text-center font-medium" x-text="cameraError"></p>
                        <button @click="cameraError = null" class="text-xs bg-red-500 text-white px-4 py-1.5 rounded-lg hover:bg-red-600 transition">Tutup</button>
                    </div>

                    {{-- Camera selector --}}
                    <div x-show="cameras.length > 1 && cameraActive && !capturedImage"
                        class="absolute top-3 right-3 z-20">
                        <select x-model="selectedCamera" @change="switchCamera()"
                            class="max-w-[150px] sm:max-w-xs text-xs bg-black/60 text-white border border-white/20 rounded-lg px-2 py-1.5 backdrop-blur cursor-pointer">
                            <template x-for="(cam, i) in cameras" :key="cam.deviceId">
                                <option :value="cam.deviceId" x-text="cam.label || 'Kamera ' + (i+1)"></option>
                            </template>
                        </select>
                    </div>

                    <canvas x-ref="canvas" class="hidden"></canvas>
                </div>

                {{-- Camera Controls --}}
                <div class="bg-white/60 px-4 py-4 flex flex-col sm:flex-row gap-3 items-center justify-center border-t border-slate-100">
                    <button type="button" id="btn-open-camera"
                        x-show="!cameraActive && !capturedImage"
                        @click="startCamera()"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Buka Kamera
                    </button>

                    <div x-show="cameraActive && !capturedImage" class="flex gap-2.5 sm:gap-3 w-full sm:w-auto justify-center">
                        <button type="button" id="btn-capture"
                            @click="capture()"
                            class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl shadow hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all text-sm">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3" stroke="currentColor"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            </svg>
                            Ambil Foto
                        </button>

                        <button type="button"
                            @click="stopCamera()"
                            class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tutup
                        </button>
                    </div>

                    <template x-if="capturedImage">
                        <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 w-full justify-center">
                            <button type="button" id="btn-add-photo"
                                @click="addToQueue()"
                                class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl shadow hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all text-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah ke Antrian
                            </button>
                            <button type="button" id="btn-retake"
                                @click="retake()"
                                class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition text-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Foto Ulang
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ============ FILE UPLOAD PANEL ============ --}}
        <div x-show="source === 'file'" x-transition>
            <label id="file-drop-zone"
                for="file-upload-input"
                @dragover.prevent="dragging = true"
                @dragleave="dragging = false"
                @drop.prevent="onDrop($event)"
                :class="dragging ? 'border-indigo-400 bg-indigo-50/60 scale-[1.01]' : 'border-slate-200 bg-slate-50/60 hover:border-indigo-300 hover:bg-indigo-50/40'"
                class="flex flex-col items-center justify-center gap-3 p-8 rounded-2xl border-2 border-dashed transition-all duration-200 cursor-pointer mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div class="text-center">
                    <p class="font-semibold text-slate-700 text-sm">Seret & letakkan gambar di sini</p>
                    <p class="text-xs text-slate-400 mt-0.5">atau klik untuk pilih file &mdash; JPG, PNG, WEBP, BMP</p>
                </div>
                <input id="file-upload-input" type="file" multiple
                    accept=".jpg,.jpeg,.png,.bmp,.webp"
                    class="hidden"
                    @change="onFileSelect($event)">
            </label>
        </div>

        {{-- ============ IMAGE QUEUE ============ --}}
        <div x-show="queue.length > 0" x-transition class="mb-4">
            <div class="bg-white/50 rounded-2xl border border-slate-100 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Antrian (<span x-text="queue.length"></span> gambar)
                    </h2>
                    <button type="button" @click="queue = []"
                        class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                        Hapus Semua
                    </button>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2.5 mb-4">
                    <template x-for="(item, index) in queue" :key="index">
                        <div class="relative group rounded-xl overflow-hidden aspect-[3/4] bg-slate-100 shadow-sm border border-slate-200">
                            <img :src="item.preview" class="w-full h-full object-cover">
                            <div class="absolute top-1.5 right-1.5 z-10 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                <button type="button" @click="queue.splice(index, 1)"
                                    class="w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg active:scale-95 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[9px] text-center py-0.5 font-medium">
                                Hal. <span x-text="index + 1"></span>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Options --}}
                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Ukuran Kertas</label>
                        <select x-model="paperSize"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white/80 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <option value="A4">A4</option>
                            <option value="Letter">Letter</option>
                            <option value="Legal">Legal</option>
                            <option value="Original">Sesuai Asli</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Kualitas</label>
                        <select x-model="quality"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white/80 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <option value="90">Tinggi (90)</option>
                            <option value="75" selected>Sedang (75)</option>
                            <option value="50">Rendah (50)</option>
                        </select>
                    </div>
                </div>

                <button type="button" id="btn-convert"
                    @click="convert()"
                    :disabled="processing || queue.length === 0"
                    class="mt-3 w-full flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-xl shadow hover:shadow-lg hover:scale-[1.01] active:scale-[0.99] transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100 text-sm">
                    <svg x-show="!processing" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <svg x-show="processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span x-text="processing ? 'Memproses...' : 'Buat PDF dari ' + queue.length + ' Gambar'"></span>
                </button>
            </div>
        </div>

        {{-- ============ RESULT ============ --}}
        <div x-show="result" x-transition class="mb-4">
            <div class="bg-emerald-50/80 border border-emerald-200 rounded-2xl p-6 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center mx-auto mb-3 shadow">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">PDF Berhasil Dibuat!</h3>
                <p class="text-slate-500 text-xs mb-4" x-text="`${lastCount} halaman telah dikonversi menjadi PDF.`"></p>
                <div class="flex flex-col sm:flex-row gap-2.5 justify-center w-full">
                    <a :href="result" id="btn-download-result"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 sm:py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-xl shadow hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download PDF
                    </a>
                    <button type="button" @click="reset()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Scan Baru
                    </button>
                </div>
            </div>
        </div>

        {{-- Error --}}
        <div x-show="errorMsg" x-transition class="mb-4">
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold text-red-700 text-sm">Terjadi kesalahan</p>
                    <p class="text-red-600 text-xs mt-0.5" x-text="errorMsg"></p>
                </div>
                <button @click="errorMsg = null" class="text-red-400 hover:text-red-600 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

    </div>{{-- end x-data --}}

</x-tool-template>

@push('styles')
<style>
    @keyframes scan-animation {
        0% { top: 5%; }
        50% { top: 95%; }
        100% { top: 5%; }
    }
    .animate-scan {
        position: absolute;
        animation: scan-animation 3s ease-in-out infinite;
    }
</style>
@endpush

@push('scripts')
<script>
function scanToPdf() {
    return {
        source: 'camera',
        cameraActive: false,
        cameraError: null,
        capturedImage: null,
        cameras: [],
        selectedCamera: null,
        stream: null,
        dragging: false,
        queue: [],
        paperSize: 'A4',
        quality: '75',
        processing: false,
        result: null,
        errorMsg: null,
        lastCount: 0,

        async switchSource(src) {
            if (this.source === 'camera' && this.cameraActive) {
                this.stopCamera();
            }
            this.source = src;
        },

        async startCamera() {
            this.cameraError = null;
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                this.cameras = devices.filter(d => d.kind === 'videoinput');
                if (!this.selectedCamera && this.cameras.length > 0) {
                    const back = this.cameras.find(c => /back|rear|environment/i.test(c.label));
                    this.selectedCamera = back ? back.deviceId : this.cameras[0].deviceId;
                }
                const constraints = {
                    video: this.selectedCamera
                        ? { deviceId: { exact: this.selectedCamera }, width: { ideal: 1920 }, height: { ideal: 1080 } }
                        : { facingMode: { ideal: 'environment' }, width: { ideal: 1920 }, height: { ideal: 1080 } }
                };
                this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                this.$refs.video.srcObject = this.stream;
                await this.$refs.video.play();
                this.cameraActive = true;
                const devicesAfter = await navigator.mediaDevices.enumerateDevices();
                this.cameras = devicesAfter.filter(d => d.kind === 'videoinput');
            } catch (err) {
                if (err.name === 'NotAllowedError') {
                    this.cameraError = 'Akses kamera ditolak. Izinkan akses kamera di browser Anda.';
                } else if (err.name === 'NotFoundError') {
                    this.cameraError = 'Tidak ada kamera yang ditemukan di perangkat ini.';
                } else {
                    this.cameraError = 'Gagal membuka kamera: ' + err.message;
                }
            }
        },

        async switchCamera() {
            if (!this.cameraActive) return;
            this.stopStream();
            await this.startCamera();
        },

        stopStream() {
            if (this.stream) {
                this.stream.getTracks().forEach(t => t.stop());
                this.stream = null;
            }
        },

        stopCamera() {
            this.stopStream();
            this.cameraActive = false;
            this.capturedImage = null;
            if (this.$refs.video) this.$refs.video.srcObject = null;
        },

        capture() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            this.capturedImage = canvas.toDataURL('image/jpeg', 0.92);
        },

        addToQueue() {
            if (!this.capturedImage) return;
            this.queue.push({ preview: this.capturedImage, dataUrl: this.capturedImage, type: 'camera' });
            this.capturedImage = null;
        },

        retake() { this.capturedImage = null; },

        onFileSelect(e) {
            this.addFiles(Array.from(e.target.files));
            e.target.value = '';
        },

        onDrop(e) {
            this.dragging = false;
            this.addFiles(Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')));
        },

        addFiles(files) {
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.queue.push({ preview: e.target.result, dataUrl: e.target.result, type: 'file', file });
                };
                reader.readAsDataURL(file);
            });
        },

        async convert() {
            if (this.queue.length === 0 || this.processing) return;
            this.processing = true;
            this.errorMsg = null;
            this.lastCount = this.queue.length;

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('paper_size', this.paperSize);
            formData.append('quality', this.quality);

            for (let i = 0; i < this.queue.length; i++) {
                const item = this.queue[i];
                if (item.type === 'file' && item.file) {
                    formData.append('images[]', item.file);
                } else {
                    const blob = await this.dataUrlToBlob(item.dataUrl);
                    formData.append('images[]', blob, `capture_${i+1}.jpg`);
                }
            }

            try {
                const res = await fetch('{{ route("scan-to-pdf.process") }}', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.success) {
                    this.result = data.download_url;
                    this.stopCamera();
                } else {
                    this.errorMsg = data.message || 'Gagal membuat PDF.';
                }
            } catch (err) {
                this.errorMsg = 'Terjadi kesalahan jaringan: ' + err.message;
            } finally {
                this.processing = false;
            }
        },

        dataUrlToBlob(dataUrl) {
            return new Promise(resolve => {
                const [header, base64] = dataUrl.split(',');
                const mime = header.match(/:(.*?);/)[1];
                const binary = atob(base64);
                const arr = new Uint8Array(binary.length);
                for (let i = 0; i < binary.length; i++) arr[i] = binary.charCodeAt(i);
                resolve(new Blob([arr], { type: mime }));
            });
        },

        reset() {
            this.queue = [];
            this.result = null;
            this.errorMsg = null;
            this.capturedImage = null;
            this.lastCount = 0;
        },

        destroy() { this.stopStream(); }
    }
}
</script>
@endpush
@endsection
