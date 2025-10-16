@extends('layouts.app')
@section('title', 'Gestion de Caja / Gastos')
@section('content')
<div class="container mt-4">
    <div class="text-center mb-4">
        <h2><i class="bi bi-cash-register"></i> Gestion de Caja / Gastos</h2>
    </div>
    <div class="row mb-4">
        <div class="col-md-4 mb-2">
            <label>Fecha de inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ request('fecha_inicio', date('Y-m-d', strtotime('-7 days'))) }}">
        </div>
        <div class="col-md-4 mb-2">
            <label>Fecha de fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ request('fecha_fin', date('Y-m-d')) }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100" id="btn-filtrar"><i class="bi bi-funnel"></i> Filtrar</button>
        </div>
    </div>
    <div class="card p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5><i class="bi bi-cash-coin"></i> Resumen de Caja</h5>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalResumenCaja"><i class="bi bi-plus"></i> Añadir Resumen de Caja</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Ventas (Efectivo)</th>
                        <th>Ventas (Otros)</th>
                        <th>Gastos (Efectivo)</th>
                        <th>Gastos (Otros)</th>
                        <th>Saldo Efectivo</th>
                        <th>Saldo Otros</th>
                        <th>Saldo Total</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí van los datos de resumen de caja -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="card p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5><i class="bi bi-list"></i> Listado de Gastos</h5>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalGasto"><i class="bi bi-plus"></i> Añadir Gasto</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Método de Pago</th>
                        <th>Monto</th>
                        <th>User</th>
                        <th>Estado</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí van los datos de gastos -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal Añadir Resumen de Caja -->
    <div class="modal fade" id="modalResumenCaja" tabindex="-1" aria-labelledby="modalResumenCajaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResumenCajaLabel">Añadir Resumen de Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formResumenCaja">
                        <div class="mb-3">
                            <label for="fecha_resumen" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha_resumen" name="fecha_resumen" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="ventas_efectivo" class="form-label">Ventas (Efectivo)</label>
                            <input type="number" step="0.01" class="form-control" id="ventas_efectivo" name="ventas_efectivo" required>
                        </div>
                        <div class="mb-3">
                            <label for="ventas_otros" class="form-label">Ventas (Otros)</label>
                            <input type="number" step="0.01" class="form-control" id="ventas_otros" name="ventas_otros" required>
                        </div>
                        <div class="mb-3">
                            <label for="gastos_efectivo" class="form-label">Gastos (Efectivo)</label>
                            <input type="number" step="0.01" class="form-control" id="gastos_efectivo" name="gastos_efectivo" required>
                        </div>
                        <div class="mb-3">
                            <label for="gastos_otros" class="form-label">Gastos (Otros)</label>
                            <input type="number" step="0.01" class="form-control" id="gastos_otros" name="gastos_otros" required>
                        </div>
                        <div class="mb-3">
                            <label for="saldo_efectivo" class="form-label">Saldo Efectivo</label>
                            <input type="number" step="0.01" class="form-control" id="saldo_efectivo" name="saldo_efectivo" required>
                        </div>
                        <div class="mb-3">
                            <label for="saldo_otros" class="form-label">Saldo Otros</label>
                            <input type="number" step="0.01" class="form-control" id="saldo_otros" name="saldo_otros" required>
                        </div>
                        <div class="mb-3">
                            <label for="saldo_total" class="form-label">Saldo Total</label>
                            <input type="number" step="0.01" class="form-control" id="saldo_total" name="saldo_total" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Añadir Gasto -->
    <div class="modal fade" id="modalGasto" tabindex="-1" aria-labelledby="modalGastoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGastoLabel">Añadir Gasto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formGasto">
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago</label>
                            <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Yape/Plin">Yape/Plin</option>
                                <option value="BCP">BCP</option>
                                <option value="BBVA">BBVA</option>
                                <option value="INTERBANK">INTERBANK</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="monto" class="form-label">Monto</label>
                            <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Detalles de Caja -->
    <div class="modal fade" id="modalDetallesCaja" tabindex="-1" aria-labelledby="modalDetallesCajaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesCajaLabel">Detalles de Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Detalles de caja -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Cargar y refrescar la tabla de gastos
function cargarGastos(page = 1, search = '') {
    $.get('/gastos?page=' + page + '&search=' + encodeURIComponent(search), function(data) {
        let tbody = '';
        if (data.data.length === 0) {
            tbody = '<tr><td colspan="7" class="text-center">No hay gastos registrados.</td></tr>';
        } else {
            data.data.forEach(function(gasto) {
                tbody += `<tr>
                    <td>${gasto.fecha}</td>
                    <td>${gasto.descripcion}</td>
                    <td>${gasto.metodo_pago}</td>
                    <td>S/. ${parseFloat(gasto.monto).toFixed(2)}</td>
                    <td>${gasto.user ? gasto.user.name : ''}</td>
                    <td>${gasto.estado}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="eliminarGasto(${gasto.id})"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
            });
        }
        $(".table-responsive tbody").eq(1).html(tbody);
        // Paginación (simple)
        // Puedes mejorar esto con links reales si lo deseas
    });
}

// Guardar gasto
$('#formGasto').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/gastos',
        method: 'POST',
        data: $(this).serialize(),
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        success: function(resp) {
            $('#modalGasto').modal('hide');
            $('#formGasto')[0].reset();
            cargarGastos();
        },
        error: function(xhr) {
            alert('Error al guardar gasto');
        }
    });
});

// Eliminar gasto
function eliminarGasto(id) {
    if (!confirm('¿Eliminar gasto?')) return;
    $.ajax({
        url: '/gastos/' + id,
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        success: function(resp) {
            cargarGastos();
        },
        error: function(xhr) {
            alert('Error al eliminar gasto');
        }
    });
}

// Inicializar
$(document).ready(function() {
    cargarGastos();
    // Puedes agregar aquí la lógica para los filtros, gráficos, etc.
});
</script>
@endsection 