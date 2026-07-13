@extends('layouts.app')

@section('hideFooter')
@endsection

@section('title', 'Ubah Excel ke PDF — VizzioDocs')

@section('content')
<x-tool-template 
    title="Konversi Excel ke PDF" 
    description="Ubah tabel data Microsoft Excel (.xlsx/.xls) Anda menjadi dokumen PDF yang mudah dibaca." 
    action="{{ route('excel-to-pdf.process') }}" 
    tool="excel-to-pdf" 
    accept=".xlsx,.xls" 
    mimes="Excel (.xlsx, .xls)" 
    :multiple="false"
/>
@endsection
