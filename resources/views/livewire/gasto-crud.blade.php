<div>
    <div class="card p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5><i class="bi bi-list"></i> Listado de Gastos</h5>
            <button class="btn btn-danger" wire:click="showModal"><i class="bi bi-plus"></i> Añadir Gasto</button>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($gastos as $gasto)
                        <tr>
                            <td>{{ $gasto->fecha }}</td>
                            <td>{{ $gasto->descripcion }}</td>
                            <td>{{ $gasto->metodo_pago ?? '-' }}</td>
                            <td>S/. {{ number_format($gasto->monto, 2) }}</td>
                            <td>{{ $gasto->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay gastos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal Añadir Gasto -->
    <div class="modal fade @if($showModal) show d-block @endif" tabindex="-1" style="@if($showModal) display:block; background:rgba(0,0,0,0.5); @endif" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Gasto</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" wire:model.defer="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" wire:model.defer="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago</label>
                            <select class="form-select" id="metodo_pago" wire:model.defer="metodo_pago" required>
                                <option value="">Seleccione</option>
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
                            <input type="number" step="0.01" class="form-control" id="monto" wire:model.defer="monto" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" wire:click="closeModal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
