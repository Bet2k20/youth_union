<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class MetricsApiController extends Controller
{
    /**
     * API lấy số liệu thống kê (Metrics) của Đoàn Thanh niên Học viện CSND
     * GET /api/metrics hoặc GET /api/thong-ke hoặc GET /api/statistics
     */
    public function index(): JsonResponse
    {
        $metrics = self::getMetricsData();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy số liệu thống kê (metrics) thành công',
            'total' => count($metrics),
            'data' => $metrics,
            'metrics' => $metrics,
            'summary' => [
                'doan_vien' => 4114,
                'co_so_doan' => 24,
                'clb_doi_nhom' => 9,
                'chuong_trinh_moi_nam' => '100+',
            ],
        ], 200);
    }

    /**
     * Dữ liệu các chỉ số thống kê chuẩn thiết kế
     */
    public static function getMetricsData(): array
    {
        return [
            [
                'id' => 1,
                'key' => 'doan_vien',
                'label' => 'Đoàn viên',
                'value' => '4114',
                'number' => 4114,
                'formatted_value' => '4.114',
                'unit' => 'đoàn viên',
                'description' => 'Tổng số đoàn viên toàn Học viện',
            ],
            [
                'id' => 2,
                'key' => 'co_so_doan',
                'label' => 'Cơ sở Đoàn trực thuộc',
                'value' => '24',
                'number' => 24,
                'formatted_value' => '24',
                'unit' => 'cơ sở Đoàn',
                'description' => 'Các liên chi đoàn và chi đoàn trực thuộc Đoàn trường',
            ],
            [
                'id' => 3,
                'key' => 'clb_doi_nhom',
                'label' => 'CLB · Đội · Nhóm',
                'value' => '9',
                'number' => 9,
                'formatted_value' => '9',
                'unit' => 'CLB',
                'description' => 'Các câu lạc bộ, đội, nhóm sở thích và học thuật',
            ],
            [
                'id' => 4,
                'key' => 'chuong_trinh_moi_nam',
                'label' => 'Chương trình mỗi năm',
                'value' => '100+',
                'number' => 100,
                'formatted_value' => '100+',
                'unit' => 'chương trình',
                'description' => 'Hoạt động, phong trào, chiến dịch thanh niên tình nguyện mỗi năm',
            ],
        ];
    }
}
