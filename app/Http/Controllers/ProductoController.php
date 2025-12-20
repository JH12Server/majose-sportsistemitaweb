<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        // Mostrar productos del catálogo (modelo Product) en el listado admin
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('id', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%")
                  ->orWhere('brand', 'like', "%$search%")
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
            'category' => 'nullable|string',
            'brand' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'base_price' => $request->input('base_price'),
            'category' => $request->input('category'),
            'brand' => $request->input('brand'),
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
    }

    public function edit(Product $producto)
    {
        $categorias = Category::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Product $producto)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
            'category' => 'nullable|string',
            'brand' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $producto->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'base_price' => $request->input('base_price'),
            'category' => $request->input('category'),
            'brand' => $request->input('brand'),
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : $producto->is_active,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
    }
} 