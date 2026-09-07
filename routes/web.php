<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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
