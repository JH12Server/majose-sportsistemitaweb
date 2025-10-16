<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SaleDetail;

class SaleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['sale_id' => 1, 'product_id' => 1, 'quantity' => 2, 'price' => 30.00],
            ['sale_id' => 1, 'product_id' => 2, 'quantity' => 1, 'price' => 15.00],
            ['sale_id' => 2, 'product_id' => 3, 'quantity' => 3, 'price' => 20.00],
            ['sale_id' => 3, 'product_id' => 4, 'quantity' => 1, 'price' => 25.00],
            ['sale_id' => 4, 'product_id' => 5, 'quantity' => 2, 'price' => 10.00],
            ['sale_id' => 5, 'product_id' => 6, 'quantity' => 1, 'price' => 18.00],
        ];
        foreach ($data as $item) {
            SaleDetail::create($item);
        }
    }
}
