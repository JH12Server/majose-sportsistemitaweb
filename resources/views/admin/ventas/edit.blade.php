@extends('layouts.app')
@section('title', 'Editar Venta')
@section('content')
<div class="container mt-4">
    <h4>Editar Venta</h4>
    <form action="{{ route('ventas.update', $venta) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Cliente</label>
            <select name="user_id" class="form-control" required>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" @if($venta->user_id==$cliente->id) selected @endif>{{ $cliente->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Producto</label>
            <select name="producto_id" class="form-control" required>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" @if($detalle && $detalle->producto_id==$producto->id) selected @endif>{{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Cantidad</label>
            <input type="number" name="cantidad" class="form-control" value="{{ $detalle->cantidad ?? 1 }}" min="1" required>
        </div>
        <div class="mb-3">
            <label>Monto</label>
            <input type="number" name="monto" class="form-control" value="{{ $venta->monto }}" required>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="completado" @if($venta->estado=='completado') selected @endif>Completado</option>
                <option value="pendiente" @if($venta->estado=='pendiente') selected @endif>Pendiente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 