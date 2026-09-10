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
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'movement_type')) {
                $table->string('movement_type', 50)->nullable()->after('thumbnail'); // den_on_dap_nghia | thanh_nien_xung_kich
            }
            if (!Schema::hasColumn('activities', 'activity_type')) {
                $table->string('activity_type', 100)->nullable()->after('movement_type'); // Thắp nến tri ân, Tặng quà người có công, Chiến dịch tiêu biểu...
            }
            if (!Schema::hasColumn('activities', 'location')) {
                $table->string('location', 255)->nullable()->after('activity_type'); // Địa điểm / Địa bàn
            }
            if (!Schema::hasColumn('activities', 'target_audience')) {
                $table->string('target_audience', 255)->nullable()->after('location'); // Đối tượng
            }
            if (!Schema::hasColumn('activities', 'participants')) {
                $table->string('participants', 255)->nullable()->after('target_audience'); // Tham gia (vd: 300+ đoàn viên)
            }
            if (!Schema::hasColumn('activities', 'summary_content')) {
                $table->text('summary_content')->nullable()->after('participants'); // Nội dung
            }
            if (!Schema::hasColumn('activities', 'significance')) {
                $table->text('significance')->nullable()->after('summary_content'); // Ý nghĩa
            }
            if (!Schema::hasColumn('activities', 'result')) {
                $table->text('result')->nullable()->after('significance'); // Kết quả
            }
            if (!Schema::hasColumn('activities', 'objective')) {
                $table->text('objective')->nullable()->after('result'); // Mục tiêu
            }
            if (!Schema::hasColumn('activities', 'cooperation')) {
                $table->string('cooperation', 255)->nullable()->after('objective'); // Phối hợp
            }
            if (!Schema::hasColumn('activities', 'value')) {
                $table->text('value')->nullable()->after('cooperation'); // Giá trị
            }
            if (!Schema::hasColumn('activities', 'comment')) {
                $table->json('comment')->nullable()->after('value'); // Bình luận: { content, author, class_unit }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $columns = [
                'movement_type',
                'activity_type',
                'location',
                'target_audience',
                'participants',
                'summary_content',
                'significance',
                'result',
                'objective',
                'cooperation',
                'value',
                'comment',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('activities', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
