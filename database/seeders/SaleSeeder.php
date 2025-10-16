<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['user_id' => 1, 'total' => 120.50, 'status' => 'completado', 'created_at' => '2024-07-01 10:00:00'],
            ['user_id' => 2, 'total' => 75.00, 'status' => 'pendiente', 'created_at' => '2024-07-02 11:00:00'],
            ['user_id' => 1, 'total' => 200.00, 'status' => 'completado', 'created_at' => '2024-07-03 12:00:00'],
            ['user_id' => 2, 'total' => 50.00, 'status' => 'cancelado', 'created_at' => '2024-07-04 13:00:00'],
            ['user_id' => 1, 'total' => 99.99, 'status' => 'completado', 'created_at' => '2024-07-05 14:00:00'],
            ['user_id' => 2, 'total' => 150.00, 'status' => 'pendiente', 'created_at' => '2024-07-06 15:00:00'],
        ];
        foreach ($data as $item) {
            Sale::create($item);
        }
    }
}
