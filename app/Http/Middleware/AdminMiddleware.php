<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'supervisor'])) {
            abort(403, 'Acceso denegado. Solo administradores y supervisores pueden acceder a esta sección.');
        }

        return $next($request);
    }
} 