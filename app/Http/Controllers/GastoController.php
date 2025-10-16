<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gasto::with('user');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('descripcion', 'like', "%$search%")
                  ->orWhere('metodo_pago', 'like', "%$search%")
                  ->orWhere('monto', 'like', "%$search%")
                  ->orWhere('estado', 'like', "%$search%") ;
            });
        }
        $gastos = $query->orderByDesc('fecha')->paginate(10)->appends($request->all());
        return response()->json($gastos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'required',
            'metodo_pago' => 'required',
            'monto' => 'required|numeric',
        ]);
        $gasto = Gasto::create([
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'metodo_pago' => $request->metodo_pago,
            'monto' => $request->monto,
            'user_id' => Auth::id(),
            'estado' => 'aprobado',
        ]);
        return response()->json(['success' => true, 'gasto' => $gasto]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gasto $gasto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gasto $gasto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gasto $gasto)
    {
        $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'required',
            'metodo_pago' => 'required',
            'monto' => 'required|numeric',
            'estado' => 'required',
        ]);
        $gasto->update($request->only(['fecha', 'descripcion', 'metodo_pago', 'monto', 'estado']));
        return response()->json(['success' => true, 'gasto' => $gasto]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        return response()->json(['success' => true]);
    }
}
