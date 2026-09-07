<?php

use App\Http\Controllers\Api\ActivityApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BannerApiController;
use App\Http\Controllers\Api\ClubApiController;
use App\Http\Controllers\Api\HomeSummaryApiController;
use App\Http\Controllers\Api\OutstandingPersonApiController;
use App\Http\Controllers\Api\UploadApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes cho Đoàn Thanh Niên (Youth Union)
|--------------------------------------------------------------------------
| Tất cả các route ở đây đều tự động có tiền tố /api/
| Ví dụ: http://127.0.0.1:8888/api/upload
*/

// ==========================================
// 1. PUBLIC APIS (Dành cho Trang Chủ & Khách xem)
// ==========================================

// Upload File Ảnh từ Máy Tính (Hỗ trợ JPG, PNG, WEBP, tối đa 5MB)
Route::post('/upload', [UploadApiController::class, 'upload']);

// Trang Chủ (Tổng hợp banner, tin tức, CLB nổi bật, số liệu thống kê)
Route::get('/home', [HomeSummaryApiController::class, 'index']);

// Banner Slider
Route::apiResource('banners', BannerApiController::class);

// Hoạt Động & Tin Tức
Route::apiResource('activities', ActivityApiController::class);

// Câu Lạc Bộ & Thể Loại
Route::get('/club-categories', [ClubApiController::class, 'categories']);
Route::apiResource('clubs', ClubApiController::class);

// Gương Mặt Tiêu Biểu
Route::apiResource('outstanding-people', OutstandingPersonApiController::class);

// ==========================================
// 2. AUTHENTICATION APIS (Xác thực tài khoản)
// ==========================================

// Đăng nhập công khai
Route::post('/auth/login', [AuthApiController::class, 'login']);

// ==========================================
// 3. PROTECTED APIS (Cần Đăng Nhập Token Sanctum)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    // Thông tin tài khoản hiện tại & Đăng xuất
    Route::get('/auth/me', [AuthApiController::class, 'me']);
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::post('/auth/change-password', [AuthApiController::class, 'changePassword']);

    // Quản lý người dùng / Cán bộ Đoàn (Dành cho Admin)
    Route::apiResource('users', UserApiController::class);
});
