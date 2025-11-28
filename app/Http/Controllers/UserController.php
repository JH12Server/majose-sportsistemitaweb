<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Support different user lists: clientes (users), trabajadores (workers), proveedores (providers), or all
        $type = $request->input('type', 'clientes');
        $query = User::query();
        if ($type === 'clientes') {
            $query->where('role', 'user');
        } elseif ($type === 'trabajadores') {
            $query->where('role', 'worker');
        } elseif ($type === 'proveedores') {
            $query->where('role', 'provider');
        } // else 'all' -> no role filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('direccion', 'like', "%$search%")
                  ->orWhere('cedula', 'like', "%$search%")
                  ->orWhere('tipo_persona', 'like', "%$search%") ;
            });
        }
        $users = $query->paginate(10)->appends($request->all());
        return view('admin.usuarios.index', compact('users', 'type'));
    }

    /**
     * Clients-only view (separate from admin usuarios view).
     */
    public function clients(Request $request)
    {
        $query = User::where('role', 'user');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('direccion', 'like', "%$search%")
                  ->orWhere('cedula', 'like', "%$search%")
                  ->orWhere('tipo_persona', 'like', "%$search%");
            });
        }
        $users = $query->paginate(10)->appends($request->all());
        // Return a simplified clients view (no admin tabs)
        return view('clientes.index', compact('users'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return redirect('/dashboard');
        //return view('admin.usuarios.index', compact('users'));
       // return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente');
    }


    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$usuario->id,
            'role' => 'required',
        ]);
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        if ($request->filled('password')) {
            $usuario->update(['password' => Hash::make($request->password)]);
        }
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }

    public function perfil(Request $request)
    {
        $user = $request->user();
        return view('perfil.edit', compact('user'));
    }

    public function actualizarPerfil(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|max:2048',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos_perfil', 'public');
            $user->foto = $path;
        }
        $user->save();
        return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente');
    }

    public function configuracion(Request $request)
    {
        $user = $request->user();
        return view('perfil.configuracion', compact('user'));
    }

    public function actualizarConfiguracion(Request $request)
    {
        try {
            $user = $request->user();
            
            // Validación de los datos del formulario
            $validatedData = $request->validate([
                'password_actual' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'password_actual.required' => 'Debes ingresar tu contraseña actual',
                'password.required' => 'La nueva contraseña es obligatoria',
                'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
                'password.confirmed' => 'Las contraseñas no coinciden',
            ]);

            // Verificar que la contraseña actual sea correcta
            if (!\Hash::check($request->password_actual, $user->password)) {
                return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta'])->withInput();
            }

            // Verificar que la nueva contraseña sea diferente a la actual
            if (Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'La nueva contraseña debe ser diferente a la actual'])->withInput();
            }

            // Actualizar la contraseña
            $user->password = Hash::make($request->password);
            
            // Si el usuario es administrador, permitir cambiar el rol
            if ($request->filled('role') && $user->role === 'admin') {
                $user->role = $request->role;
            }
            
            if ($user->save()) {
                // Cerrar sesión después de cambiar la contraseña para mayor seguridad
                Auth::logout();
                
                // Redirigir al login con un mensaje de éxito
                return redirect()->route('login')
                    ->with('success', 'Tu contraseña ha sido actualizada correctamente. Por favor inicia sesión nuevamente.');
            }
            
            return back()->with('error', 'No se pudo actualizar la contraseña. Por favor, inténtalo de nuevo.');
            
        } catch (\Exception $e) {
            // Log del error para depuración
            \Log::error('Error al actualizar la contraseña: ' . $e->getMessage());
            
            // Verificar si es un error de validación
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return back()->withErrors($e->validator->errors())->withInput();
            }
            
            return back()->with('error', 'Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde.');
        }
    }
} 