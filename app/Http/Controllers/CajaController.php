<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        // Aquí se cargarán los datos para las gráficas y tablas
        return view('caja.gastos');
    }
}
