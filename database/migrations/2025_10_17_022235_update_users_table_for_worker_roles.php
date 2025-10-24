<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('permissions')->nullable();
        });
        
        // Actualizar el campo role por separado para evitar problemas con PostgreSQL
        DB::statement("ALTER TABLE users ALTER COLUMN role TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'customer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'is_active', 'permissions']);
        });
        
        DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'user'");
    }
};
