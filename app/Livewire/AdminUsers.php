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

    // Available roles
    protected $availableRoles = ['admin', 'cliente', 'trabajador', 'proveedor'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:admin,cliente,trabajador,proveedor',
    ];

    protected $rulesUpdate = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'role' => 'required|in:admin,cliente,trabajador,proveedor',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
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
            'role' => 'required|in:admin,cliente,trabajador,proveedor',
        ];

        $user = User::find($this->editingUserId);
        if ($this->email !== $user->email) {
            $rules['email'] = 'required|email|unique:users,email';
        }

        $this->validate($rules);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ]);

        $this->dispatch('notify', message: 'Usuario actualizado exitosamente');
        $this->editingUserId = null;
        $this->resetForm();
    }

    public function cancelEdit()
    {
        $this->editingUserId = null;
        $this->resetForm();
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        if ($this->role_filter) {
            $query->where('role', $this->role_filter);
        }

        $users = $query->paginate(15);

        return view('livewire.admin-users', [
            'users' => $users,
            'roles' => $this->availableRoles,
        ]);
    }
}
