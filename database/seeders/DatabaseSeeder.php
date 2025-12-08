<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario admin de prueba
        User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Admin Demo',
                'password' => bcrypt('admin1234'),
                'role' => 'admin',
            ]
        );

        // Usuario normal de prueba
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('12345678'),
                'role' => 'user',
            ]
        );

        // Llamar a los seeders de las tablas principales
        $this->call([
            CategoriaSeeder::class,
            MarcaSeeder::class,
            PresentacionSeeder::class,
            ServicioSeeder::class,
            SaleSeeder::class,
            SaleDetailSeeder::class,
            EntregaSeeder::class,
            GastoSeeder::class,
            ResumenCajaSeeder::class,
            CarritoSeeder::class,
        ]);
    }
}
