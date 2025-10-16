<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Servicio::with('category');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%")
                  ->orWhere('id', 'like', "%$search%")
                  ->orWhere('categoria', 'like', "%$search%")
                  ->orWhere('estado', 'like', "%$search%")
                ;
            });
        }
        $productos = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Category::all();
        return view('admin.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'categoria_id' => 'required|exists:categories,id',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
        ]);
        Servicio::create($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
    }

    public function edit(Servicio $producto)
    {
        $categorias = Category::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Servicio $producto)
    {
        $request->validate([
            'nombre' => 'required',
            'categoria_id' => 'required|exists:categories,id',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
        ]);
        $producto->update($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Servicio $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
    }
} 