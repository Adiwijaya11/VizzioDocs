@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Optimize PDF — VizzioDocs')

@section('content')
<x-tool-template title="Optimize PDF" description="Optimasi struktur PDF untuk mengurangi ukuran file tanpa mengurangi kualitas signifikan." action="{{ route('optimize-pdf.process') }}" tool="optimize-pdf">
    <x-slot:optionsSlot>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Level Optimasi</label>
            <select name="level" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <option value="standard" selected>Standar - Kompresi ringan, kualitas tetap</option>
                <option value="aggressive">Agresif - Kompresi kuat, ukuran lebih kecil</option>
            </select>
            <p class="text-xs text-slate-400 mt-1">Level agresif akan menghasilkan file lebih kecil namun mungkin sedikit mengurangi kualitas gambar.</p>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
