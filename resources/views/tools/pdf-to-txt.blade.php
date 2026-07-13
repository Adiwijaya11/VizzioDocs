@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Konversi PDF ke TXT — VizzioDocs')

@section('content')
<x-tool-template 
    title="Konversi PDF ke TXT" 
    description="Ekstrak semua teks dari file PDF Anda dan simpan sebagai dokumen teks biasa (.txt)." 
    action="{{ route('pdf-to-txt.process') }}" 
    tool="pdf-to-txt" 
    accept=".pdf" 
    mimes="PDF" 
    :multiple="false"
    :lockedPaths="$lockedPaths" 
    :lockMap="$lockMap"
/>
@endsection
