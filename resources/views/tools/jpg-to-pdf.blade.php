@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Ubah JPG ke PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Konversi JPG ke PDF" 
    description="Ubah dan gabungkan gambar JPG/JPEG Anda menjadi sebuah berkas PDF berkualitas tinggi." 
    action="{{ route('jpg-to-pdf.process') }}" 
    tool="jpg-to-pdf" 
    accept=".jpg,.jpeg,.png" 
    mimes="JPG, JPEG, PNG" 
    :multiple="true"
/>
@endsection
