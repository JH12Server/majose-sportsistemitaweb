<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResumenCaja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Faker\Factory as Faker;

class ResumenCajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, rand(5, 10)) as $i) {
            ResumenCaja::create([
                'fecha' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                'monto_inicial' => $faker->randomFloat(2, 100, 500),
                'monto_final' => $faker->randomFloat(2, 500, 2000),
                'total_ventas' => $faker->randomFloat(2, 100, 2000),
                'total_gastos' => $faker->randomFloat(2, 10, 500),
                'observaciones' => $faker->sentence(6),
            ]);
        }
    }
}
