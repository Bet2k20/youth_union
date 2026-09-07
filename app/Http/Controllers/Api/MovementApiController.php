<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovementApiController extends Controller
{
    /**
     * API dữ liệu cho màn hình Phong trào Đoàn (Figma)
     * GET /api/movements
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Danh sách các chiến dịch phong trào trọng tâm
        $movementCategories = [
            [
                'id' => 'tinh-nguyen',
                'title' => 'Tình nguyện & Đền ơn đáp nghĩa',
                'description' => 'Mùa hè xanh, Tiếp sức mùa thi, Hiến máu tình nguyện "Giọt hồng tri ân", Thắp nến tri ân tại nghĩa trang liệt sĩ.',
                'color' => '#10b981',
            ],
            [
                'id' => 'sang-tao',
                'title' => 'Tuổi trẻ sáng tạo & Nghiên cứu khoa học',
                'description' => 'Diễn đàn nghiên cứu khoa học, câu lạc bộ học thuật, ứng dụng công nghệ thông tin và chuyển đổi số trong học tập.',
                'color' => '#3b82f6',
            ],
            [
                'id' => 'ren-luyen',
                'title' => 'Rèn luyện thể lực & Điều lệnh CAND',
                'description' => 'Hội thao thanh niên, giải võ thuật ứng dụng, rèn cán luyện quân và chấp hành nghiêm kỷ cương điều lệnh.',
                'color' => '#f59e0b',
            ],
            [
                'id' => 'sinh-vien-5-tot',
                'title' => 'Phong trào Sinh viên 5 Tốt',
                'description' => 'Cuộc vận động lớn rèn luyện toàn diện 5 tiêu chí: Đạo đức, Học tập, Thể lực, Tình nguyện, Hội nhập.',
                'color' => '#8b5cf6',
            ],
        ];

        // 2. Lấy danh sách các bài viết / sự kiện phong trào
        $query = Activity::where('is_active', true)->latest('id');

        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where('title', 'like', "%{$keyword}%");
        }

        $activities = $query->paginate((int) $request->input('per_page', 9));

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu trang Phong trào thành công',
            'data' => [
                'categories' => $movementCategories,
                'activities' => $activities,
            ],
        ], 200);
    }
}
