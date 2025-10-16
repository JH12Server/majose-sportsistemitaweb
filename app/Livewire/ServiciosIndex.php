<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Servicio;
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
        $query = Servicio::query();

        if ($this->search) {
            $query->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                  ->orWhere('categoria', 'like', '%' . $this->search . '%');
        }

        if ($this->filterCategoria) {
            $query->where('categoria', $this->filterCategoria);
        }

        if ($this->filterEstado !== '') {
            $query->where('estado', $this->filterEstado);
        }

        return $query->paginate(10);
    }

    public function getCategoriasProperty()
    {
        return Servicio::distinct()->pluck('categoria')->filter();
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
            $servicio = Servicio::find($servicioId);
            if ($servicio) {
                $servicio->delete();
                session()->flash('status', 'Servicio eliminado correctamente.');
            }
        }
    }

    public function render()
    {
        return view('livewire.servicios-index');
    }
}
