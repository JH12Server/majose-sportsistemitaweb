<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Livewire\FloatingIcons;

class CustomerCatalog extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $selectedCategory = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $selectedBrand = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $viewMode = 'grid'; // grid o list
    public $showFilters = false;
    public $selectedProduct = null;
    public $showProductModal = false;
    // Customization fields
    public $customizationText = '';
    public $customizationColor = '';
    public $customizationSize = '';
    public $customizationFile;

    protected $rules = [
        'customizationText' => 'nullable|string|max:500',
        'customizationColor' => 'nullable|string|max:100',
        'customizationSize' => 'nullable|string|max:50',
        'customizationFile' => 'nullable|image|max:4096',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'selectedBrand' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        // Inicializar valores por defecto
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
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

    public function updatedSelectedBrand()
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

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->selectedBrand = '';
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function showProductDetail($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->showProductModal = true;
        // reset customization defaults
        $this->customizationText = '';
        $this->customizationColor = is_array($this->selectedProduct->available_colors) && count($this->selectedProduct->available_colors) ? $this->selectedProduct->available_colors[0] : '';
        $this->customizationSize = is_array($this->selectedProduct->available_sizes) && count($this->selectedProduct->available_sizes) ? $this->selectedProduct->available_sizes[0] : '';
        $this->customizationFile = null;
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->selectedProduct = null;
        $this->customizationText = '';
        $this->customizationColor = '';
        $this->customizationSize = '';
        $this->customizationFile = null;
    }

    public function addToCart($productId)
    {
        $this->dispatch('addToCart', $productId)->to(FloatingIcons::class);
        $this->dispatch('show-success', 'Producto agregado al carrito');
    }

    public function addSelectedToCart()
    {
        if (!$this->selectedProduct) {
            return;
        }

        $customization = [];
        if ($this->selectedProduct->allows_customization) {
            $this->validate();
            $customization = [
                'text' => $this->customizationText,
                'color' => $this->customizationColor,
                'size' => $this->customizationSize,
            ];
            if ($this->customizationFile) {
                $path = $this->customizationFile->store('customizations', 'public');
                $customization['file'] = $path;
            }
        }

        $this->dispatch('addToCart', $this->selectedProduct->id, $customization)->to(FloatingIcons::class);
        $this->dispatch('show-success', 'Producto agregado al carrito con personalización');
        $this->closeProductModal();
    }

    public function getCategoriesProperty()
    {
        return Product::where('is_active', true)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();
    }

    public function getBrandsProperty()
    {
        return Product::where('is_active', true)
            ->select('brand')
            ->distinct()
            ->pluck('brand')
            ->filter()
            ->values()
            ->toArray();
    }

    public function getPriceRangeProperty()
    {
        $prices = Product::where('is_active', true)
            ->selectRaw('MIN(base_price) as min_price, MAX(base_price) as max_price')
            ->first();
        
        return [
            'min' => $prices->min_price ?? 0,
            'max' => $prices->max_price ?? 1000,
        ];
    }

    public function render()
    {
        $query = Product::where('is_active', true);

        // Aplicar filtros
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedCategory) {
            $query->where('category', $this->selectedCategory);
        }

        if ($this->selectedBrand) {
            $query->where('brand', $this->selectedBrand);
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

        return view('livewire.customer-catalog', [
            'products' => $products,
            'categories' => $this->categories,
            'brands' => $this->brands,
            'priceRange' => $this->priceRange,
        ]);
    }
}