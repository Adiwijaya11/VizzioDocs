@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'PDF ke Excel — VizzioDocs')

@section('content')
<x-tool-template title="PDF ke Excel" description="Ekstrak data tabel dari PDF dan konversi menjadi file Excel (.xlsx)." action="{{ route('pdf-to-excel.process') }}" tool="pdf-to-excel">
    <x-slot:optionsSlot>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800">Info</p>
                    <p class="text-xs text-blue-600 mt-1">Fitur ini mengekstrak teks dari setiap halaman PDF ke dalam sheet Excel terpisah. Hasil terbaik untuk PDF yang berisi data terstruktur/tabel.</p>
                </div>
            </div>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
