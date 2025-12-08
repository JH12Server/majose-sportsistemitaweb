<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarritoController extends Controller
{
    public function index()
    {
        $cart = Carrito::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cart->sum(fn($item) => $item->quantity * $item->unit_price);

        return view('customer-cart', compact('cart', 'total'));
    }

    // ... tus otros métodos (add, updateQuantity, remove, etc.) quedan igual ...

    public function saveCustomization(Request $request, $productId)
    {
        $request->validate([
            'size' => 'required',
            'color' => 'required',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048', // máx 5MB
        ]);

        $product = Product::findOrFail($productId);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('customizations', 'public');
            // → guarda: customizations/abc123.jpg
        }

        Carrito::create([
            'user_id'                   => Auth::id(),
            'product_id'                => $product->id,
            'quantity'                  => 1,
            'unit_price'                => $product->price,
            'size'                      => $request->size,
            'color'                     => $request->color,
            'text'                      => $request->text,
            'font'                      => $request->font,
            'text_color'                => $request->text_color,
            'additional_specifications' => $request->additional_specifications,
            'reference_file'            => $filePath, // solo el path relativo
        ]);

        return redirect()->route('customer-cart')
            ->with('success', '¡Camiseta personalizada agregada al carrito!');
    }
}