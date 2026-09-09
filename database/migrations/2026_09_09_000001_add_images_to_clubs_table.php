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
        if (Schema::hasTable('clubs') && !Schema::hasColumn('clubs', 'images')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->text('images')->nullable()->after('logo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('clubs') && Schema::hasColumn('clubs', 'images')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->dropColumn('images');
            });
        }
    }
};
