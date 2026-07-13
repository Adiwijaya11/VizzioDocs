@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Ubah PNG ke PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Konversi PNG ke PDF" 
    description="Ubah dan gabungkan gambar PNG transparan Anda menjadi sebuah file PDF berkualitas tinggi." 
    action="{{ route('png-to-pdf.process') }}" 
    tool="png-to-pdf" 
    accept=".png" 
    mimes="PNG" 
    :multiple="true"
    :lockedPaths="$lockedPaths" 
    :lockMap="$lockMap"
/>
@endsection
