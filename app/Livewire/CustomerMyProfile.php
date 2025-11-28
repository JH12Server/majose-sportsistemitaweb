<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;

class CustomerMyProfile extends Component
{
    protected $listeners = [
        'password-updated' => 'handlePasswordUpdated',
        'show-error' => 'showError',
        'show-success' => 'showSuccess'
    ];
    
    public function handlePasswordUpdated()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    
    public function showError($message)
    {
        session()->flash('error', $message);
    }
    
    public function showSuccess($message)
    {
        session()->flash('success', $message);
    }
    public $user;
    public $editMode = false;
    public $showPasswordModal = false;
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';
    public $customerStats = [];
    public $recentOrders = [];
    public $avatar; // uploaded image
    // Primitive props for editing
    public $name = '';
    public $email = '';
    public $phone = '';

    use WithFileUploads;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'avatar' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.email' => 'El email debe ser válido.',
        'email.unique' => 'Este email ya está en uso.',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        // seed primitive props
        $this->name = $this->user->name ?? '';
        $this->email = $this->user->email ?? '';
        if (Schema::hasColumn('users', 'phone')) {
            $this->phone = $this->user->phone ?? '';
        }
        $this->loadCustomerStats();
        $this->loadRecentOrders();
        
        // Emitir evento de carga para inicializar JavaScript
        $this->dispatch('livewire:load');
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
        if (!$this->editMode) {
            $this->user = Auth::user();
            $this->avatar = null;
            // reset form fields to persisted values
            $this->name = $this->user->name ?? '';
            $this->email = $this->user->email ?? '';
            if (Schema::hasColumn('users', 'phone')) {
                $this->phone = $this->user->phone ?? '';
            }
        }
    }

    public function saveProfile()
    {
        try {
            // If email is empty, keep previous one
            if (empty($this->email)) {
                $this->email = Auth::user()->email;
            }
            // Build validation rules dynamically (email unique only if changed)
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|max:2048',
            ];

            $currentEmail = Auth::user()->email;
            if (!empty($this->email) && $this->email !== $currentEmail) {
                $rules['email'] .= '|unique:users,email,' . $this->user->id;
            }

            $this->validate($rules, $this->messages);
            // Prepare data to persist
            $data = [
                'name' => trim((string) $this->name),
                'email' => $this->email,
            ];
            if (Schema::hasColumn('users', 'phone')) {
                $data['phone'] = $this->phone;
            }

            // Handle avatar upload
            if ($this->avatar) {
                // delete old avatar if exists
                if (!empty($this->user->foto) && Storage::disk('public')->exists($this->user->foto)) {
                    Storage::disk('public')->delete($this->user->foto);
                }
                $path = $this->avatar->store('avatars', 'public');
                $data['foto'] = $path;
            }

            // Persist using direct query to avoid any stale model issues
            $userId = Auth::id();
            User::where('id', $userId)->update($data);
            Log::info('Perfil de cliente (nuevo) actualizado', [
                'user_id' => $this->user->id,
                'changes' => $this->user->getChanges()
            ]);
            $this->editMode = false;
            $this->user = User::find($userId);
            // sync back to form fields
            $this->name = $this->user->name ?? '';
            $this->email = $this->user->email ?? '';
            if (Schema::hasColumn('users', 'phone')) {
                $this->phone = $this->user->phone ?? '';
            }
            $this->avatar = null;
            $this->dispatch('show-success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al actualizar el perfil');
            Log::error('Error updating customer my profile: ' . $e->getMessage());
        }
    }

    public function showChangePasswordModal()
    {
        $this->showPasswordModal = true;
        $this->currentPassword = '';
        $this->newPassword = '';
        $this->confirmPassword = '';
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->currentPassword = '';
        $this->newPassword = '';
        $this->confirmPassword = '';
    }

    public function changePassword()
    {
        // Validar los campos
        $this->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|different:currentPassword',
            'confirmPassword' => 'required|string|same:newPassword',
        ], [
            'currentPassword.required' => 'La contraseña actual es obligatoria.',
            'newPassword.required' => 'La nueva contraseña es obligatoria.',
            'newPassword.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'newPassword.different' => 'La nueva contraseña debe ser diferente a la actual.',
            'confirmPassword.required' => 'Por favor confirma tu nueva contraseña.',
            'confirmPassword.same' => 'Las contraseñas no coinciden.',
        ]);

        try {
            // Verificar que la contraseña actual sea correcta
            if (!Hash::check($this->currentPassword, $this->user->password)) {
                $this->addError('currentPassword', 'La contraseña actual es incorrecta');
                return;
            }

            // Verificar que la nueva contraseña sea diferente a la actual
            if (Hash::check($this->newPassword, $this->user->password)) {
                $this->addError('newPassword', 'La nueva contraseña debe ser diferente a la actual');
                return;
            }

            // Actualizar la contraseña
            $this->user->password = Hash::make($this->newPassword);
            
            // Guardar los cambios
            if ($this->user->save()) {
                // Cerrar el modal y mostrar mensaje de éxito
                $this->closePasswordModal();
                $this->dispatch('show-success', 'Contraseña actualizada correctamente. Por tu seguridad, se cerrará la sesión.');
                
                // Cerrar sesión después de 2 segundos
                $this->dispatch('password-updated');
                
                // Registrar el cambio de contraseña
                Log::info('Contraseña de cliente actualizada con éxito', [
                    'user_id' => $this->user->id,
                    'user_name' => $this->user->name,
                    'ip' => request()->ip()
                ]);
            } else {
                $this->dispatch('show-error', 'No se pudo actualizar la contraseña. Por favor, inténtalo de nuevo.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar la contraseña del cliente: ' . $e->getMessage(), [
                'user_id' => $this->user->id,
                'exception' => $e
            ]);
            $this->dispatch('show-error', 'Ocurrió un error al actualizar la contraseña. Por favor, inténtalo de nuevo.');
        }
    }

    public function loadCustomerStats()
    {
        $customerId = $this->user->id;

        $this->customerStats = [
            'total_orders' => Order::where('user_id', $customerId)->count(),
            'orders_delivered' => Order::where('user_id', $customerId)
                ->where('status', 'delivered')
                ->count(),
            'orders_in_progress' => Order::where('user_id', $customerId)
                ->whereIn('status', ['pending','review','production','ready','shipped'])
                ->count(),
            'last_month_orders' => Order::where('user_id', $customerId)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->count(),
            'spent_total' => (float) Order::where('user_id', $customerId)->sum('total_amount'),
        ];
    }

    public function loadRecentOrders()
    {
        $this->recentOrders = Order::with(['items.product'])
            ->where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function getStatusColorProperty()
    {
        return [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'review' => 'bg-blue-100 text-blue-800',
            'production' => 'bg-orange-100 text-orange-800',
            'ready' => 'bg-green-100 text-green-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
    }

    public function getStatusesProperty()
    {
        return [
            'pending' => 'Pendiente',
            'review' => 'En Revisión',
            'production' => 'En Producción',
            'ready' => 'Listo para Envío',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
        ];
    }

    public function render()
    {
        return view('livewire.customer-my-profile', [
            'statusColors' => $this->statusColor,
            'statuses' => $this->statuses,
        ]);
    }
}
