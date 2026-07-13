@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Ubah PDF ke JPG — VizzioDocs')

@section('content')
<x-tool-template 
    title="Konversi PDF ke JPG" 
    description="Ekstrak halaman PDF Anda menjadi gambar JPG berkualitas tinggi dalam hitungan detik." 
    action="{{ route('pdf-to-jpg.process') }}" 
    tool="pdf-to-jpg" 
    accept=".pdf" 
    mimes="PDF" 
    :multiple="false"
/>
@endsection
