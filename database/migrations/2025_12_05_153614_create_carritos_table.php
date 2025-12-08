<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);

            // Campos de personalización
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('text')->nullable();
            $table->string('font')->nullable();
            $table->string('text_color')->nullable();
            $table->text('additional_specifications')->nullable();

            // Ruta del archivo subido (solo el path relativo)
            $table->string('reference_file')->nullable();

            $table->timestamps();

            // Índice único para evitar duplicados del mismo producto sin personalización
            $table->unique(['user_id', 'product_id', 'size', 'color', 'reference_file']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carritos');
    }
};