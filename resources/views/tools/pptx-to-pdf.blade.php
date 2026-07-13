@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'PowerPoint ke PDF — VizzioDocs')

@section('content')
<x-tool-template title="PowerPoint ke PDF" description="Konversi file PowerPoint (.pptx) menjadi PDF dengan mudah." action="{{ route('pptx-to-pdf.process') }}" tool="pptx-to-pdf" accept=".pptx" mimes="PPTX" :lockedPaths="$lockedPaths" :lockMap="$lockMap">
    <x-slot:optionsSlot>
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-orange-800">Info</p>
                    <p class="text-xs text-orange-600 mt-1">Konten teks dari setiap slide akan diekstrak dan diformat ke dalam dokumen PDF yang rapi.</p>
                </div>
            </div>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
