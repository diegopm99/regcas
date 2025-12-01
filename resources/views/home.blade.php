@extends('layout.app')

@section('title', 'Home')

@push('styles')
    
@endpush

@section('content')
    <h1>Bienvenido</h1>
    <a class="btn btn-primary" href="{{ route('comprobante.index') }}">Buscar comprobante</a>
    <a class="btn btn-secondary" href="{{ route('documento.view') }}">Reportes</a>
    <br>
    <h2>Mantenimientos:</h2>
    <a class="btn btn-secondary" href="{{ route('tdocumento.view') }}">Tipos de Documentos</a>
    <a class="btn btn-secondary" href="{{ route('retencion.view') }}">Retenciones</a>
    <a class="btn btn-secondary" href="{{ route('cuenta.view') }}">Cuentas Contables</a>
@endsection
