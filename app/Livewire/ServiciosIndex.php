<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ServiciosIndex extends Component
{
    use WithPagination;

    public $showFormModal = false;
    public $editingServicio = null;
    public $search = '';
    public $filterCategoria = '';
    public $filterEstado = '';

    protected $listeners = ['eliminarServicio' => 'eliminarServicio'];
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategoria()
    {
        $this->resetPage();
    }

    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function getServiciosProperty()
    {
        $query = Product::query()->where('is_active', '>=', 0);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterCategoria) {
            $query->where('category', $this->filterCategoria);
        }

        if ($this->filterEstado !== '') {
            // filterEstado expected as '1' or '0'
            $query->where('is_active', $this->filterEstado);
        }

        return $query->paginate(10);
    }

    public function getCategoriasProperty()
    {
        return Product::distinct()->pluck('category')->filter();
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function showForm($servicioId = null)
    {
        $this->editingServicio = $servicioId;
        $this->showFormModal = true;
    }

    public function hideForm()
    {
        $this->showFormModal = false;
        $this->editingServicio = null;
    }

    public function eliminarServicio($servicioId)
    {
        if ($this->user && $this->user->role === 'admin') {
            $producto = Product::find($servicioId);
            if ($producto) {
                $producto->delete();
                session()->flash('status', 'Producto eliminado correctamente.');
            }
        }
    }

    public function render()
    {
        return view('livewire.servicios-index');
    }
}
