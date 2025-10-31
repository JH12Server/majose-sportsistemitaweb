<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Camiseta Personalizada',
                'description' => 'Camiseta de algodón 100% con bordado personalizado. Perfecta para eventos, equipos deportivos o regalos únicos.',
                'base_price' => 25.00,
                'category' => 'Ropa',
                'brand' => 'Majose',
                'material' => 'Algodón 100%',
                'available_sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'available_colors' => ['Blanco', 'Negro', 'Azul', 'Rojo', 'Verde', 'Gris'],
                'images' => ['products/camiseta-1.jpg', 'products/camiseta-2.jpg'],
                'allows_customization' => true,
                'production_days' => 5,
                'is_active' => true,
                'featured' => true,
            ],
            [
                'name' => 'Gorra Bordada',
                'description' => 'Gorra ajustable con bordado personalizado. Ideal para empresas, equipos deportivos o uso personal.',
                'base_price' => 18.00,
                'category' => 'Accesorios',
                'brand' => 'Majose',
                'material' => 'Algodón y poliéster',
                'available_sizes' => ['Única'],
                'available_colors' => ['Negro', 'Blanco', 'Azul marino', 'Gris', 'Rojo'],
                'images' => ['products/gorra-1.jpg', 'products/gorra-2.jpg'],
                'allows_customization' => true,
                'production_days' => 3,
                'is_active' => true,
                'featured' => true,
            ],
            [
                'name' => 'Chaqueta con Bordado',
                'description' => 'Chaqueta deportiva con bordado personalizado. Perfecta para equipos deportivos y eventos corporativos.',
                'base_price' => 45.00,
                'category' => 'Ropa',
                'brand' => 'Majose Pro',
                'material' => 'Poliéster y algodón',
                'available_sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'available_colors' => ['Negro', 'Azul marino', 'Gris', 'Blanco'],
                'images' => ['products/chaqueta-1.jpg', 'products/chaqueta-2.jpg'],
                'allows_customization' => true,
                'production_days' => 7,
                'is_active' => true,
                'featured' => true,
            ],
            [
                'name' => 'Bolsa Tote Personalizada',
                'description' => 'Bolsa de tela resistente con bordado personalizado. Perfecta para compras, playa o uso diario.',
                'base_price' => 15.00,
                'category' => 'Accesorios',
                'brand' => 'EcoMajose',
                'material' => 'Algodón y yute',
                'available_sizes' => ['Mediana', 'Grande'],
                'available_colors' => ['Natural', 'Negro', 'Azul', 'Verde', 'Rosa'],
                'images' => ['products/bolsa-1.jpg', 'products/bolsa-2.jpg'],
                'allows_customization' => true,
                'production_days' => 4,
                'is_active' => true,
                'featured' => false,
            ],
            [
                'name' => 'Polo Empresarial',
                'description' => 'Polo de alta calidad con bordado corporativo. Ideal para uniformes de trabajo y eventos empresariales.',
                'base_price' => 35.00,
                'category' => 'Ropa',
                'brand' => 'Majose',
                'material' => 'Piqué de algodón',
                'available_sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'available_colors' => ['Blanco', 'Azul marino', 'Negro', 'Gris', 'Rojo'],
                'images' => ['products/polo-1.jpg', 'products/polo-2.jpg'],
                'allows_customization' => true,
                'production_days' => 6,
                'is_active' => true,
                'featured' => false,
            ],
            [
                'name' => 'Mochila Bordada',
                'description' => 'Mochila resistente con bordado personalizado. Perfecta para estudiantes, viajes o uso diario.',
                'base_price' => 40.00,
                'category' => 'Accesorios',
                'brand' => 'Majose',
                'material' => 'Nylon resistente',
                'available_sizes' => ['Mediana', 'Grande'],
                'available_colors' => ['Negro', 'Azul', 'Verde', 'Gris', 'Rojo'],
                'images' => ['products/mochila-1.jpg', 'products/mochila-2.jpg'],
                'allows_customization' => true,
                'production_days' => 8,
                'is_active' => true,
                'featured' => false,
            ],
            [
                'name' => 'Delantal de Cocina',
                'description' => 'Delantal resistente con bordado personalizado. Ideal para cocinas profesionales o uso doméstico.',
                'base_price' => 20.00,
                'category' => 'Ropa',
                'brand' => 'Majose Home',
                'material' => 'Algodón resistente',
                'available_sizes' => ['Único'],
                'available_colors' => ['Blanco', 'Negro', 'Azul', 'Rojo', 'Verde'],
                'images' => ['products/delantal-1.jpg', 'products/delantal-2.jpg'],
                'allows_customization' => true,
                'production_days' => 4,
                'is_active' => true,
                'featured' => false,
            ],
            [
                'name' => 'Toalla Personalizada',
                'description' => 'Toalla de baño con bordado personalizado. Perfecta para spas, hoteles o regalos únicos.',
                'base_price' => 30.00,
                'category' => 'Hogar',
                'brand' => 'Majose Home',
                'material' => 'Algodón egipcio',
                'available_sizes' => ['Mediana', 'Grande', 'Extra Grande'],
                'available_colors' => ['Blanco', 'Beige', 'Azul claro', 'Rosa claro'],
                'images' => ['products/toalla-1.jpg', 'products/toalla-2.jpg'],
                'allows_customization' => true,
                'production_days' => 6,
                'is_active' => true,
                'featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }
    }
}