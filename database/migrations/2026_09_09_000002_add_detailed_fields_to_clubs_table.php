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
        Schema::table('clubs', function (Blueprint $table) {
            if (!Schema::hasColumn('clubs', 'missions')) {
                $table->json('missions')->nullable()->after('description');
            }
            if (!Schema::hasColumn('clubs', 'management_structure')) {
                $table->json('management_structure')->nullable()->after('missions');
            }
            if (!Schema::hasColumn('clubs', 'regular_activities')) {
                $table->json('regular_activities')->nullable()->after('management_structure');
            }
            if (!Schema::hasColumn('clubs', 'achievements')) {
                $table->json('achievements')->nullable()->after('regular_activities');
            }
            if (!Schema::hasColumn('clubs', 'membership_requirements')) {
                $table->json('membership_requirements')->nullable()->after('achievements');
            }
            if (!Schema::hasColumn('clubs', 'recruitment_process')) {
                $table->json('recruitment_process')->nullable()->after('membership_requirements');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $columns = [
                'missions',
                'management_structure',
                'regular_activities',
                'achievements',
                'membership_requirements',
                'recruitment_process',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('clubs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
