<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public $productId;
    public $name;
    public $description;
    public $image;
    public $base_price;
    public $category;
    public $is_active = true;
    public $image_actual;

    protected $listeners = ['editarServicio' => 'mount'];

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'description' => 'nullable|string',
            'image' => $this->productId ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'base_price' => 'nullable|numeric',
            'category' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function mount($productId = null)
    {
        $this->productId = $productId;
        if ($productId) {
            $product = Product::findOrFail($productId);
            $this->name = $product->name;
            $this->description = $product->description;
            $this->base_price = $product->base_price;
            $this->category = $product->category;
            $this->is_active = $product->is_active;
            // keep main image path if exists
            $this->image_actual = $product->main_image_url ?? ($product->main_image ?? null);
        } else {
            $this->reset(['name','description','base_price','category','is_active','image','image_actual']);
            $this->is_active = true;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'base_price' => $this->base_price,
            'category' => $this->category,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $path = $this->image->store('products','public');
            // set images as array so Product mutator normalizes it
            $data['images'] = [$path];
            $data['main_image'] = $path;
        } elseif ($this->image_actual) {
            // If image_actual is a full URL, leave images empty; otherwise set to existing path
            if (!empty($this->image_actual)) {
                $data['images'] = [$this->image_actual];
                $data['main_image'] = $this->image_actual;
            }
        }

        if ($this->productId) {
            Product::findOrFail($this->productId)->update($data);
            session()->flash('status','Producto actualizado correctamente.');
        } else {
            Product::create($data);
            session()->flash('status','Producto creado correctamente.');
        }

        $this->emitUp('hideForm');
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}
