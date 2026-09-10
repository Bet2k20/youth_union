<?php

use App\Http\Controllers\Api\AboutApiController;
use App\Http\Controllers\Api\ActivityApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BannerApiController;
use App\Http\Controllers\Api\ClubApiController;
use App\Http\Controllers\Api\HomeSummaryApiController;
use App\Http\Controllers\Api\MediaApiController;
use App\Http\Controllers\Api\MovementApiController;
use App\Http\Controllers\Api\OutstandingPersonApiController;
use App\Http\Controllers\Api\Student5GoodApiController;
use App\Http\Controllers\Api\UploadApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes cho Đoàn Thanh Niên Học Viện CSND (Tuổi trẻ PPA)
| Chuẩn hóa 100% theo bản thiết kế Figma "Sổ tay sinh viên - T02"
|--------------------------------------------------------------------------
| Tất cả các route ở đây đều tự động có tiền tố /api/
*/

// ==========================================
// 1. PUBLIC APIS (Khớp 100% Các Màn Hình Figma & Dashboard)
// ==========================================

// Kích hoạt & Khởi tạo dữ liệu Database 1-Click
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

// Upload File Ảnh từ Máy Tính (Hỗ trợ JPG, PNG, WEBP, tối đa 5MB)
Route::post('/upload', [UploadApiController::class, 'upload']);

// 1. MÀN HÌNH TRANG CHỦ (Home Page)
Route::get('/home', [HomeSummaryApiController::class, 'index']);

// 2. MÀN HÌNH GIỚI THIỆU & ĐOÀN THANH NIÊN HỌC VIỆN (About & Organization)
Route::get('/about', [AboutApiController::class, 'index']);

// 3. MÀN HÌNH PHONG TRÀO ĐOÀN (Movements)
Route::get('/movements', [MovementApiController::class, 'index']);

// 4. MÀN HÌNH HÀNH TRÌNH PHẤN ĐẤU / SINH VIÊN 5 TỐT (Student 5 Good)
Route::get('/student-5-good', [Student5GoodApiController::class, 'index']);

// 5. MÀN HÌNH CÂU LẠC BỘ – ĐỘI – NHÓM (Clubs)
Route::get('/club-categories', [ClubApiController::class, 'categories']);
Route::apiResource('clubs', ClubApiController::class);

// 6. MÀN HÌNH HOẠT ĐỘNG & TIN TỨC (Activities)
Route::apiResource('activities', ActivityApiController::class);

// 7. MÀN HÌNH GƯƠNG MẶT SINH VIÊN TIÊU BIỂU (Outstanding People)
Route::apiResource('outstanding-people', OutstandingPersonApiController::class);

// 8. MÀN HÌNH THƯ VIỆN (Thư viện ảnh & Thông tư, quy định, biểu mẫu)
Route::get('/media', [MediaApiController::class, 'index']);
Route::get('/media/photos', [MediaApiController::class, 'photos']);
Route::get('/media/documents', [MediaApiController::class, 'documents']);
Route::get('/photos', [MediaApiController::class, 'photos']);
Route::get('/documents', [MediaApiController::class, 'documents']);


// 9. BANNER SLIDER
Route::apiResource('banners', BannerApiController::class);

// 10. QUẢN LÝ TÀI KHOẢN & PHÂN QUYỀN (Users & Roles trên Dashboard)
Route::apiResource('users', UserApiController::class);

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
});
