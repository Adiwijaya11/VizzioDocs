@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Remove Pages — VizzioDocs')

@section('content')
<x-tool-template title="Remove Pages" description="Hapus halaman tertentu dari PDF Anda. Pilih halaman mana saja yang ingin dihapus dengan mudah." action="{{ route('remove-pages.process') }}" tool="remove-pages" :lockedPaths="$lockedPaths" :lockMap="$lockMap">
    <x-slot:optionsSlot>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Halaman yang akan dihapus</label>
            <input type="text" name="pages" placeholder="Contoh: 1,3,5-7" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" required>
            <p class="text-xs text-slate-400 mt-1">Gunakan koma (,) untuk memisahkan halaman dan strip (-) untuk range. Contoh: 1,3,5-7</p>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
