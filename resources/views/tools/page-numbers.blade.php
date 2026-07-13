@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Page Numbers — VizzioDocs')

@section('content')
<x-tool-template title="Page Numbers" description="Tambahkan nomor halaman ke setiap halaman PDF Anda." action="{{ route('page-numbers.process') }}" tool="page-numbers">
    <x-slot:optionsSlot>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Posisi Nomor Halaman</label>
            <select name="position" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <option value="bottom-center">Bawah - Tengah</option>
                <option value="bottom-right">Bawah - Kanan</option>
                <option value="bottom-left">Bawah - Kiri</option>
                <option value="top-center">Atas - Tengah</option>
                <option value="top-right">Atas - Kanan</option>
                <option value="top-left">Atas - Kiri</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Format Nomor</label>
            <select name="format" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <option value="number">1, 2, 3...</option>
                <option value="dash">- 1 -, - 2 -, - 3 -</option>
                <option value="of">1 of N, 2 of N...</option>
                <option value="page">Halaman 1, Halaman 2...</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Mulai dari Halaman</label>
            <input type="number" name="start_page" value="1" min="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <p class="text-xs text-slate-400 mt-1">Nomor halaman mulai ditambahkan dari halaman ini.</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Ukuran Font</label>
            <input type="number" name="font_size" value="10" min="6" max="24" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
