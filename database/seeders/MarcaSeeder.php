<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nombre' => 'Nike', 'descripcion' => 'Marca deportiva internacional', 'estado' => true],
            ['nombre' => 'Adidas', 'descripcion' => 'Ropa y calzado deportivo', 'estado' => true],
            ['nombre' => 'Sony', 'descripcion' => 'Electrónica y tecnología', 'estado' => true],
            ['nombre' => 'Apple', 'descripcion' => 'Tecnología e innovación', 'estado' => true],
            ['nombre' => 'Samsung', 'descripcion' => 'Electrodomésticos y móviles', 'estado' => true],
            ['nombre' => 'Puma', 'descripcion' => 'Ropa deportiva', 'estado' => false],
            ['nombre' => 'LG', 'descripcion' => 'Electrodomésticos', 'estado' => true],
        ];
        foreach ($data as $item) {
            Marca::create($item);
        }
    }
}
