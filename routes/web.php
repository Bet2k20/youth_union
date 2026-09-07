<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ: Hiển thị Câu lạc bộ
Route::get('/', [ClubController::class, 'index'])->name('clubs.index');

// Trang hoạt động: Hiển thị các Hoạt động / Tin tức của Đoàn trường
Route::get('/hoat-dong', [ActivityController::class, 'index'])->name('activities.index');

// Trang kiểm thử API (Tester)
Route::get('/test-api', function () {
    return view('test_api');
})->name('test.api');

// BẢNG ĐIỀU KHIỂN DASHBOARD ADMIN (Làm từng chức năng)
Route::get('/dashboard', function () {
    return view('admin.activities');
})->name('admin.dashboard');
