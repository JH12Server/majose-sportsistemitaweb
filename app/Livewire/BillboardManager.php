<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class BillboardManager extends Component
{
    use WithFileUploads;

    public $images = [];
    public $uploaded = [];
    public $name = '';
    public $base_price = '';
    public $description = '';
    public $selectedImage = null;
    public $category = '';
    public $brand = '';
    public $material = '';
    public $available_sizes = '';// comma separated
    public $available_colors = '';// comma separated
    public $allows_customization = false;
    public $production_days = '';
    public $is_active = true;
    public $featured = false;

    protected $rules = [
        'images.*' => 'image|max:5120',
        'name' => 'nullable|string|max:255',
        'base_price' => 'nullable|numeric',
        'description' => 'nullable|string',
        'category' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'material' => 'nullable|string|max:255',
        'available_sizes' => 'nullable|string',
        'available_colors' => 'nullable|string',
        'allows_customization' => 'boolean',
        'production_days' => 'nullable|integer',
        'is_active' => 'boolean',
        'featured' => 'boolean',
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

            // Ensure the file is available under public/storage so Storage::url() works
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
                // ignore - best effort to make image publicly available in dev windows
            }
        }

        // reset temporary upload inputs
        $this->images = [];

        $this->dispatchBrowserEvent('show-notification', ['message' => 'Imágenes subidas correctamente']);
    }

    public function selectImage($path)
    {
        $this->selectedImage = $path;
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

    public function createProductFromSelected()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'category' => 'nullable|string',
        ]);

        $sizes = array_values(array_filter(array_map('trim', explode(',', $this->available_sizes ?? ''))));
        $colors = array_values(array_filter(array_map('trim', explode(',', $this->available_colors ?? ''))));

        $product = Product::create([
            'name' => $this->name,
            'base_price' => $this->base_price,
            'description' => $this->description,
            'images' => [$this->selectedImage],
            'main_image' => $this->selectedImage,
            'category' => $this->category,
            'brand' => $this->brand,
            'material' => $this->material,
            'available_sizes' => $sizes,
            'available_colors' => $colors,
            'allows_customization' => (bool) $this->allows_customization,
            'production_days' => $this->production_days ?: null,
            'is_active' => (bool) $this->is_active,
            'featured' => (bool) $this->featured,
        ]);

        $this->dispatchBrowserEvent('show-notification', ['message' => 'Producto creado: ' . $product->name]);
        // remove from uploaded list
        $this->uploaded = array_values(array_filter($this->uploaded, function($p) {
            return $p !== $this->selectedImage;
        }));
        $this->selectedImage = null;
        $this->name = '';
        $this->base_price = '';
        $this->description = '';
    }

    public function render()
    {
        return view('livewire.billboard-manager');
    }
}
