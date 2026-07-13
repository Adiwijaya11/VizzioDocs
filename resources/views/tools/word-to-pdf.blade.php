@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Ubah Word ke PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Konversi Word ke PDF" 
    description="Ubah file dokumen Microsoft Word (.docx) Anda menjadi file PDF instan dengan layout rapi." 
    action="{{ route('word-to-pdf.process') }}" 
    tool="word-to-pdf" 
    accept=".docx" 
    mimes="Word (.docx)" 
    :multiple="false"
    :lockedPaths="$lockedPaths" 
    :lockMap="$lockMap"
/>
@endsection
