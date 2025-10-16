<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'precio',
        'categoria',
        'estado',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class, 'product_id');
    }
} 