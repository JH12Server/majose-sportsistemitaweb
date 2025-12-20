<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%");
            })->orWhereHas('items.product', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('order_number', 'like', "%$search%");
        }
        $entregas = $query->paginate(10)->appends($request->all());
        return view('admin.entregas.index', compact('entregas'));
    }

    
    /**
     * Display the specified resource.
     */
    public function show(Order $entrega)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $entrega)
    {
        return view('admin.entregas.edit', compact('entrega'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $entrega)
    {
        $request->validate([
            'status' => 'required|in:pending,review,production,ready,shipped,delivered,cancelled',
        ]);
        $entrega->update([
            'status' => $request->status,
        ]);
        return redirect()->route('entregas.index')->with('success', 'Estado del pedido actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $entrega)
    {
        $entrega->delete();
        return redirect()->route('entregas.index')->with('success', 'Pedido eliminado correctamente');
    }
}
