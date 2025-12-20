<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirigir usuario a Google OAuth
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Manejar callback de Google y crear/actualizar usuario
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error al conectar con Google. Por favor, intenta de nuevo.');
        }

        // Buscar usuario por google_id o email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Actualizar google_id si no lo tiene
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            // Crear nuevo usuario
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt('google-auth-' . $googleUser->getId()),
                'role' => 'customer', // Por defecto clientes
                'is_active' => true,
            ]);
        }

        // Iniciar sesión
        Auth::login($user);

        // Redirigir según rol
        if ($user->isWorker()) {
            return redirect()->intended('/worker/dashboard');
        } else {
            return redirect()->intended('/customer/dashboard');
        }
    }
}
