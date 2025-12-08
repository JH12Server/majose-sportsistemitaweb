<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class BillboardUploader extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $images = [];
    public $uploaded = [];
    
    // Product fields
    public $name = '';
    public $base_price = '';
    public $description = '';
    public $category = '';
    public $brand = '';
    public $material = '';
    public $available_sizes = '';
    public $available_colors = '';
    public $allows_customization = false;
    public $production_days = '';
    public $is_active = true;
    public $featured = false;
    public $selectedImage = null;
    // For CRUD listing
    public $perPage = 10;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $editingProductId = null;

    // File inputs for create/edit modals
    public $main_image_file;
    public $edit_main_image_file;

    protected $rules = [
        'images.*' => 'image|max:5120',
        'name' => 'required|string|max:255',
        'base_price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'category' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'material' => 'nullable|string|max:255',
        'available_sizes' => 'nullable|string',
        'available_colors' => 'nullable|string',
        'allows_customization' => 'boolean',
        'production_days' => 'nullable|integer|min:0',
        'is_active' => 'boolean',
        'featured' => 'boolean',
    ];

    protected $createRules = [
        'name' => 'required|string|max:255',
        'base_price' => 'required|numeric|min:0',
        'category' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'main_image_file' => 'nullable|image|max:5120',
        'available_sizes' => 'nullable|string',
        'available_colors' => 'nullable|string',
        'allows_customization' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $updateRules = [
        'name' => 'required|string|max:255',
        'base_price' => 'required|numeric|min:0',
        'category' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'edit_main_image_file' => 'nullable|image|max:5120',
        'available_sizes' => 'nullable|string',
        'available_colors' => 'nullable|string',
        'allows_customization' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function updatedImages()
    {
        $this->validateOnly('images');
    }

    public function upload()
    {
        $this->validate(['images.*' => 'image|max:5120']);

        foreach ($this->images as $file) {
            $path = $file->store('products', 'public');
            $this->uploaded[] = $path;

            // Copy to public/storage for accessibility
            try {
                $storagePath = storage_path('app/public/' . $path);
                $publicPath = public_path('storage/' . $path);
                $publicDir = dirname($publicPath);
                if (!file_exists($publicDir)) {
                    @mkdir($publicDir, 0755, true);
                }
                if (file_exists($storagePath) && !file_exists($publicPath)) {
                    @copy($storagePath, $publicPath);
                }
            } catch (\Exception $e) {
                // ignore
            }
        }

        $this->images = [];
        $this->dispatch('notify', message: 'Imágenes subidas correctamente');
    }

    public function selectImage($path)
    {
        $this->selectedImage = $path;
        $this->resetFormFields();
    }

    private function resetFormFields()
    {
        $this->name = '';
        $this->base_price = '';
        $this->description = '';
        $this->category = '';
        $this->brand = '';
        $this->material = '';
        $this->available_sizes = '';
        $this->available_colors = '';
        $this->allows_customization = false;
        $this->production_days = '';
        $this->is_active = true;
        $this->featured = false;
    }

    public function cancelSelection()
    {
        $this->selectedImage = null;
        $this->resetFormFields();
    }

    public function createProduct()
    {
        $this->validate($this->createRules);

        // Parse sizes and colors
        $sizes = array_values(array_filter(array_map('trim', explode(',', $this->available_sizes ?? ''))));
        $colors = array_values(array_filter(array_map('trim', explode(',', $this->available_colors ?? ''))));

        try {
            $images = [];
            $mainImagePath = $this->selectedImage;
            // if file uploaded via modal, store it
            if ($this->main_image_file) {
                $mainImagePath = $this->main_image_file->store('products', 'public');
                $images[] = $mainImagePath;
                // copy to public/storage
                try {
                    $storagePath = storage_path('app/public/' . $mainImagePath);
                    $publicPath = public_path('storage/' . $mainImagePath);
                    $publicDir = dirname($publicPath);
                    if (!file_exists($publicDir)) { @mkdir($publicDir, 0755, true); }
                    if (file_exists($storagePath) && !file_exists($publicPath)) { @copy($storagePath, $publicPath); }
                } catch (\Exception $e) {}
            } elseif ($this->selectedImage) {
                $images[] = $this->selectedImage;
            }

            $product = Product::create([
                'name' => $this->name,
                'base_price' => $this->base_price,
                'description' => $this->description ?? '',
                'images' => $images,
                'main_image' => $mainImagePath,
                'category' => $this->category ?? '',
                'brand' => $this->brand ?? '',
                'material' => $this->material ?? '',
                'available_sizes' => $sizes,
                'available_colors' => $colors,
                'allows_customization' => (bool) $this->allows_customization,
                'production_days' => $this->production_days ?: null,
                'is_active' => (bool) $this->is_active,
                'featured' => (bool) $this->featured,
            ]);

            // Remove from uploaded list
            $this->uploaded = array_values(array_filter($this->uploaded, function($p) {
                return $p !== $this->selectedImage;
            }));

            $this->selectedImage = null;
            $this->resetFormFields();

            $this->dispatch('notify', message: 'Producto creado: ' . $product->name);
            // close modal/state
            $this->showCreateModal = false;
            $this->main_image_file = null;
        } catch (\Exception $e) {
            $this->dispatch('notify-error', message: 'Error al crear producto: ' . $e->getMessage());
        }
    }

    public function showCreate()
    {
        $this->resetFormFields();
        $this->showCreateModal = true;
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->editingProductId = $id;
        $this->name = $product->name;
        $this->base_price = $product->base_price;
        $this->description = $product->description;
        $this->category = $product->category;
        $this->brand = $product->brand;
        $this->material = $product->material;
        $this->available_sizes = is_array($product->available_sizes) ? implode(', ', $product->available_sizes) : ($product->available_sizes ?? '');
        $this->available_colors = is_array($product->available_colors) ? implode(', ', $product->available_colors) : ($product->available_colors ?? '');
        $this->allows_customization = (bool)$product->allows_customization;
        $this->is_active = (bool)$product->is_active;
        $this->selectedImage = $product->main_image ?? null;
        $this->showEditModal = true;
    }

    public function updateProduct()
    {
        $this->validate($this->updateRules);
        $product = Product::findOrFail($this->editingProductId);

        $images = $product->images ?? [];
        $mainImagePath = $product->main_image ?? null;

        if ($this->edit_main_image_file) {
            $path = $this->edit_main_image_file->store('products', 'public');
            $mainImagePath = $path;
            if (!in_array($path, $images)) { $images[] = $path; }
            try {
                $storagePath = storage_path('app/public/' . $path);
                $publicPath = public_path('storage/' . $path);
                $publicDir = dirname($publicPath);
                if (!file_exists($publicDir)) { @mkdir($publicDir, 0755, true); }
                if (file_exists($storagePath) && !file_exists($publicPath)) { @copy($storagePath, $publicPath); }
            } catch (\Exception $e) {}
        }

        $sizes = array_values(array_filter(array_map('trim', explode(',', $this->available_sizes ?? ''))));
        $colors = array_values(array_filter(array_map('trim', explode(',', $this->available_colors ?? ''))));

        $product->update([
            'name' => $this->name,
            'base_price' => $this->base_price,
            'description' => $this->description,
            'category' => $this->category,
            'brand' => $this->brand,
            'material' => $this->material,
            'available_sizes' => $sizes,
            'available_colors' => $colors,
            'allows_customization' => (bool)$this->allows_customization,
            'is_active' => (bool)$this->is_active,
            'images' => $images,
            'main_image' => $mainImagePath,
        ]);

        $this->showEditModal = false;
        $this->editingProductId = null;
        $this->edit_main_image_file = null;
        $this->dispatch('notify', message: 'Producto actualizado');
    }

    public function confirmDelete($id)
    {
        $this->editingProductId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteProduct()
    {
        $product = Product::findOrFail($this->editingProductId);
        $product->delete();
        $this->showDeleteModal = false;
        $this->editingProductId = null;
        $this->dispatch('notify', message: 'Producto eliminado');
    }

    public function render()
    {
        $products = Product::orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.billboard-uploader', [
            'products' => $products,
        ]);
    }
}
