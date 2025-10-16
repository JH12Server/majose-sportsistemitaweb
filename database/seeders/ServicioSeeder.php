<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nombre' => 'Entrenamiento Personal', 'descripcion' => 'Sesión de entrenamiento', 'imagen' => 'entrenamiento.jpg', 'precio' => 30.00, 'categoria' => 'Deportes', 'estado' => true],
            ['nombre' => 'Reparación de Bicicleta', 'descripcion' => 'Servicio de reparación', 'imagen' => 'bicicleta.jpg', 'precio' => 15.00, 'categoria' => 'Deportes', 'estado' => true],
            ['nombre' => 'Clases de Yoga', 'descripcion' => 'Clase grupal', 'imagen' => 'yoga.jpg', 'precio' => 20.00, 'categoria' => 'Salud', 'estado' => true],
            ['nombre' => 'Soporte Técnico', 'descripcion' => 'Asistencia técnica', 'imagen' => 'soporte.jpg', 'precio' => 25.00, 'categoria' => 'Tecnología', 'estado' => true],
            ['nombre' => 'Corte de Cabello', 'descripcion' => 'Servicio de peluquería', 'imagen' => 'corte.jpg', 'precio' => 10.00, 'categoria' => 'Hogar', 'estado' => false],
            ['nombre' => 'Clases de Natación', 'descripcion' => 'Clase para niños', 'imagen' => 'natacion.jpg', 'precio' => 18.00, 'categoria' => 'Deportes', 'estado' => true],
        ];
        foreach ($data as $item) {
            Servicio::create($item);
        }
    }
}
