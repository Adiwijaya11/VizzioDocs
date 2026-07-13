@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'PDF ke PDF/A — VizzioDocs')

@section('content')
<x-tool-template title="PDF ke PDF/A" description="Konversi PDF standar ke format PDF/A untuk arsip jangka panjang." action="{{ route('pdf-to-pdfa.process') }}" tool="pdf-to-pdfa">
    <x-slot:optionsSlot>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Standar Kepatuhan</label>
            <select name="compliance" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <option value="pdfa-1b">PDF/A-1b (Level Dasar)</option>
                <option value="pdfa-2b">PDF/A-2b (Direkomendasikan)</option>
            </select>
            <p class="text-xs text-slate-400 mt-1">PDF/A-2b mendukung transparansi dan kompresi yang lebih baik.</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-green-800">PDF/A</p>
                    <p class="text-xs text-green-600 mt-1">Format PDF/A menjamin dokumen dapat dibaca dan dirender secara konsisten untuk jangka panjang, cocok untuk arsip dan dokumentasi resmi.</p>
                </div>
            </div>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
