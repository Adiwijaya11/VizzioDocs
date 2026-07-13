@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Watermark PDF — VizzioDocs')

@section('content')
<x-tool-template title="Watermark PDF" description="Tambahkan watermark teks atau gambar ke setiap halaman PDF Anda." action="{{ route('watermark-pdf.process') }}" tool="watermark-pdf" :lockedPaths="$lockedPaths" :lockMap="$lockMap">
    <x-slot:optionsSlot>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tipe Watermark</label>
                <select name="watermark_type" id="watermark-type" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <option value="text">Teks</option>
                    <option value="image">Gambar</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Posisi</label>
                <select name="position" id="watermark-position" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <option value="center">Tengah</option>
                    <option value="top-left">Kiri Atas</option>
                    <option value="top-right">Kanan Atas</option>
                    <option value="bottom-left">Kiri Bawah</option>
                    <option value="bottom-right">Kanan Bawah</option>
                </select>
            </div>
        </div>
        <div id="text-watermark-options" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Teks Watermark</label>
                <input type="text" name="watermark_text" placeholder="Contoh: RAHASIA" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" value="RAHASIA">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ukuran Font</label>
                    <input type="range" name="font_size" min="12" max="200" value="60" class="w-full accent-indigo-600">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>12</span>
                        <span id="font-size-value">60</span>
                        <span>200</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Rotasi (°)</label>
                    <input type="range" name="rotation" min="0" max="360" value="45" class="w-full accent-indigo-600">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>0°</span>
                        <span id="rotation-value">45°</span>
                        <span>360°</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Warna</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" id="watermark-color" value="#FF0000" class="w-12 h-12 rounded-xl border border-slate-200 cursor-pointer p-1 bg-white/80">
                        <input type="text" id="color-hex" value="#FF0000" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono text-sm uppercase">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Transparansi (%)</label>
                    <input type="range" name="opacity" min="10" max="100" value="40" class="w-full accent-indigo-600">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>10%</span>
                        <span id="opacity-value">40%</span>
                        <span>100%</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="image-watermark-options" class="hidden">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Watermark</label>
            <input type="file" name="watermark_image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
        </div>
        <script>
            document.getElementById('watermark-type').addEventListener('change', function() {
                document.getElementById('text-watermark-options').classList.toggle('hidden', this.value !== 'text');
                document.getElementById('image-watermark-options').classList.toggle('hidden', this.value !== 'image');
            });
            // Live preview helpers for range inputs
            document.querySelector('input[name="font_size"]').addEventListener('input', function() {
                document.getElementById('font-size-value').textContent = this.value;
            });
            document.querySelector('input[name="rotation"]').addEventListener('input', function() {
                document.getElementById('rotation-value').textContent = this.value + '°';
            });
            document.querySelector('input[name="opacity"]').addEventListener('input', function() {
                document.getElementById('opacity-value').textContent = this.value + '%';
            });
            // Sync color picker with hex input
            document.getElementById('watermark-color').addEventListener('input', function() {
                document.getElementById('color-hex').value = this.value;
            });
            document.getElementById('color-hex').addEventListener('input', function() {
                if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                    document.getElementById('watermark-color').value = this.value;
                }
            });
        </script>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
