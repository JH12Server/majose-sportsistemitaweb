<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cliente de prueba
        User::create([
            'name' => 'Juan Cliente',
            'email' => 'cliente@test.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1234567890',
            'address' => 'Calle Principal 123, Ciudad',
            'is_active' => true,
        ]);

        // Administrador
        User::create([
            'name' => 'Admin Sistema',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567891',
            'is_active' => true,
        ]);

        // Administrador Demo
        User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567896',
            'is_active' => true,
        ]);

        // Diseñador
        User::create([
            'name' => 'María Diseñadora',
            'email' => 'disenador@test.com',
            'password' => Hash::make('password'),
            'role' => 'designer',
            'phone' => '+1234567892',
            'is_active' => true,
        ]);

        // Bordador
        User::create([
            'name' => 'Carlos Bordador',
            'email' => 'bordador@test.com',
            'password' => Hash::make('password'),
            'role' => 'embroiderer',
            'phone' => '+1234567893',
            'is_active' => true,
        ]);

        // Encargado de entrega
        User::create([
            'name' => 'Ana Entregas',
            'email' => 'entregas@test.com',
            'password' => Hash::make('password'),
            'role' => 'delivery_manager',
            'phone' => '+1234567894',
            'is_active' => true,
        ]);

        // Supervisor
        User::create([
            'name' => 'Luis Supervisor',
            'email' => 'supervisor@test.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'phone' => '+1234567895',
            'is_active' => true,
        ]);
    }
}