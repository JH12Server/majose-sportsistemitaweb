<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nombre' => 'Deportes', 'descripcion' => 'Artículos deportivos', 'estado' => 1],
            ['nombre' => 'Tecnología', 'descripcion' => 'Gadgets y electrónicos', 'estado' => 1],
            ['nombre' => 'Ropa', 'descripcion' => 'Vestimenta y accesorios', 'estado' => 1],
            ['nombre' => 'Hogar', 'descripcion' => 'Productos para el hogar', 'estado' => 1],
            ['nombre' => 'Salud', 'descripcion' => 'Productos de salud y bienestar', 'estado' => 1],
            ['nombre' => 'Juguetes', 'descripcion' => 'Juguetes para niños', 'estado' => 1],
            ['nombre' => 'Libros', 'descripcion' => 'Libros y revistas', 'estado' => 1],
        ];
        foreach ($data as $item) {
            Category::create($item);
        }
    }
}
