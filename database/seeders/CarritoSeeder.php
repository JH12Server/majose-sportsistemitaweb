<?php

namespace Database\Seeders;

use App\Models\Carrito;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CarritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener un usuario y un producto existentes
        $user = User::whereHas('roles', function ($query) {
            $query->where('type', 'customer');
        })->first();

        $product = Product::where('is_active', true)->first();

        if ($user && $product) {
            // Crear un item de prueba en el carrito
            Carrito::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $product->base_price,
                'size' => 'M',
                'color' => 'Negro',
                'text' => 'Mi Personalización',
                'font' => 'Arial',
                'text_color' => '#FFFFFF',
                'additional_specifications' => 'Personalización especial',
                'reference_file' => null, // Sin imagen inicialmente
            ]);

            $this->command->info('Item de carrito de prueba creado exitosamente');
        } else {
            $this->command->warn('No se encontró usuario o producto para crear item de prueba');
        }
    }
}
