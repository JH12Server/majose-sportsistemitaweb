<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ResumenCaja;

class CajaResumenCrud extends Component
{
    public $showModal = false;
    public $fecha;
    public $ventas_efectivo;
    public $ventas_otros;
    public $gastos_efectivo;
    public $gastos_otros;
    public $saldo_efectivo;
    public $saldo_otros;
    public $saldo_total;
    public $editMode = false;
    public $selectedId = null;

    protected $rules = [
        'fecha' => 'required|date',
        'ventas_efectivo' => 'required|numeric',
        'ventas_otros' => 'required|numeric',
        'gastos_efectivo' => 'required|numeric',
        'gastos_otros' => 'required|numeric',
        'saldo_efectivo' => 'required|numeric',
        'saldo_otros' => 'required|numeric',
        'saldo_total' => 'required|numeric',
    ];

    public function render()
    {
        return view('livewire.caja-resumen-crud', [
            'resumenes' => ResumenCaja::orderByDesc('fecha')->get()
        ]);
    }

    public function showModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->selectedId = null;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editMode = false;
        $this->selectedId = null;
    }

    public function editResumen($id)
    {
        $resumen = ResumenCaja::findOrFail($id);
        $this->selectedId = $id;
        $this->fecha = $resumen->fecha;
        $this->ventas_efectivo = $resumen->ventas_efectivo;
        $this->ventas_otros = $resumen->ventas_otros;
        $this->gastos_efectivo = $resumen->gastos_efectivo;
        $this->gastos_otros = $resumen->gastos_otros;
        $this->saldo_efectivo = $resumen->saldo_efectivo;
        $this->saldo_otros = $resumen->saldo_otros;
        $this->saldo_total = $resumen->saldo_total;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        ResumenCaja::create([
            'fecha' => $this->fecha,
            'ventas_efectivo' => $this->ventas_efectivo,
            'ventas_otros' => $this->ventas_otros,
            'gastos_efectivo' => $this->gastos_efectivo,
            'gastos_otros' => $this->gastos_otros,
            'saldo_efectivo' => $this->saldo_efectivo,
            'saldo_otros' => $this->saldo_otros,
            'saldo_total' => $this->saldo_total,
        ]);
        $this->showModal = false;
        $this->resetForm();
    }

    public function update()
    {
        $this->validate();
        if ($this->selectedId) {
            $resumen = ResumenCaja::find($this->selectedId);
            if ($resumen) {
                $resumen->update([
                    'fecha' => $this->fecha,
                    'ventas_efectivo' => $this->ventas_efectivo,
                    'ventas_otros' => $this->ventas_otros,
                    'gastos_efectivo' => $this->gastos_efectivo,
                    'gastos_otros' => $this->gastos_otros,
                    'saldo_efectivo' => $this->saldo_efectivo,
                    'saldo_otros' => $this->saldo_otros,
                    'saldo_total' => $this->saldo_total,
                ]);
            }
        }
        $this->showModal = false;
        $this->editMode = false;
        $this->selectedId = null;
        $this->resetForm();
    }

    public function deleteResumen($id)
    {
        ResumenCaja::find($id)?->delete();
    }

    private function resetForm()
    {
        $this->fecha = date('Y-m-d');
        $this->ventas_efectivo = '';
        $this->ventas_otros = '';
        $this->gastos_efectivo = '';
        $this->gastos_otros = '';
        $this->saldo_efectivo = '';
        $this->saldo_otros = '';
        $this->saldo_total = '';
    }
}
