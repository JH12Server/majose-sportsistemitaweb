<?php

namespace App\Http\Controllers;

use App\Models\Presentacion;
use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    public function index()
    {
        $presentaciones = Presentacion::paginate(10);
        return view('admin.presentaciones.index', compact('presentaciones'));
    }

    public function create()
    {
        return view('admin.presentaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:presentaciones,nombre',
            'descripcion' => 'nullable',
            'estado' => 'required|boolean',
        ]);
        Presentacion::create($request->only(['nombre', 'descripcion', 'estado']));
        return redirect()->route('presentaciones.index')->with('success', 'Presentación creada correctamente');
    }

    public function edit(Presentacion $presentacion)
    {
        return view('admin.presentaciones.edit', compact('presentacion'));
    }

    public function update(Request $request, Presentacion $presentacion)
    {
        $request->validate([
            'nombre' => 'required|unique:presentaciones,nombre,'.$presentacion->id,
            'descripcion' => 'nullable',
            'estado' => 'required|boolean',
        ]);
        $presentacion->update($request->only(['nombre', 'descripcion', 'estado']));
        return redirect()->route('presentaciones.index')->with('success', 'Presentación actualizada correctamente');
    }

    public function destroy(Presentacion $presentacion)
    {
        $presentacion->delete();
        return redirect()->route('presentaciones.index')->with('success', 'Presentación eliminada correctamente');
    }
} 