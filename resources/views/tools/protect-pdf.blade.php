@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Protect PDF — VizzioDocs')

@section('content')
<x-tool-template title="Protect PDF" description="Lindungi PDF Anda dengan password untuk keamanan tambahan." action="{{ route('protect-pdf.process') }}" tool="protect-pdf" :lockedPaths="$lockedPaths" :lockMap="$lockMap">
    <x-slot:optionsSlot>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Pengguna</label>
            <input type="password" name="user_password" placeholder="Masukkan password untuk membuka PDF" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" required>
            <p class="text-xs text-slate-400 mt-1">Password ini dibutuhkan untuk membuka PDF.</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Owner (Opsional)</label>
            <input type="password" name="owner_password" placeholder="Password untuk mengubah pengaturan" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white/80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <p class="text-xs text-slate-400 mt-1">Password ini memungkinkan mengubah izin PDF.</p>
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="allow_print" value="1" checked id="allow-print" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <label for="allow-print" class="text-sm text-slate-600">Izinkan Print</label>
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="allow_copy" value="1" checked id="allow-copy" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <label for="allow-copy" class="text-sm text-slate-600">Izinkan Salin Teks</label>
        </div>
    </x-slot:optionsSlot>
</x-tool-template>
@endsection
