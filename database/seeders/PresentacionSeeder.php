<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Presentacion;

class PresentacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nombre' => 'Caja', 'descripcion' => 'Presentación en caja', 'estado' => true],
            ['nombre' => 'Bolsa', 'descripcion' => 'Presentación en bolsa', 'estado' => true],
            ['nombre' => 'Paquete', 'descripcion' => 'Presentación en paquete', 'estado' => true],
            ['nombre' => 'Unidad', 'descripcion' => 'Presentación individual', 'estado' => true],
            ['nombre' => 'Botella', 'descripcion' => 'Presentación en botella', 'estado' => false],
            ['nombre' => 'Lata', 'descripcion' => 'Presentación en lata', 'estado' => true],
        ];
        foreach ($data as $item) {
            Presentacion::create($item);
        }
    }
}
