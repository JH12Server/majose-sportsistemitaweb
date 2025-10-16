<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class AdminVentas extends Component
{
    public $search = '';
    public $status = '';
    public $fechaInicio = '';
    public $fechaFin = '';

    public function mount()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado');
        }
    }

    public function actualizarStatus($ventaId, $nuevoStatus)
    {
        $venta = Sale::findOrFail($ventaId);
        $venta->update(['status' => $nuevoStatus]);
        session()->flash('status', 'Estado de venta actualizado correctamente.');
    }

    public function render()
    {
        $ventas = Sale::with(['user', 'details.product'])
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->when($this->fechaInicio, function($query) {
                $query->whereDate('created_at', '>=', $this->fechaInicio);
            })
            ->when($this->fechaFin, function($query) {
                $query->whereDate('created_at', '<=', $this->fechaFin);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $estadisticas = [
            'total_ventas' => Sale::count(),
            'ventas_hoy' => Sale::whereDate('created_at', today())->count(),
            'total_ingresos' => Sale::sum('total'),
            'ingresos_hoy' => Sale::whereDate('created_at', today())->sum('total'),
        ];

        return view('livewire.admin-ventas', [
            'ventas' => $ventas,
            'estadisticas' => $estadisticas
        ]);
    }
} 