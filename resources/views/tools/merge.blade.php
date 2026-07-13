@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Gabungkan PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Gabungkan Berkas PDF" 
    description="Satukan dua atau lebih dokumen PDF menjadi satu berkas PDF baru secara urut." 
    action="{{ route('merge.process') }}" 
    tool="merge" 
    accept=".pdf" 
    mimes="PDF" 
    :multiple="true"
/>
@endsection
