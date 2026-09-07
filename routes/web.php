<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route 1-Click tự động khởi tạo toàn bộ Bảng và Dữ liệu vào Database online
Route::get('/setup-database', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $migrateLog = Artisan::output();

        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\SampleDataSeeder',
            '--force' => true
        ]);
        $seedLog = Artisan::output();

        return response()->json([
            'status' => 'success',
            'message' => '🎉 Khởi tạo Database và nạp dữ liệu thành công 100%!',
            'migrate_log' => $migrateLog,
            'seed_log' => $seedLog,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi: ' . $e->getMessage(),
        ], 500);
    }
});

// Route kiểm tra sức khỏe hệ thống & kết nối Database
Route::get('/ping', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'Connected to Database successfully (' . DB::connection()->getDatabaseName() . ')';
    } catch (\Exception $e) {
        $dbStatus = 'Database connection error: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'OK',
        'app_env' => config('app.env'),
        'database' => $dbStatus,
        'php_version' => PHP_VERSION,
        'time' => now()->toDateTimeString(),
    ]);
});

// Trang chủ: Hiển thị Câu lạc bộ
Route::get('/', [ClubController::class, 'index'])->name('clubs.index');

// Trang hoạt động: Hiển thị các Hoạt động / Tin tức của Đoàn trường
Route::get('/hoat-dong', [ActivityController::class, 'index'])->name('activities.index');

// Trang kiểm thử API (Tester)
Route::get('/test-api', function () {
    return view('test_api');
})->name('test.api');

// BẢNG ĐIỀU KHIỂN DASHBOARD ADMIN
Route::get('/dashboard', function () {
    return view('admin.activities');
})->name('admin.dashboard');
