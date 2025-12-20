<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class AdminUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $role_filter = '';
    public $showCreateForm = false;
    public $editingUserId = null;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';

    // Available roles (match /usuarios/{id}/edit)
    protected $availableRoles = ['user', 'worker', 'provider', 'admin'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:user,worker,provider,admin',
    ];

    protected $rulesUpdate = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'role' => 'required|in:user,worker,provider,admin',
        'password' => 'nullable|string|min:8',
    ];

    public function updatedSearch()
    {
        $this->applyFilters();
    }

    public function updatedRoleFilter()
    {
        $this->applyFilters();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->editingUserId = null;
    }

    public function createUser()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->dispatch('notify', message: 'Usuario creado exitosamente');
        $this->closeCreateForm();
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
            $this->dispatch('notify', message: 'Usuario eliminado exitosamente');
        } else {
            $this->dispatch('notify-error', message: 'No puedes eliminar tu propia cuenta');
        }
    }

    public function editUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->editingUserId = $userId;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
        }
    }

    public function updateUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|in:user,worker,provider,admin',
            'password' => 'nullable|string|min:8',
        ];

        $user = User::find($this->editingUserId);
        if ($this->email !== $user->email) {
            $rules['email'] = 'required|email|unique:users,email';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $this->dispatch('notify', message: 'Usuario actualizado exitosamente');
        $this->editingUserId = null;
        $this->resetForm();
    }

    public function cancelEdit()
    {
        $this->editingUserId = null;
        $this->resetForm();
    }

    public function applyFilters()
    {
        // simple helper to trigger a filter action (keeps Livewire behavior consistent with a "Filtrar" button)
        $this->resetPage();
        $this->search = trim((string) $this->search);
        $this->role_filter = $this->role_filter ?: '';
    }

    public function render()
    {
        $query = User::query();

        $searchRaw = trim((string) $this->search);
        if ($searchRaw !== '') {
            $search = $searchRaw;
            $lower = mb_strtolower($search);

            $query->where(function($q) use ($search, $lower) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');

                // Map common Spanish role words to internal role values
                $map = [
                    'cliente' => 'user',
                    'clientes' => 'user',
                    'trabajador' => 'worker',
                    'trabajadores' => 'worker',
                    'proveedor' => 'provider',
                    'proveedores' => 'provider',
                    'administrador' => 'admin',
                    'admin' => 'admin',
                    'diseñador' => 'designer',
                    'bordador' => 'embroiderer',
                ];

                foreach ($map as $spanish => $roleVal) {
                    if (strpos($lower, $spanish) !== false) {
                        $q->orWhere('role', $roleVal);
                    }
                }
            });
        }

        if (!empty($this->role_filter)) {
            $query->where('role', $this->role_filter);
        }

        $users = $query->paginate(15);

        // provide dynamic roles list from DB
        $rolesList = User::select('role')->distinct()->pluck('role')->filter()->values()->toArray();

        return view('livewire.admin-users', [
            'users' => $users,
            'roles' => $rolesList,
        ]);
    }
}
