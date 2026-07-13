@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Konversi PDF ke Markdown — VizzioDocs')

@section('content')
<x-tool-template 
    title="Konversi PDF ke Markdown" 
    description="Ubah dokumen PDF Anda menjadi format Markdown (.md) yang bersih dan terstruktur." 
    action="{{ route('pdf-to-markdown.process') }}" 
    tool="pdf-to-markdown" 
    accept=".pdf" 
    mimes="PDF" 
    :multiple="false"
/>
@endsection
