@extends('layouts.app')

@section('content')
    <div class="container px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Billboard - Gestor de Productos</h1>
        <p class="mb-6 text-gray-600">Sube imágenes y crea nuevos productos para el catálogo del cliente.</p>

        @livewire('billboard-uploader')
    </div>
@endsection
