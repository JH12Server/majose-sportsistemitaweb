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
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'status_updated_by')) {
                $table->foreignId('status_updated_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            }
            if (!Schema::hasColumn('orders', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable()->after('status_updated_by');
            }
            if (!Schema::hasColumn('orders', 'status_notes')) {
                $table->text('status_notes')->nullable()->after('status_updated_at');
            }
            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('actual_delivery');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'status_updated_by')) {
                $table->dropForeign(['status_updated_by']);
                $table->dropColumn('status_updated_by');
            }
            if (Schema::hasColumn('orders', 'status_updated_at')) {
                $table->dropColumn('status_updated_at');
            }
            if (Schema::hasColumn('orders', 'status_notes')) {
                $table->dropColumn('status_notes');
            }
            if (Schema::hasColumn('orders', 'delivered_at')) {
                $table->dropColumn('delivered_at');
            }
        });
    }
};
