@extends('layouts.app')
@section('title', 'Nueva Venta')
@section('content')
<div class="container mt-4">
    <h4>Registrar Venta</h4>
    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Cliente</label>
            <select name="user_id" class="form-control" required>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Producto</label>
            <select name="producto_id" class="form-control" required>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Cantidad</label>
            <input type="number" name="cantidad" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label>Monto</label>
            <input type="number" name="monto" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="completado">Completado</option>
                <option value="pendiente">Pendiente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 