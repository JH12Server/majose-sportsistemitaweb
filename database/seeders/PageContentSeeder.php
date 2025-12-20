<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PageContent::firstOrCreate([], [
            'hero_title' => 'Bienvenido a MajoseSport',
            'hero_subtitle' => 'Los mejores artículos deportivos para tu entrenamiento',
            'hero_button_text' => 'Explorar Tienda',
            'products_description' => 'Encuentra una amplia variedad de productos deportivos de calidad.',
            'about_description' => 'En MajoseSport, nos dedicamos a proporcionar los mejores artículos deportivos para atletas de todos los niveles. Con más de 10 años de experiencia, somos líderes en la industria del deporte.',
            'feature_1_title' => '✅ Calidad Premium',
            'feature_1_desc' => 'Productos de las mejores marcas del mundo',
            'feature_2_title' => '🚚 Envío Rápido',
            'feature_2_desc' => 'Entrega en 24-48 horas en todo el país',
            'feature_3_title' => '💯 Garantía',
            'feature_3_desc' => '100% garantía de satisfacción del cliente',
            'contact_description' => '¿Preguntas o comentarios? Estamos aquí para ayudarte',
            'phone' => '+51 999 999 999',
            'email' => 'contacto@majosesport.com',
            'address' => 'Calle Principal 123, Lima, Perú',
        ]);
    }
}
