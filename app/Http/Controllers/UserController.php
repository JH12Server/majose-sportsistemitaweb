<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');
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
        return view('admin.usuarios.index', compact('users'));
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
        $user = $request->user();
        $request->validate([
            'password_actual' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|in:admin,user',
        ]);
        if (!\Hash::check($request->password_actual, $user->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta']);
        }
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        if ($request->filled('role') && $user->role === 'admin') {
            $user->role = $request->role;
        }
        $user->save();
        return redirect()->route('configuracion')->with('success', 'Configuración actualizada correctamente');
    }
} 