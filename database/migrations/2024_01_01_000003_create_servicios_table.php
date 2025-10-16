<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->string('categoria')->nullable();
            $table->boolean('estado')->default(true); // true = activo, false = inactivo
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicios');
    }
}; 