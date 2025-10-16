@extends('layouts.app')
@section('title', 'Compras')
@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Lista de Compras</h4>
    <form method="GET" class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <label>Fecha de inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="{{ $fecha_inicio }}">
        </div>
        <div class="col-md-3">
            <label>Fecha de fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="{{ $fecha_fin }}">
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-4 text-end">
            <div class="card p-2 mb-2">
                <div class="fw-bold text-center">Monto Total de Compras</div>
                <div class="fs-4 text-center">$ {{ number_format($monto_total, 2) }}</div>
                <div class="small text-center">Desde <b>{{ \Carbon\Carbon::parse($fecha_inicio)->format('d-m-Y') }}</b> Hasta <b>{{ \Carbon\Carbon::parse($fecha_fin)->format('d-m-Y') }}</b></div>
            </div>
        </div>
    </form>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalNuevaCompra" style="margin-top: 0.5rem;"><i class="bi bi-plus-circle"></i> Nueva Compra</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Comprobante</th>
                    <th>Proveedor</th>
                    <th>Fecha y hora</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($compras as $compra)
                <tr>
                    <td>#{{ $compra->id }}</td>
                    <td>{{ $compra->user->name ?? '-' }}</td>
                    <td>{{ $compra->created_at->format('d/m/Y H:i') }}</td>
                    <td>${{ number_format($compra->total, 2) }}</td>
                    <td>
                        @if($compra->status == 'completada')
                            <span class="badge bg-success">Completada</span>
                        @else
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @endif
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                        <form action="#" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Eliminar compra?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay compras registradas en este rango de fechas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $compras->links() }}
        </div>
    </div>
    <!-- Modal Nueva Compra -->
    <div class="modal fade" id="modalNuevaCompra" tabindex="-1" aria-labelledby="modalNuevaCompraLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background:#fff; color:#111;">
          <div class="modal-header" style="border-bottom:2px solid #e53935;">
            <h5 class="modal-title" id="modalNuevaCompraLabel" style="color:#111;">Registrar Compra</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-5">
                <div class="p-3 mb-3" style="border:2px solid #e53935; border-radius:15px;">
                  <div class="fw-bold mb-2" style="background:#43e97b; color:#fff; border-radius:15px 15px 0 0; padding:6px 12px;">Datos generales</div>
                  <div class="mb-2">
                    <label>Proveedor:</label>
                    <select class="form-select">
                      <option>Proveedor General</option>
                    </select>
                  </div>
                  <div class="row mb-2">
                    <div class="col">
                      <label>Num. de Comp:</label>
                      <input type="text" class="form-control" value="237">
                    </div>
                    <div class="col">
                      <label>Impuesto(IGV):</label>
                      <input type="number" class="form-control" value="0">
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col">
                      <label>Fecha Emis:</label>
                      <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col">
                      <label>Fecha Venc:</label>
                      <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-7">
                <div class="p-3 mb-3" style="border:2px solid #111; border-radius:15px;">
                  <div class="fw-bold mb-2" style="background:#222; color:#fff; border-radius:15px 15px 0 0; padding:6px 12px;">Detalles de la compra</div>
                  <div class="mb-2">
                    <select class="form-select" style="background:#f8f9fa;">
                      <option>Selecciona un producto</option>
                    </select>
                  </div>
                  <div class="row mb-2">
                    <div class="col">
                      <label>Cantidad:</label>
                      <input type="number" class="form-control">
                    </div>
                    <div class="col">
                      <label>Precio de compra:</label>
                      <input type="number" class="form-control">
                    </div>
                    <div class="col">
                      <label>Precio de venta:</label>
                      <input type="number" class="form-control">
                    </div>
                  </div>
                  <div class="text-end mb-2">
                    <button class="btn btn-primary" style="background:#2962ff; border:none;">Agregar</button>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered" style="background:#fff;">
                      <thead style="background:#222; color:#fff;">
                        <tr>
                          <th>#</th>
                          <th>Producto</th>
                          <th>Cant.</th>
                          <th>Pre. compra</th>
                          <th>Pre. venta</th>
                          <th>Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr><td colspan="6"></td></tr>
                      </tbody>
                      <tfoot>
                        <tr><td colspan="5" class="text-end">Sumas</td><td>0</td></tr>
                        <tr><td colspan="5" class="text-end">IGV %</td><td>0</td></tr>
                        <tr><td colspan="5" class="text-end">Total</td><td>0</td></tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection 