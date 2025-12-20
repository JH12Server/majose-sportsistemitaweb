<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            
            // Sección Hero
            $table->string('hero_title')->default('Bienvenido a MajoseSport');
            $table->string('hero_subtitle')->default('Los mejores artículos deportivos para tu entrenamiento');
            $table->string('hero_button_text')->default('Explorar Tienda');
            $table->string('hero_image_url')->nullable();
            
            // Sección Productos
            $table->text('products_description')->nullable();
            
            // Sección Nosotros
            $table->text('about_description')->nullable();
            $table->string('about_image_url')->nullable();
            
            // Secciones de características (Feature 1, 2, 3)
            $table->string('feature_1_title')->default('✅ Calidad Premium');
            $table->string('feature_1_desc')->default('Productos de las mejores marcas del mundo');
            $table->string('feature_2_title')->default('🚚 Envío Rápido');
            $table->string('feature_2_desc')->default('Entrega en 24-48 horas en todo el país');
            $table->string('feature_3_title')->default('💯 Garantía');
            $table->string('feature_3_desc')->default('100% garantía de satisfacción del cliente');
            
            // Sección Contacto
            $table->text('contact_description')->nullable();
            
            // Redes sociales y datos de contacto
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
