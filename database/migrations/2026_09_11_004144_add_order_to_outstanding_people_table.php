<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('outstanding_people') && !Schema::hasColumn('outstanding_people', 'order')) {
            Schema::table('outstanding_people', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('position');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('outstanding_people') && Schema::hasColumn('outstanding_people', 'order')) {
            Schema::table('outstanding_people', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};
