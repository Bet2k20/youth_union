<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Club;
use Illuminate\Http\JsonResponse;

class MediaApiController extends Controller
{
    /**
     * API dữ liệu cho màn hình Thư viện ảnh & Tài liệu biểu mẫu (Figma)
     * GET /api/media
     */
    public function index(): JsonResponse
    {
        // 1. Thư viện hình ảnh hoạt động tổng hợp
        $gallery = Activity::where('is_active', true)
            ->whereNotNull('thumbnail')
            ->latest('id')
            ->take(12)
            ->get(['id', 'title', 'thumbnail', 'created_at']);

        // 2. Biểu mẫu & Văn bản chỉ đạo của Đoàn trường
        $documents = [
            [
                'id' => 1,
                'title' => 'Mẫu đăng ký rèn luyện tiêu chuẩn "Sinh viên 5 Tốt" năm học mới',
                'category' => 'Biểu mẫu rèn luyện',
                'file_type' => 'DOCX',
                'file_size' => '1.2 MB',
                'download_url' => '/uploads/documents/mau_dang_ky_sinh_vien_5_tot.docx',
            ],
            [
                'id' => 2,
                'title' => 'Kế hoạch tổ chức Chiến dịch Tình nguyện Mùa Hè Xanh 2026',
                'category' => 'Kế hoạch phong trào',
                'file_type' => 'PDF',
                'file_size' => '2.5 MB',
                'download_url' => '/uploads/documents/ke_hoach_mua_he_xanh_2026.pdf',
            ],
            [
                'id' => 3,
                'title' => 'Đơn xin thành lập / gia nhập Câu Lạc Bộ trực thuộc Đoàn trường',
                'category' => 'CLB - Đội - Nhóm',
                'file_type' => 'DOCX',
                'file_size' => '850 KB',
                'download_url' => '/uploads/documents/don_gia_nhap_clb.docx',
            ],
            [
                'id' => 4,
                'title' => 'Hướng dẫn sinh hoạt Chi đoàn định kỳ và đánh giá xếp loại đoàn viên',
                'category' => 'Công tác Đoàn',
                'file_type' => 'PDF',
                'file_size' => '3.1 MB',
                'download_url' => '/uploads/documents/huong_dan_sinh_hoat_chi_doan.pdf',
            ],
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu trang Thư viện thành công',
            'data' => [
                'gallery' => $gallery,
                'documents' => $documents,
            ],
        ], 200);
    }
}
