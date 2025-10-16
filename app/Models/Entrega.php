<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
        'tipo_producto',
        'cedula',
        'tipo_persona',
        'activo',
    ];
}
