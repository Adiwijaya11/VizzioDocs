@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'HTML ke PDF — VizzioDocs')

@section('content')
<x-tool-template title="HTML ke PDF" description="Konversi halaman HTML menjadi file PDF dengan tampilan yang sempurna." action="{{ route('html-to-pdf.process') }}" tool="html-to-pdf" accept=".html,.htm" mimes="HTML">
    <x-slot:optionsSlot>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Orientasi Halaman</label>
            <select name="orientation" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <option value="portrait">Portrait</option>
                <option value="landscape">Landscape</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Ukuran Kertas</label>
            <select name="page_size" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <option value="A4">A4</option>
                <option value="letter">Letter</option>
                <option value="legal">Legal</option>
            </select>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
