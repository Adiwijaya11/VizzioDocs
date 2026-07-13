@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Organize PDF — VizzioDocs')

@section('content')
<x-tool-template title="Organize PDF" description="Atur ulang halaman PDF Anda - putar, hapus, atau ubah urutan halaman." action="{{ route('organize-pdf.process') }}" tool="organize-pdf">
    <x-slot:optionsSlot>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Urutan Halaman Baru</label>
            <input type="text" name="page_order" placeholder="Contoh: 3,1,2,5,4" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <p class="text-xs text-slate-400 mt-1">Tulis ulang urutan halaman. Kosongkan untuk mempertahankan urutan asli.</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Halaman yang Dihapus</label>
            <input type="text" name="remove_pages" placeholder="Contoh: 2,4" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <p class="text-xs text-slate-400 mt-1">Halaman yang ingin dihapus dari hasil akhir.</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Rotasi Global (derajat)</label>
            <select name="rotation" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <option value="0">Tanpa Rotasi</option>
                <option value="90">90° (searah jarum jam)</option>
                <option value="180">180°</option>
                <option value="270">270° (berlawanan jarum jam)</option>
            </select>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
