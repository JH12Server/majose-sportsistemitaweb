<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\FloatingIcons;

class CustomerDashboard extends Component
{
    public $recentOrders;
    public $stats;
    public $featuredProducts = [];
    public $categories = [];

    public function mount()
    {
        if (auth()->check()) {
            $this->loadStats();
            $this->loadRecentOrders();
            $this->loadCatalogData();
        }
    }

    public function loadCatalogData()
    {
        $this->featuredProducts = Product::where('featured', true)
            ->take(8)
            ->get();
            
        $this->categories = \App\Models\Category::orderBy('nombre')
            ->get();
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

    public function showProductDetail($productId)
    {
        $this->dispatch('show-product-detail', $productId)->to(FloatingIcons::class);
    }

    public function addToCart($productId)
    {
        $this->dispatch('addToCart', $productId)->to(FloatingIcons::class);
    }
}