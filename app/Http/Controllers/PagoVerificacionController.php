<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PagoVerificacionController extends Controller
{
    public function index()
    {
        // Obtener todas las órdenes con sus items y usuario
        $orders = Order::with(['user', 'items.product'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pagos-verificacion', compact('orders'));
    }

    public function edit($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.pagos-verificacion-edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string',
            'customer_notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('pagos.verificacion')
            ->with('success', 'Pago actualizado correctamente');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('pagos.verificacion')
            ->with('success', 'Pago eliminado correctamente');
    }

    public function search(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%$search%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('items.product', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->orderByDesc('created_at')->get();

        return view('admin.pagos-verificacion', compact('orders'));
    }
}
