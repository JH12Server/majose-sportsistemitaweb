<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="card p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5><i class="bi bi-cash-coin"></i> Resumen de Caja</h5>
            <button class="btn btn-danger" wire:click="showModal"><i class="bi bi-plus"></i> Añadir Resumen de Caja</button>
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resumenes as $resumen)
                        <tr>
                            <td>{{ $resumen->fecha }}</td>
                            <td>S/. {{ number_format($resumen->ventas_efectivo, 2) }}</td>
                            <td>S/. {{ number_format($resumen->ventas_otros, 2) }}</td>
                            <td>S/. {{ number_format($resumen->gastos_efectivo, 2) }}</td>
                            <td>S/. {{ number_format($resumen->gastos_otros, 2) }}</td>
                            <td>S/. {{ number_format($resumen->saldo_efectivo, 2) }}</td>
                            <td>S/. {{ number_format($resumen->saldo_otros, 2) }}</td>
                            <td>S/. {{ number_format($resumen->saldo_total, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary me-1" wire:click="editResumen({{ $resumen->id }})"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-danger" wire:click="deleteResumen({{ $resumen->id }})"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal Añadir Resumen de Caja -->
    <div class="modal fade @if($showModal) show d-block @endif" tabindex="-1" style="@if($showModal) display:block; background:rgba(0,0,0,0.5); @endif" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@if($editMode) Editar Resumen de Caja @else Añadir Resumen de Caja @endif</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="@if($editMode) update @else save @endif">
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" wire:model.defer="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="ventas_efectivo" class="form-label">Ventas (Efectivo)</label>
                            <input type="number" step="0.01" class="form-control" id="ventas_efectivo" wire:model.defer="ventas_efectivo" required>
                        </div>
                        <div class="mb-3">
                            <label for="ventas_otros" class="form-label">Ventas (Otros)</label>
                            <input type="number" step="0.01" class="form-control" id="ventas_otros" wire:model.defer="ventas_otros" required>
                        </div>
                        <div class="mb-3">
                            <label for="gastos_efectivo" class="form-label">Gastos (Efectivo)</label>
                            <input type="number" step="0.01" class="form-control" id="gastos_efectivo" wire:model.defer="gastos_efectivo" required>
                        </div>
                        <div class="mb-3">
                            <label for="gastos_otros" class="form-label">Gastos (Otros)</label>
                            <input type="number" step="0.01" class="form-control" id="gastos_otros" wire:model.defer="gastos_otros" required>
                        </div>
                        <div class="mb-3">
                            <label for="saldo_efectivo" class="form-label">Saldo Efectivo</label>
                            <input type="number" step="0.01" class="form-control" id="saldo_efectivo" wire:model.defer="saldo_efectivo" required>
                        </div>
                        <div class="mb-3">
                            <label for="saldo_otros" class="form-label">Saldo Otros</label>
                            <input type="number" step="0.01" class="form-control" id="saldo_otros" wire:model.defer="saldo_otros" required>
                        </div>
                        <div class="mb-3">
                            <label for="saldo_total" class="form-label">Saldo Total</label>
                            <input type="number" step="0.01" class="form-control" id="saldo_total" wire:model.defer="saldo_total" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" wire:click="closeModal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">@if($editMode) Actualizar @else Guardar @endif</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
