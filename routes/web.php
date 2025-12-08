<?php

use Illuminate\Support\Facades\Route;

// Fallback route to serve product images directly from storage if public/storage link is missing
Route::get('/product-image/{path}', function ($path) {
    // only use the basename to avoid directory traversal
    $filename = basename($path);
    if (empty($filename)) {
        abort(404);
    }

    $pathOnDisk = storage_path('app/public/products/' . $filename);
    if (!file_exists($pathOnDisk)) {
        abort(404);
    }

    return response()->file($pathOnDisk, [
        'Cache-Control' => 'public, max-age=31536000'
    ]);
})->where('path', '.*')->name('product.image');

use Illuminate\Support\Facades\Auth;

// Rutas de autenticación personalizadas
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Livewire\CatalogoProductos;
use App\Livewire\Carrito;
use App\Livewire\Checkout;
use App\Livewire\HistorialCompras;
use App\Livewire\AdminVentas;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PresentacionController;

// Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Register
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.dashboard');
    })->middleware('admin')->name('dashboard');

    Route::get('/productos', function () {
        return view('layouts.productos');
    })->name('productos');

    // Página separada de Clientes (vista distinta a Usuarios)
    Route::get('/clientes', [App\Http\Controllers\UserController::class, 'clients'])->name('clientes.index');

    Route::get('/ventas', function () {
        return view('layouts.ventas');
    })->name('ventas');

    Route::get('/pedidos', function () {
        return view('layouts.pedidos');
    })->name('pedidos');

    Route::get('/envios', function () {
        return view('layouts.envios');
    })->name('envios');

    Route::get('/configuracion', [App\Http\Controllers\UserController::class, 'configuracion'])->name('configuracion');
    Route::post('/configuracion', [App\Http\Controllers\UserController::class, 'actualizarConfiguracion'])->name('configuracion.actualizar');

    Route::get('/carrito', Carrito::class)->name('carrito');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/historial', HistorialCompras::class)->name('historial');
    Route::resource('categorias', App\Http\Controllers\CategoriaController::class);
    Route::resource('marcas', MarcaController::class);
    Route::resource('presentaciones', PresentacionController::class)->parameters([
        'presentaciones' => 'presentacion'
    ]);
    Route::resource('productos', App\Http\Controllers\ProductoController::class);
    Route::resource('compras', App\Http\Controllers\CompraController::class);
    Route::resource('ventas', App\Http\Controllers\VentaController::class);
    Route::resource('usuarios', App\Http\Controllers\UserController::class);
    Route::resource('entregas', App\Http\Controllers\EntregaController::class);
    Route::get('/caja/gastos', [App\Http\Controllers\CajaController::class, 'index'])->name('caja.gastos');
    Route::get('/gastos', [App\Http\Controllers\GastoController::class, 'index'])->name('gastos.index');
    Route::post('/gastos', [App\Http\Controllers\GastoController::class, 'store'])->name('gastos.store');
    Route::put('/gastos/{gasto}', [App\Http\Controllers\GastoController::class, 'update'])->name('gastos.update');
    Route::delete('/gastos/{gasto}', [App\Http\Controllers\GastoController::class, 'destroy'])->name('gastos.destroy');
    Route::get('/perfil', [App\Http\Controllers\UserController::class, 'perfil'])->name('perfil');
    Route::post('/perfil', [App\Http\Controllers\UserController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    
    // Rutas solo para administradores
    Route::middleware('admin')->group(function () {
        Route::get('/admin/ventas', AdminVentas::class)->name('admin.ventas');
        Route::get('/admin/reports', App\Livewire\AdminReports::class)->name('admin.reports');
        
        // Admin Profile & Users Management
        Route::get('/admin/profile', function () {
            return view('admin.profile');
        })->name('admin.profile');
        Route::get('/admin/users', function () {
            return view('admin.users');
        })->name('admin.users');
        
        // Roles CRUD (full resource routes under /admin/roles)
        Route::resource('/admin/roles', App\Http\Controllers\RoleController::class)->names('roles');
        
        // Billboard admin page to manage uploads for catalog
        Route::get('/admin/billboard', function () {
            return view('admin.billboard');
        })->name('admin.billboard');

        // Product CRUD routes used by admin (named as requested)
        Route::post('/products/create', [App\Http\Controllers\ProductController::class, 'store'])->name('products.create');
        Route::post('/products/update/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
        Route::post('/products/delete/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.delete');
    });
});

// Redirigir '/' según el rol del usuario
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    $user = Auth::user();
    if ($user->isCustomer()) {
        return redirect()->route('customer.dashboard');
    } elseif ($user->isWorker()) {
        return redirect()->route('worker.dashboard');
    }
    
    return redirect()->route('login');
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Rutas para clientes
    Route::middleware('customer')->group(function () {
        Route::get('/customer/dashboard', App\Livewire\CustomerDashboard::class)->name('customer.dashboard');
        Route::get('/customer/catalog', App\Livewire\CustomerCatalog::class)->name('customer.catalog');
        Route::get('/customer/cart', App\Livewire\CustomerCart::class)->name('customer.cart');
        Route::get('/customer/orders', App\Livewire\CustomerOrders::class)->name('customer.orders');
        Route::get('/customer/checkout', App\Livewire\CustomerCheckout::class)->name('customer.checkout');
        Route::get('/customer/order-confirmation/{orderId}', App\Livewire\OrderConfirmation::class)->name('customer.order-confirmation');
        Route::get('/customer/my-profile', App\Livewire\CustomerMyProfile::class)->name('customer.my-profile');
    });

    // Rutas para trabajadores
    Route::middleware('worker')->group(function () {
        Route::get('/worker/dashboard', App\Livewire\WorkerDashboard::class)->name('worker.dashboard');
        Route::get('/worker/orders', App\Livewire\WorkerOrders::class)->name('worker.orders');
        Route::get('/worker/orders/{order}', function($order) {
            return view('worker.order-detail', compact('order'));
        })->name('worker.orders.show');
        Route::get('/worker/products', function() {
            return view('worker.products');
        })->name('worker.products');
        Route::get('/worker/users', function() {
            return view('worker.users');
        })->name('worker.users');
        // Use WorkerMyProfile for the worker '/worker/profile' route so it matches the customer profile UI
        Route::get('/worker/profile', App\Livewire\WorkerMyProfile::class)->name('worker.profile');
        Route::get('/worker/my-profile', App\Livewire\WorkerMyProfile::class)->name('worker.my-profile');
    });

    // Rutas legacy (mantener compatibilidad)
    Route::get('/catalogo', CatalogoProductos::class)->name('catalogo');
    Route::get('/carrito', Carrito::class)->name('carrito');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/historial', HistorialCompras::class)->name('historial');
    
    // Rutas solo para administradores
    Route::middleware('admin')->group(function () {
        Route::get('/admin/ventas', AdminVentas::class)->name('admin.ventas');
    });

    // PayPal endpoints (authenticated)
    Route::post('/paypal/create-order', [App\Http\Controllers\PayPalController::class, 'createOrder'])->name('paypal.create');
    Route::post('/paypal/capture-order/{orderId}', [App\Http\Controllers\PayPalController::class, 'captureOrder'])->name('paypal.capture');
});

// Ruta de prueba para diagnóstico
Route::get('/prueba', function () {
    return 'Funciona';
});

//payment
Route::get('paywithpaypal', [App\Http\Controllers\PaymentController::class, 'payWithPayPal'])->name('paywithpaypal');