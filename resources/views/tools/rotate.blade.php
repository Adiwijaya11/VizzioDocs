@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Putar Halaman PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Putar Halaman PDF" 
    description="Putar posisi seluruh halaman dokumen PDF Anda secara permanen dengan sudut tertentu." 
    action="{{ route('rotate.process') }}" 
    tool="rotate" 
    accept=".pdf" 
    mimes="PDF" 
    :multiple="false"
    :lockedPaths="$lockedPaths" 
    :lockMap="$lockMap"
>
    <x-slot:optionsSlot>
        <div class="space-y-4">
            <div>
                <label for="rotate-angle" class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Pilih Sudut Putaran</label>
                <select id="rotate-angle" name="angle" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-semibold focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-xs">
                    <option value="90">90° Kanan (Searah Jarum Jam)</option>
                    <option value="180">180° (Terbalik)</option>
                    <option value="270">90° Kiri (Berlawanan Jarum Jam)</option>
                </select>
            </div>

            <!-- Rotation Preview Panel -->
            <div id="rotate-preview-container" class="hidden flex flex-col items-center justify-center py-4 border-t border-slate-100 mt-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Preview Arah Halaman</p>
                <div id="rotate-preview-box" class="w-36 h-48 bg-white border-2 border-dashed border-indigo-200 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 shadow-md transform rotate-90">
                    <div class="w-10 h-14 border-2 border-indigo-500 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8" />
                        </svg>
                    </div>
                    <span id="rotate-pdf-name" class="text-[9px] text-slate-400 font-semibold mt-3 max-w-[110px] truncate">document.pdf</span>
                </div>
            </div>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
