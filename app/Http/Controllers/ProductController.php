<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'base_price' => 'required|numeric',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|max:5120',
            'available_colors' => 'nullable|string',
            'available_sizes' => 'nullable|string',
            'allows_customization' => 'nullable|boolean',
            'custom_text' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        // handle image upload
        $images = [];
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            // try to copy to public/storage
            try {
                $storagePath = storage_path('app/public/' . $path);
                $publicPath = public_path('storage/' . $path);
                $publicDir = dirname($publicPath);
                if (!file_exists($publicDir)) {
                    @mkdir($publicDir, 0755, true);
                }
                if (file_exists($storagePath) && !file_exists($publicPath)) {
                    @copy($storagePath, $publicPath);
                }
            } catch (\Exception $e) {}

            $images[] = $path;
            $data['main_image'] = $path;
        }

        $data['images'] = $images;
        $data['available_sizes'] = array_values(array_filter(array_map('trim', explode(',', $data['available_sizes'] ?? ''))));
        $data['available_colors'] = array_values(array_filter(array_map('trim', explode(',', $data['available_colors'] ?? ''))));
        $data['allows_customization'] = isset($data['allows_customization']) ? (bool)$data['allows_customization'] : false;
        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : true;

        $product = Product::create($data);

        return redirect()->back()->with('success', 'Producto creado correctamente');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'base_price' => 'required|numeric',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|max:5120',
            'available_colors' => 'nullable|string',
            'available_sizes' => 'nullable|string',
            'allows_customization' => 'nullable|boolean',
            'custom_text' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            try {
                $storagePath = storage_path('app/public/' . $path);
                $publicPath = public_path('storage/' . $path);
                $publicDir = dirname($publicPath);
                if (!file_exists($publicDir)) {
                    @mkdir($publicDir, 0755, true);
                }
                if (file_exists($storagePath) && !file_exists($publicPath)) {
                    @copy($storagePath, $publicPath);
                }
            } catch (\Exception $e) {}
            $data['main_image'] = $path;

            // add to images array if not present
            $images = $product->images ?? [];
            if (!in_array($path, $images)) {
                $images[] = $path;
            }
            $data['images'] = $images;
        }

        $data['available_sizes'] = array_values(array_filter(array_map('trim', explode(',', $data['available_sizes'] ?? ''))));
        $data['available_colors'] = array_values(array_filter(array_map('trim', explode(',', $data['available_colors'] ?? ''))));
        $data['allows_customization'] = isset($data['allows_customization']) ? (bool)$data['allows_customization'] : false;
        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : true;

        $product->update($data);

        return redirect()->back()->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->back()->with('success', 'Producto eliminado');
    }
}
