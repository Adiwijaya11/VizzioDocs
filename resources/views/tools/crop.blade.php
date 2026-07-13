@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Crop Halaman PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Crop Halaman PDF" 
    description="Potong halaman PDF Anda dengan mudah — atur batas potong secara visual dan unduh hasilnya dalam hitungan detik."
    action="{{ route('crop.process') }}" 
    tool="crop" 
    accept=".pdf" 
    mimes="PDF"
    :hideDefaultUpload="true"
    :maxFileSize="$maxFileSize ?? ($adminMaxFileSizeMB * 1024 * 1024)"
>
    <div class="w-full flex flex-col h-full">
        {{-- PDF Upload + Viewer --}}
        @include('components.pdf-upload', ['maxFileSize' => $maxFileSize ?? ($adminMaxFileSizeMB * 1024 * 1024)])
    </div>
</x-tool-template>

@push('scripts')
<script src="{{ asset('js/pdfjs/pdf.min.js') }}"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ asset('js/pdfjs/pdf.worker.min.js') }}';
</script>
@endpush
@endsection
