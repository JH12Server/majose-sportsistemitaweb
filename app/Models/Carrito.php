<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'unit_price',
        'size',
        'color',
        'text',
        'font',
        'text_color',
        'additional_specifications',
        'reference_file'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor para obtener la URL pública de la imagen de referencia
    public function getReferenceImageUrlAttribute()
    {
        if ($this->reference_file) {
            return Storage::url($this->reference_file); // → /storage/customizations/xxx.jpg
        }
        return null;
    }

    // Accessor opcional: para tener toda la personalización en un array (muy útil en Blade)
    public function getCustomizationAttribute()
    {
        return [
            'size'      => $this->size,
            'color'     => $this->color,
            'text'      => $this->text,
            'font'      => $this->font,
            'text_color'=> $this->text_color,
            'image'     => $this->reference_image_url, // ← aquí está la URL correcta
        ];
    }
}
