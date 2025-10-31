<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class WorkerProfile extends Component
{
    use WithPagination;

    public $user;
    public $editMode = false;
    public $showPasswordModal = false;
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';
    public $workerStats = [];
    public $recentOrders = [];

    protected $rules = [
        'user.name' => 'required|string|max:255',
        'user.email' => 'required|email|max:255',
        'user.phone' => 'nullable|string|max:20',
        'user.role' => 'nullable|string|max:255',
        'user.work_area' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'user.name.required' => 'El nombre es obligatorio.',
        'user.email.required' => 'El email es obligatorio.',
        'user.email.email' => 'El email debe ser válido.',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadWorkerStats();
        $this->loadRecentOrders();
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
        if (!$this->editMode) {
            // Recargar datos del usuario si se cancela la edición
            $this->user = Auth::user();
        }
    }

    public function saveProfile()
    {
        try {
            $this->validate();

            $this->user->save();

            Log::info('Perfil de trabajador actualizado', [
                'user_id' => $this->user->id,
                'changes' => $this->user->getChanges()
            ]);

            $this->editMode = false;
            $this->dispatch('show-success', 'Perfil actualizado correctamente');

        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al actualizar el perfil');
            Log::error('Error updating worker profile: ' . $e->getMessage());
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
            // Verificar contraseña actual
            if (!Hash::check($this->currentPassword, $this->user->password)) {
                $this->dispatch('show-error', 'La contraseña actual es incorrecta');
                return;
            }

            // Actualizar contraseña
            $this->user->update([
                'password' => Hash::make($this->newPassword)
            ]);

            Log::info('Contraseña de trabajador actualizada', [
                'user_id' => $this->user->id,
                'user_name' => $this->user->name
            ]);

            $this->closePasswordModal();
            $this->dispatch('show-success', 'Contraseña actualizada correctamente');

        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al actualizar la contraseña');
            Log::error('Error updating worker password: ' . $e->getMessage());
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
        return view('livewire.worker-profile', [
            'statusColors' => $this->statusColor,
            'statuses' => $this->statuses,
        ]);
    }
}
