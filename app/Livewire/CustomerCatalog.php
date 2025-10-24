<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $material = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $showFilters = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'material' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedMaterial()
    {
        $this->resetPage();
    }

    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->material = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function render()
    {
        $query = Product::where('is_active', true);

        // Aplicar filtros
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        if ($this->material) {
            $query->where('material', 'like', '%' . $this->material . '%');
        }

        if ($this->minPrice) {
            $query->where('base_price', '>=', $this->minPrice);
        }

        if ($this->maxPrice) {
            $query->where('base_price', '<=', $this->maxPrice);
        }

        // Aplicar ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);

        $products = $query->paginate(12);

        // Obtener opciones para filtros
        $categories = Product::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        $materials = Product::where('is_active', true)
            ->distinct()
            ->pluck('material')
            ->filter()
            ->sort()
            ->values();

        $priceRange = Product::where('is_active', true)
            ->selectRaw('MIN(base_price) as min_price, MAX(base_price) as max_price')
            ->first();

        return view('livewire.customer-catalog', [
            'products' => $products,
            'categories' => $categories,
            'materials' => $materials,
            'priceRange' => $priceRange,
        ]);
    }
}