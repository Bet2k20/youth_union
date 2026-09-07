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
        if (Schema::hasTable('clubs') && !Schema::hasColumn('clubs', 'logo')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->string('logo', 500)->nullable()->after('name');
            });
        }

        if (Schema::hasTable('outstanding_people') && !Schema::hasColumn('outstanding_people', 'avatar')) {
            Schema::table('outstanding_people', function (Blueprint $table) {
                $table->string('avatar', 500)->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('clubs') && Schema::hasColumn('clubs', 'logo')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->dropColumn('logo');
            });
        }

        if (Schema::hasTable('outstanding_people') && Schema::hasColumn('outstanding_people', 'avatar')) {
            Schema::table('outstanding_people', function (Blueprint $table) {
                $table->dropColumn('avatar');
            });
        }
    }
};
