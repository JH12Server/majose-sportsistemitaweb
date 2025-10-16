<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gasto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GastoCrud extends Component
{
    public $showModal = false;
    public $fecha;
    public $descripcion;
    public $metodo_pago;
    public $monto;

    protected $rules = [
        'fecha' => 'required|date',
        'descripcion' => 'required|string',
        'metodo_pago' => 'required|string',
        'monto' => 'required|numeric|min:0',
    ];

    public function render()
    {
        return view('livewire.gasto-crud', [
            'gastos' => Gasto::with('user')->orderByDesc('fecha')->get()
        ]);
    }

    public function showModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();
        Gasto::create([
            'user_id' => Auth::id() ?? 1,
            'fecha' => $this->fecha,
            'descripcion' => $this->descripcion,
            'metodo_pago' => $this->metodo_pago,
            'monto' => $this->monto,
        ]);
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->fecha = date('Y-m-d');
        $this->descripcion = '';
        $this->metodo_pago = '';
        $this->monto = '';
    }
}
