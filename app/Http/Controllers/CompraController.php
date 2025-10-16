<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fecha_fin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $compras = Sale::whereBetween('created_at', [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $monto_total = Sale::whereBetween('created_at', [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'])->sum('total');

        return view('admin.compras.index', compact('compras', 'fecha_inicio', 'fecha_fin', 'monto_total'));
    }

    // Métodos para editar y eliminar pueden agregarse aquí
} 