@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Repair PDF — VizzioDocs')

@section('content')
<x-tool-template title="Repair PDF" description="Perbaiki file PDF yang rusak atau corrupt agar bisa digunakan kembali." action="{{ route('repair-pdf.process') }}" tool="repair-pdf" :lockedPaths="$lockedPaths" :lockMap="$lockMap">
    <x-slot:optionsSlot>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Perhatian</p>
                    <p class="text-xs text-amber-600 mt-1">Fitur ini akan mencoba memperbaiki struktur PDF yang rusak. Hasil bergantung pada tingkat kerusakan file. File asli tidak akan diubah.</p>
                </div>
            </div>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
