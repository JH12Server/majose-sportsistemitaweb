<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Category::paginate(10);
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:categories,nombre',
        ]);
        Category::create($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente');
    }

    public function edit(Category $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Category $categoria)
    {
        $request->validate([
            'nombre' => 'required|unique:categories,nombre,'.$categoria->id,
        ]);
        $categoria->update($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(Category $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente');
    }
} 