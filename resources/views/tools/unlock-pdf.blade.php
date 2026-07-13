@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Unlock PDF — VizzioDocs')

@section('content')
<x-tool-template title="Unlock PDF" description="Buka kunci PDF yang dilindungi password." action="{{ route('unlock-pdf.process') }}" tool="unlock-pdf" :lockedPaths="$lockedPaths" :lockMap="$lockMap">
    <x-slot:optionsSlot>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Password PDF</label>
            <input type="password" name="password" placeholder="Masukkan password PDF Anda" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <p class="text-xs text-slate-400 mt-1">Masukkan password yang digunakan untuk membuka PDF. Kosongkan jika PDF hanya dibatasi izin (bukan password buka).</p>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
