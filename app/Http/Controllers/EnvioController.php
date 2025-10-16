<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;
use App\Models\Envio;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Envio::with(['venta', 'cliente'])->get();
        return view('admin.envios.index', compact('envios'));
    }

    public function create()
    {
        $ventas = Sale::all();
        $clientes = User::where('role', 'user')->get();
        return view('admin.envios.create', compact('ventas', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:sales,id',
            'cliente_id' => 'required|exists:users,id',
            'direccion' => 'required',
            'fecha' => 'required|date',
            'estado' => 'required',
        ]);
        Envio::create($request->all());
        return redirect()->route('envios.index')->with('success', 'Envío registrado correctamente');
    }

    public function edit(Envio $envio)
    {
        $ventas = Sale::all();
        $clientes = User::where('role', 'user')->get();
        return view('admin.envios.edit', compact('envio', 'ventas', 'clientes'));
    }

    public function update(Request $request, Envio $envio)
    {
        $request->validate([
            'pedido_id' => 'required|exists:sales,id',
            'cliente_id' => 'required|exists:users,id',
            'direccion' => 'required',
            'fecha' => 'required|date',
            'estado' => 'required',
        ]);
        $envio->update($request->all());
        return redirect()->route('envios.index')->with('success', 'Envío actualizado correctamente');
    }

    public function destroy(Envio $envio)
    {
        $envio->delete();
        return redirect()->route('envios.index')->with('success', 'Envío eliminado correctamente');
    }
} 