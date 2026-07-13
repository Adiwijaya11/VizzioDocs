@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Kompres PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Kompres Ukuran PDF" 
    description="Kurangi ukuran file PDF Anda menggunakan kompresi tingkat tinggi yang dioptimalkan." 
    action="{{ route('compress.process') }}" 
    tool="compress" 
    accept=".pdf" 
    mimes="PDF" 
    :multiple="false"
>
    <x-slot:optionsSlot>
        <div class="space-y-4">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pilih Mode Kompresi</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Standard Mode -->
                <label class="relative flex flex-col p-4 bg-white border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50/50 transition-colors shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-slate-800">Kompresi Standar</span>
                        <input type="radio" name="mode" value="standard" checked class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                    </div>
                    <p class="text-slate-400 text-xs leading-normal">Merekompresi berkas stream PDF. Ukuran berkurang, kualitas sama seperti aslinya.</p>
                </label>

                <!-- Extreme Mode -->
                <label class="relative flex flex-col p-4 bg-white border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50/50 transition-colors shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-slate-800">Kompresi Ekstrim</span>
                        <input type="radio" name="mode" value="extreme" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                    </div>
                    <p class="text-slate-400 text-xs leading-normal">Membangun ulang PDF dengan memproses ulang gambar ke kualitas 60%. Cocok untuk PDF hasil scan.</p>
                </label>

            </div>

            <!-- Target Size Option -->
            <div class="pt-2">
                <label class="relative flex flex-col p-5 bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-100 rounded-xl cursor-pointer hover:border-indigo-200 transition-all shadow-sm">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-bold text-slate-800">Kompres ke Ukuran Target</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-600 text-white rounded-full uppercase tracking-wide">Baru</span>
                            </div>
                            <p class="text-slate-600 text-xs leading-relaxed">Kompres PDF hingga ukuran file di bawah target yang Anda tentukan. Kualitas akan disesuaikan otomatis.</p>
                        </div>
                        <input type="radio" name="mode" value="target_size" class="mt-1 w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                    </div>
                    
                    <div class="flex items-center gap-3 pt-2 border-t border-indigo-100/50">
                        <label for="target_size_mb" class="text-xs font-semibold text-slate-600 whitespace-nowrap">Ukuran Target:</label>
                        <div class="relative flex-1 max-w-[200px]">
                            <input 
                                type="number" 
                                id="target_size_mb" 
                                name="target_size_mb" 
                                value="1" 
                                min="0.1" 
                                max="10" 
                                step="0.1"
                                class="w-full px-3 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="1.0"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">MB</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const targetInput = document.getElementById('target_size_mb');
        const targetRadio = document.querySelector('input[name="mode"][value="target_size"]');
        
        // If user clicks the input, automatically select the radio button
        targetInput.addEventListener('click', function() {
            targetRadio.checked = true;
        });

        targetInput.addEventListener('focus', function() {
            targetRadio.checked = true;
        });
    });
</script>
@endpush
@endsection
