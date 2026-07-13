@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Pisahkan PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Pisahkan Berkas PDF" 
    description="Ekstrak halaman tertentu dari PDF atau pisahkan setiap halaman menjadi file PDF tersendiri." 
    action="{{ route('split.process') }}" 
    tool="split" 
    accept=".pdf" 
    mimes="PDF" 
    :multiple="false"
    :lockedPaths="$lockedPaths" 
    :lockMap="$lockMap"
>
    <x-slot:optionsSlot>
        <div class="space-y-4">
            <div>
                <label for="split-mode" class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Pilih Mode Pemisahan</label>
                <select id="split-mode" name="mode" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-semibold focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-xs">
                    <option value="all">Pisahkan Semua Halaman (Hasil ZIP)</option>
                    <option value="range">Ekstrak Rentang Halaman Tertentu</option>
                </select>
            </div>

            <!-- Range Options (Hidden by default, shown by JS when range is selected) -->
            <div id="range-options-container" class="hidden grid grid-cols-2 gap-4">
                <div>
                    <label for="start_page" class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Halaman Mulai</label>
                    <input type="number" id="start_page" name="start_page" min="1" placeholder="Contoh: 1" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-semibold focus:outline-none focus:border-indigo-500 shadow-xs">
                </div>
                <div>
                    <label for="end_page" class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Halaman Selesai</label>
                    <input type="number" id="end_page" name="end_page" min="1" placeholder="Contoh: 5" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-semibold focus:outline-none focus:border-indigo-500 shadow-xs">
                </div>
            </div>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
