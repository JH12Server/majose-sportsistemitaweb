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
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            // Para PostgreSQL
            DB::statement("
                ALTER TABLE orders
                ADD CONSTRAINT orders_status_check_new CHECK(status IN ('pending', 'review', 'production', 'ready', 'shipped', 'delivered', 'cancelled', 'paid'))
            ");
            
            // Eliminar el constraint anterior si existe
            DB::statement("
                ALTER TABLE orders
                DROP CONSTRAINT IF EXISTS orders_status_check
            ");
        } elseif ($driver === 'mysql') {
            // Para MySQL
            Schema::table('orders', function (Blueprint $table) {
                DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'review', 'production', 'ready', 'shipped', 'delivered', 'cancelled', 'paid') DEFAULT 'pending'");
            });
        } elseif ($driver === 'sqlite') {
            // Para SQLite, recrear tabla con nuevo check
            DB::statement("
                CREATE TABLE orders_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id BIGINT UNSIGNED NOT NULL,
                    order_number VARCHAR(255) UNIQUE NOT NULL,
                    status VARCHAR(255) DEFAULT 'pending' NOT NULL CHECK(status IN ('pending', 'review', 'production', 'ready', 'shipped', 'delivered', 'cancelled', 'paid')),
                    total_amount DECIMAL(10,2),
                    customer_notes TEXT,
                    internal_notes TEXT,
                    estimated_delivery DATE,
                    actual_delivery DATE,
                    delivered_at DATETIME,
                    assigned_worker_id BIGINT UNSIGNED,
                    status_updated_by BIGINT UNSIGNED,
                    status_updated_at DATETIME,
                    status_notes TEXT,
                    priority VARCHAR(255) DEFAULT 'normal' NOT NULL CHECK(priority IN ('low', 'normal', 'high', 'urgent')),
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY(assigned_worker_id) REFERENCES users(id) ON DELETE SET NULL,
                    FOREIGN KEY(status_updated_by) REFERENCES users(id) ON DELETE SET NULL
                )
            ");
            
            DB::statement("INSERT INTO orders_new SELECT * FROM orders");
            DB::statement("DROP TABLE orders");
            DB::statement("ALTER TABLE orders_new RENAME TO orders");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            // Revertir para PostgreSQL
            DB::statement("
                ALTER TABLE orders
                DROP CONSTRAINT IF EXISTS orders_status_check_new
            ");
            
            DB::statement("
                ALTER TABLE orders
                ADD CONSTRAINT orders_status_check CHECK(status IN ('pending', 'review', 'production', 'ready', 'shipped', 'delivered', 'cancelled'))
            ");
        }
    }
};
