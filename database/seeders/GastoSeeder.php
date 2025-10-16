<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gasto;
use Faker\Factory as Faker;

class GastoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, rand(5, 10)) as $i) {
            Gasto::create([
                'user_id' => $faker->numberBetween(1, 2),
                'descripcion' => $faker->sentence(3),
                'monto' => $faker->randomFloat(2, 10, 200),
                'fecha' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                'metodo_pago' => $faker->randomElement(['Efectivo', 'Yape/Plin', 'BCP', 'BBVA', 'INTERBANK', 'OTROS']),
            ]);
        }
    }
}
