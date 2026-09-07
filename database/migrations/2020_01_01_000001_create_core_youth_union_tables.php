<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khởi tạo các bảng nòng cốt cho Đoàn Thanh Niên
     */
    public function up(): void
    {
        // 1. Thể loại CLB
        if (!Schema::hasTable('club_categories')) {
            Schema::create('club_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 2. Câu Lạc Bộ
        if (!Schema::hasTable('clubs')) {
            Schema::create('clubs', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('logo', 500)->nullable();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->date('founded_date')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Hoạt Động & Tin Tức
        if (!Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('thumbnail', 500)->nullable();
                $table->longText('content')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Gương Mặt Tiêu Biểu
        if (!Schema::hasTable('outstanding_people')) {
            Schema::create('outstanding_people', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('avatar', 500)->nullable();
                $table->string('role_group')->nullable(); // BGD, BI_THU_DOAN, DOAN_VIEN
                $table->string('class_unit')->nullable();
                $table->text('achievement')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outstanding_people');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('clubs');
        Schema::dropIfExists('club_categories');
    }
};
