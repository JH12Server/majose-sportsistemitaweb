<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Entrega::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('direccion', 'like', "%$search%")
                  ->orWhere('tipo_producto', 'like', "%$search%")
                  ->orWhere('cedula', 'like', "%$search%")
                  ->orWhere('tipo_persona', 'like', "%$search%") ;
            });
        }
        $entregas = $query->paginate(10)->appends($request->all());
        return view('admin.entregas.index', compact('entregas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.entregas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'direccion' => 'nullable',
            'tipo_producto' => 'nullable',
            'cedula' => 'nullable',
            'tipo_persona' => 'required',
            'activo' => 'nullable|boolean',
        ]);
        Entrega::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'tipo_producto' => $request->tipo_producto,
            'cedula' => $request->cedula,
            'tipo_persona' => $request->tipo_persona,
            'activo' => $request->has('activo') ? $request->activo : true,
        ]);
        return redirect()->route('entregas.index')->with('success', 'Entrega creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Entrega $entrega)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrega $entrega)
    {
        return view('admin.entregas.edit', compact('entrega'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entrega $entrega)
    {
        $request->validate([
            'nombre' => 'required',
            'direccion' => 'nullable',
            'tipo_producto' => 'nullable',
            'cedula' => 'nullable',
            'tipo_persona' => 'required',
            'activo' => 'nullable|boolean',
        ]);
        $entrega->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'tipo_producto' => $request->tipo_producto,
            'cedula' => $request->cedula,
            'tipo_persona' => $request->tipo_persona,
            'activo' => $request->has('activo') ? $request->activo : true,
        ]);
        return redirect()->route('entregas.index')->with('success', 'Entrega actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrega $entrega)
    {
        $entrega->delete();
        return redirect()->route('entregas.index')->with('success', 'Entrega eliminada correctamente');
    }
}
