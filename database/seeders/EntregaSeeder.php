<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entrega;

class EntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nombre' => 'Juan Pérez', 'direccion' => 'Calle 123', 'tipo_producto' => 'Ropa', 'cedula' => '12345678', 'tipo_persona' => 'Cliente', 'activo' => true],
            ['nombre' => 'Ana Gómez', 'direccion' => 'Av. Central 456', 'tipo_producto' => 'Electrónica', 'cedula' => '87654321', 'tipo_persona' => 'Proveedor', 'activo' => true],
            ['nombre' => 'Carlos Ruiz', 'direccion' => 'Calle Falsa 789', 'tipo_producto' => 'Juguetes', 'cedula' => '11223344', 'tipo_persona' => 'Cliente', 'activo' => false],
            ['nombre' => 'María López', 'direccion' => 'Av. Libertad 101', 'tipo_producto' => 'Libros', 'cedula' => '44332211', 'tipo_persona' => 'Proveedor', 'activo' => true],
            ['nombre' => 'Pedro Sánchez', 'direccion' => 'Calle Sur 202', 'tipo_producto' => 'Deportes', 'cedula' => '55667788', 'tipo_persona' => 'Cliente', 'activo' => true],
        ];
        foreach ($data as $item) {
            Entrega::create($item);
        }
    }
}
