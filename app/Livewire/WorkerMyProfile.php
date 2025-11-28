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
use Livewire\WithPagination;

class WorkerMyProfile extends Component
{
    use WithPagination, WithFileUploads;

    protected $listeners = [
        'show-error' => 'showError',
        'show-success' => 'showSuccess'
    ];

    public $user;
    public $editMode = false;
    public $showPasswordModal = false;
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';
    public $workerStats = [];
    public $recentOrders = [];

    // Avatar upload and primitive form props (mirror CustomerMyProfile)
    public $avatar;
    public $name = '';
    public $email = '';
    public $phone = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'avatar' => 'nullable|image|max:2048',
        'user.role' => 'nullable|string|max:255',
        'user.work_area' => 'nullable|string|max:255',
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

        $this->loadWorkerStats();
        $this->loadRecentOrders();

        // Emitir evento de carga para inicializar JS si es necesario
        $this->dispatch('livewire:load');
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
        if (!$this->editMode) {
            $this->user = Auth::user();
            $this->avatar = null;
            // reset primitive props to persisted values
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
            // If email empty, keep previous
            if (empty($this->email)) {
                $this->email = Auth::user()->email;
            }

            // Build rules dynamically (email unique only if changed)
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

            // Prepare data
            $data = [
                'name' => trim((string) $this->name),
                'email' => $this->email,
            ];
            if (Schema::hasColumn('users', 'phone')) {
                $data['phone'] = $this->phone;
            }

            if (Schema::hasColumn('users', 'role')) {
                $data['role'] = $this->user->role ?? null;
            }
            if (Schema::hasColumn('users', 'work_area')) {
                $data['work_area'] = $this->user->work_area ?? null;
            }

            // Handle avatar
            if ($this->avatar) {
                if (!empty($this->user->foto) && Storage::disk('public')->exists($this->user->foto)) {
                    Storage::disk('public')->delete($this->user->foto);
                }
                $path = $this->avatar->store('avatars', 'public');
                $data['foto'] = $path;
            }

            // Persist
            $userId = Auth::id();
            User::where('id', $userId)->update($data);
            Log::info('Perfil de trabajador (nuevo) actualizado', [
                'user_id' => $this->user->id,
            ]);

            $this->editMode = false;
            $this->user = User::find($userId);
            $this->name = $this->user->name ?? '';
            $this->email = $this->user->email ?? '';
            if (Schema::hasColumn('users', 'phone')) {
                $this->phone = $this->user->phone ?? '';
            }
            $this->avatar = null;
            $this->dispatch('show-success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al actualizar el perfil');
            Log::error('Error updating worker my profile: ' . $e->getMessage());
        }
    }

    public function showError($message)
    {
        session()->flash('error', $message);
    }

    public function showSuccess($message)
    {
        session()->flash('success', $message);
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
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|confirmed',
            'confirmPassword' => 'required',
        ], [
            'currentPassword.required' => 'La contraseña actual es obligatoria.',
            'newPassword.required' => 'La nueva contraseña es obligatoria.',
            'newPassword.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'newPassword.confirmed' => 'Las contraseñas no coinciden.',
            'confirmPassword.required' => 'Confirma la nueva contraseña.',
        ]);

        try {
            if (!Hash::check($this->currentPassword, $this->user->password)) {
                $this->dispatch('show-error', 'La contraseña actual es incorrecta');
                return;
            }

            $this->user->update([
                'password' => Hash::make($this->newPassword)
            ]);

            Log::info('Contraseña de trabajador (nuevo) actualizada', [
                'user_id' => $this->user->id,
                'user_name' => $this->user->name
            ]);

            $this->closePasswordModal();
            $this->dispatch('show-success', 'Contraseña actualizada correctamente');
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al actualizar la contraseña');
            Log::error('Error updating worker password (new): ' . $e->getMessage());
        }
    }

    public function loadWorkerStats()
    {
        $workerId = $this->user->id;

        $this->workerStats = [
            'total_orders_managed' => Order::where('status_updated_by', $workerId)->count(),
            'orders_completed' => Order::where('status_updated_by', $workerId)
                ->where('status', 'delivered')
                ->count(),
            'orders_in_production' => Order::where('status_updated_by', $workerId)
                ->where('status', 'production')
                ->count(),
            'urgent_orders_handled' => Order::where('status_updated_by', $workerId)
                ->where('priority', 'urgent')
                ->count(),
            'average_completion_time' => $this->calculateAverageCompletionTime($workerId),
            'orders_this_month' => Order::where('status_updated_by', $workerId)
                ->whereMonth('status_updated_at', now()->month)
                ->count(),
        ];
    }

    private function calculateAverageCompletionTime($workerId)
    {
        $orders = Order::where('status_updated_by', $workerId)
            ->where('status', 'delivered')
            ->whereNotNull('delivered_at')
            ->get();

        if ($orders->count() === 0) {
            return 0;
        }

        $totalDays = $orders->sum(function($order) {
            return $order->created_at->diffInDays($order->delivered_at);
        });

        return round($totalDays / $orders->count(), 1);
    }

    public function loadRecentOrders()
    {
        $this->recentOrders = Order::with(['items.product', 'user'])
            ->where('status_updated_by', $this->user->id)
            ->orderBy('status_updated_at', 'desc')
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
        return view('livewire.worker-my-profile', [
            'statusColors' => $this->statusColor,
            'statuses' => $this->statuses,
        ]);
    }
}
