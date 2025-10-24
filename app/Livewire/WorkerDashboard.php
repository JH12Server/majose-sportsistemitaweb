<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WorkerDashboard extends Component
{
    public $selectedPeriod = 'today';
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function updatedSelectedPeriod()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = Auth::user();
        $dateFilter = $this->getDateFilter();

        // Estadísticas generales
        $this->stats = [
            'total_orders' => Order::whereBetween('created_at', $dateFilter)->count(),
            'pending_orders' => Order::where('status', 'pending')->whereBetween('created_at', $dateFilter)->count(),
            'in_production' => Order::where('status', 'production')->whereBetween('created_at', $dateFilter)->count(),
            'ready_for_delivery' => Order::where('status', 'ready')->whereBetween('created_at', $dateFilter)->count(),
            'completed_orders' => Order::where('status', 'delivered')->whereBetween('created_at', $dateFilter)->count(),
            'total_revenue' => Order::where('status', 'delivered')->whereBetween('created_at', $dateFilter)->sum('total_amount'),
        ];

        // Estadísticas específicas por rol
        if ($user->role === 'admin' || $user->role === 'supervisor') {
            $this->stats['assigned_orders'] = Order::where('assigned_worker_id', $user->id)->whereBetween('created_at', $dateFilter)->count();
            $this->stats['urgent_orders'] = Order::where('priority', 'urgent')->whereBetween('created_at', $dateFilter)->count();
        }

        if ($user->role === 'designer') {
            $this->stats['design_orders'] = Order::where('status', 'review')->whereBetween('created_at', $dateFilter)->count();
        }

        if ($user->role === 'embroiderer') {
            $this->stats['embroidery_orders'] = Order::where('status', 'production')->whereBetween('created_at', $dateFilter)->count();
        }

        if ($user->role === 'delivery_manager') {
            $this->stats['delivery_orders'] = Order::whereIn('status', ['ready', 'shipped'])->whereBetween('created_at', $dateFilter)->count();
        }
    }

    private function getDateFilter()
    {
        $now = now();
        
        return match($this->selectedPeriod) {
            'today' => [$now->startOfDay(), $now->endOfDay()],
            'week' => [$now->startOfWeek(), $now->endOfWeek()],
            'month' => [$now->startOfMonth(), $now->endOfMonth()],
            'year' => [$now->startOfYear(), $now->endOfYear()],
            default => [$now->startOfDay(), $now->endOfDay()]
        };
    }

    public function getRecentOrdersProperty()
    {
        $user = Auth::user();
        
        $query = Order::with(['user', 'assignedWorker', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10);

        // Filtrar por rol del trabajador
        if ($user->role === 'designer') {
            $query->where('status', 'review');
        } elseif ($user->role === 'embroiderer') {
            $query->where('status', 'production');
        } elseif ($user->role === 'delivery_manager') {
            $query->whereIn('status', ['ready', 'shipped']);
        } elseif ($user->role !== 'admin' && $user->role !== 'supervisor') {
            $query->where('assigned_worker_id', $user->id);
        }

        return $query->get();
    }

    public function getUrgentOrdersProperty()
    {
        return Order::with(['user', 'assignedWorker'])
            ->where('priority', 'urgent')
            ->whereIn('status', ['pending', 'review', 'production'])
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();
    }

    public function getWorkersProperty()
    {
        return User::where('is_active', true)
            ->whereIn('role', ['designer', 'embroiderer', 'delivery_manager'])
            ->get();
    }

    public function render()
    {
        return view('livewire.worker-dashboard', [
            'recentOrders' => $this->recentOrders,
            'urgentOrders' => $this->urgentOrders,
            'workers' => $this->workers,
        ]);
    }
}