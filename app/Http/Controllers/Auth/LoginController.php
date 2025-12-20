<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Log para debug
            Log::info('Usuario autenticado', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'isCustomer' => $user->isCustomer(),
            ]);

            // Redirigir según el rol específico
            if ($user->isCustomer()) {
                // Forzar redirección al dashboard de cliente tras login
                Log::info('Redirigiendo a customer.dashboard');
                return redirect()->route('customer.dashboard');
            }

            if ($user->isAdmin()) {
                return redirect()->intended(route('dashboard'));
            }

            if ($user->isSupervisor()) {
                return redirect()->intended(route('dashboard'));
            }

            if ($user->isWorker()) {
                return redirect()->intended(route('worker.dashboard'));
            }

            // Fallback genérico - no debería llegar aquí
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
} 