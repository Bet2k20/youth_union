<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Banner;
use App\Models\Club;
use App\Models\ClubCategory;
use App\Models\OutstandingPerson;
use Illuminate\Http\JsonResponse;

class HomeSummaryApiController extends Controller
{
    /**
     * API tổng hợp dữ liệu cho Trang Chủ của nhóm Frontend
     */
    public function index(): JsonResponse
    {
        // 1. Lấy danh sách Banner đang hiển thị
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        // 2. Lấy 4 hoạt động / tin tức mới nhất
        $latestActivities = Activity::query()
            ->where('is_active', true)
            ->latest('id')
            ->take(4)
            ->get();

        // 3. Lấy 4 gương mặt tiêu biểu nổi bật
        $featuredPeople = OutstandingPerson::query()
            ->where('is_active', true)
            ->latest('id')
            ->take(4)
            ->get();

        // 4. Lấy 6 câu lạc bộ tiêu biểu kèm thể loại
        $featuredClubs = Club::query()
            ->with('category')
            ->latest('id')
            ->take(6)
            ->get();

        // 5. Số liệu thống kê tổng quan
        $statistics = [
            'total_banners' => Banner::count(),
            'total_clubs' => Club::count(),
            'total_categories' => ClubCategory::count(),
            'total_activities' => Activity::where('is_active', true)->count(),
            'total_outstanding_people' => OutstandingPerson::where('is_active', true)->count(),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu trang chủ thành công',
            'data' => [
                'statistics' => $statistics,
                'banners' => $banners,
                'latest_activities' => $latestActivities,
                'featured_clubs' => $featuredClubs,
                'featured_people' => $featuredPeople,
            ],
        ], 200);
    }
}
