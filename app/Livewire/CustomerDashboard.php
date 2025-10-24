<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CustomerDashboard extends Component
{
    public $recentOrders;
    public $stats;
    public $featuredProducts = [];
    public $categories = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentOrders();
        $this->loadCatalogData();
    }

    public function loadStats()
    {
        $user = Auth::user();
        
        $this->stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->whereIn('status', ['pending', 'review'])->count(),
            'in_production' => Order::where('user_id', $user->id)->where('status', 'production')->count(),
            'completed_orders' => Order::where('user_id', $user->id)->where('status', 'delivered')->count(),
            'total_spent' => Order::where('user_id', $user->id)->where('status', 'delivered')->sum('total_amount'),
        ];
    }

    public function loadRecentOrders()
    {
        $this->recentOrders = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.customer-dashboard', [
            'featuredProducts' => $this->featuredProducts,
            'categories' => $this->categories,
            'recentOrders' => $this->recentOrders,
            'stats' => $this->stats,
        ]);
    }

    private function loadCatalogData(): void
    {
        // Productos activos, priorizando los personalizables y más recientes
        $this->featuredProducts = Product::where('is_active', true)
            ->orderByDesc('allows_customization')
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        $this->categories = Product::where('is_active', true)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();
    }
}