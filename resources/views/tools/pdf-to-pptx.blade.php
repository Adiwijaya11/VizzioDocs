@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'PDF ke PowerPoint — VizzioDocs')

@section('content')
<x-tool-template title="PDF ke PowerPoint" description="Konversi PDF menjadi presentasi PowerPoint (.pptx) - setiap halaman menjadi satu slide." action="{{ route('pdf-to-pptx.process') }}" tool="pdf-to-pptx">
    <x-slot:optionsSlot>
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-purple-800">Info</p>
                    <p class="text-xs text-purple-600 mt-1">Setiap halaman PDF akan dikonversi menjadi satu slide PowerPoint berisi teks yang diekstrak dari halaman tersebut.</p>
                </div>
            </div>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
