<?php

use Illuminate\Support\Facades\Route;
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
    })->name('dashboard');

    Route::get('/productos', function () {
        return view('layouts.productos');
    })->name('productos');

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
    });
});

// Redirigir '/' al login si no está autenticado
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('catalogo');
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::get('/catalogo', CatalogoProductos::class)->name('catalogo');
    Route::get('/carrito', Carrito::class)->name('carrito');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/historial', HistorialCompras::class)->name('historial');
    // Rutas solo para administradores
    Route::middleware('admin')->group(function () {
        Route::get('/admin/ventas', AdminVentas::class)->name('admin.ventas');
    });
});

// Ruta de prueba para diagnóstico
Route::get('/prueba', function () {
    return 'Funciona';
});

