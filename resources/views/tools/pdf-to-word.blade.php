@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Ubah PDF ke Word — VizzioDocs')

@section('content')
<x-tool-template 
    title="Konversi PDF ke Word" 
    description="Ekstrak data teks dari file PDF Anda dan konversikan menjadi file Word (.docx) yang dapat diedit." 
    action="{{ route('pdf-to-word.process') }}" 
    tool="pdf-to-word" 
    accept=".pdf" 
    mimes="PDF" 
    :multiple="false"
    :lockedPaths="$lockedPaths" 
    :lockMap="$lockMap"
/>
@endsection
