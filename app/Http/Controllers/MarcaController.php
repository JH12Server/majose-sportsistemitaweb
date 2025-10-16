<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::paginate(10);
        return view('admin.marcas.index', compact('marcas'));
    }

    public function create()
    {
        return view('admin.marcas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:marcas,nombre',
            'descripcion' => 'nullable',
            'estado' => 'required|boolean',
        ]);
        Marca::create($request->only(['nombre', 'descripcion', 'estado']));
        return redirect()->route('marcas.index')->with('success', 'Marca creada correctamente');
    }

    public function edit(Marca $marca)
    {
        return view('admin.marcas.edit', compact('marca'));
    }

    public function update(Request $request, Marca $marca)
    {
        $request->validate([
            'nombre' => 'required|unique:marcas,nombre,'.$marca->id,
            'descripcion' => 'nullable',
            'estado' => 'required|boolean',
        ]);
        $marca->update($request->only(['nombre', 'descripcion', 'estado']));
        return redirect()->route('marcas.index')->with('success', 'Marca actualizada correctamente');
    }

    public function destroy(Marca $marca)
    {
        $marca->delete();
        return redirect()->route('marcas.index')->with('success', 'Marca eliminada correctamente');
    }
} 