<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('roles', 'type')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('type')->nullable()->after('description')->comment('system|custom');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('roles', 'type')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
