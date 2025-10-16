<?php

namespace App\Livewire;

use App\Models\Servicio;
use Livewire\Component;
use Livewire\WithFileUploads;

class ServicioForm extends Component
{
    use WithFileUploads;

    public $servicioId;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $precio;
    public $categoria;
    public $estado = true;
    public $imagen_actual;

    protected $listeners = ['editarServicio' => 'mount'];

    protected function rules()
    {
        return [
            'nombre' => 'required|string|min:3',
            'descripcion' => 'nullable|string',
            'imagen' => $this->servicioId ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'precio' => 'nullable|numeric',
            'categoria' => 'nullable|string',
            'estado' => 'boolean',
        ];
    }

    public function mount($servicioId = null)
    {
        $this->servicioId = $servicioId;
        if ($servicioId) {
            $servicio = Servicio::findOrFail($servicioId);
            $this->nombre = $servicio->nombre;
            $this->descripcion = $servicio->descripcion;
            $this->precio = $servicio->precio;
            $this->categoria = $servicio->categoria;
            $this->estado = $servicio->estado;
            $this->imagen_actual = $servicio->imagen;
        } else {
            $this->reset(['nombre','descripcion','precio','categoria','estado','imagen','imagen_actual']);
            $this->estado = true;
        }
    }

    public function save()
    {
        $this->validate();
        $data = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'categoria' => $this->categoria,
            'estado' => $this->estado,
        ];
        if ($this->imagen) {
            $data['imagen'] = $this->imagen->store('servicios','public');
        } elseif ($this->imagen_actual) {
            $data['imagen'] = $this->imagen_actual;
        }
        if ($this->servicioId) {
            Servicio::findOrFail($this->servicioId)->update($data);
            session()->flash('status','Servicio actualizado correctamente.');
        } else {
            Servicio::create($data);
            session()->flash('status','Servicio creado correctamente.');
        }
        $this->emitUp('hideForm');
    }

    public function render()
    {
        return view('livewire.servicio-form');
    }
}
