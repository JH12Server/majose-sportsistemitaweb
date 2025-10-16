<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumenCaja extends Model
{
    protected $table = 'resumen_caja';
    protected $fillable = [
        'fecha',
        'ventas_efectivo',
        'ventas_otros',
        'gastos_efectivo',
        'gastos_otros',
        'saldo_efectivo',
        'saldo_otros',
        'saldo_total',
    ];
}
