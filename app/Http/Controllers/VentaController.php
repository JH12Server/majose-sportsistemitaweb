<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fecha_fin = $request->input('fecha_fin', now()->format('Y-m-d'));
        $query = Sale::with(['user', 'details.product'])
            ->whereDate('created_at', '>=', $fecha_inicio)
            ->whereDate('created_at', '<=', $fecha_fin);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%") ;
                  })
                  ->orWhere('total', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%")
                  ->orWhereHas('details.product', function($q3) use ($search) {
                      $q3->where('nombre', 'like', "%$search%") ;
                  });
            });
        }
        $ventas = $query->orderByDesc('created_at')->paginate(10)->appends($request->all());
        $monto_total = $query->sum('total');
        return view('admin.ventas.index', compact('ventas', 'fecha_inicio', 'fecha_fin', 'monto_total'));
    }

    public function create()
    {
        $productos = Servicio::all();
        $clientes = User::where('role', 'user')->get();
        return view('admin.ventas.create', compact('productos', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'producto_id' => 'required|exists:servicios,id',
            'cantidad' => 'required|integer|min:1',
            'monto' => 'required|numeric',
            'estado' => 'required',
        ]);
        $venta = Sale::create([
            'user_id' => $request->user_id,
            'total' => $request->monto,
            'status' => $request->estado,
        ]);
        SaleDetail::create([
            'sale_id' => $venta->id,
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'precio' => $request->monto,
        ]);
        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente');
    }

    public function edit(Sale $venta)
    {
        $productos = Servicio::all();
        $clientes = User::where('role', 'user')->get();
        $detalle = $venta->details->first();
        return view('admin.ventas.edit', compact('venta', 'productos', 'clientes', 'detalle'));
    }

    public function update(Request $request, Sale $venta)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'producto_id' => 'required|exists:servicios,id',
            'cantidad' => 'required|integer|min:1',
            'monto' => 'required|numeric',
            'estado' => 'required',
        ]);
        $venta->update([
            'user_id' => $request->user_id,
            'total' => $request->monto,
            'status' => $request->estado,
        ]);
        $detalle = $venta->details->first();
        if ($detalle) {
            $detalle->update([
                'producto_id' => $request->producto_id,
                'cantidad' => $request->cantidad,
                'precio' => $request->monto,
            ]);
        }
        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente');
    }

    public function destroy(Sale $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente');
    }
} 