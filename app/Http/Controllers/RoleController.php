<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $type = $request->input('type', 'all');
        $query = Role::query();
        if ($type === 'system') {
            $query->where('type', 'system');
        } elseif ($type === 'custom') {
            $query->where('type', 'custom');
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        $roles = $query->orderBy('name')->paginate(15)->appends($request->all());
        return view('admin.roles.index', compact('roles', 'type'));
    }

    public function create()
    {
        $type = request('type', 'custom');
        return view('admin.roles.create', compact('type'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'type' => 'nullable|in:system,custom',
        ]);
        $data['type'] = $data['type'] ?? 'custom';
        Role::create($data);
        return redirect()->route('roles.index', ['type' => $data['type']])->with('success', 'Rol creado.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,'.$role->id,
            'description' => 'nullable|string|max:255',
            'type' => 'nullable|in:system,custom',
        ]);
        $data['type'] = $data['type'] ?? $role->type ?? 'custom';
        $role->update($data);
        return redirect()->route('roles.index', ['type' => $data['type']])->with('success', 'Rol actualizado.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado.');
    }
}
