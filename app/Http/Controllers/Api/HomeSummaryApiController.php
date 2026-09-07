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
     * API tổng hợp dữ liệu cho Trang Chủ (Chuẩn theo giao diện thiết kế Học Viện CSND)
     */
    public function index(): JsonResponse
    {
        // 1. Lấy danh sách Banner
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        // 1 banner chính (Hero Banner) cho phần đầu trang
        $heroBanner = $banners->first() ?? [
            'id' => 1,
            'tagline' => 'Nền tảng số 4.0 - Đoàn TNCS Hồ Chí Minh Học viện CSND',
            'title' => 'Tuổi trẻ Học viện: Bản lĩnh • Kỷ cương • Trách nhiệm • Sáng tạo',
            'description' => 'Không gian cung cấp thông tin cần thiết về Học viện, tổ chức Đoàn, các câu lạc bộ, phong trào thanh niên và hành trình rèn luyện, phấn đấu của sinh viên Học viện Cảnh sát nhân dân.',
            'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200&q=80',
            'primary_button' => ['label' => 'Hành trình thanh niên', 'url' => '/hoat-dong'],
            'secondary_button' => ['label' => 'Về Đoàn Thanh niên', 'url' => '/gioi-thieu'],
        ];

        // 2. Lấy danh sách hoạt động / tin tức (trả về 6 bài để Frontend lấy 3 bài đầu hoặc tuỳ chọn)
        $latestActivities = Activity::query()
            ->where('is_active', true)
            ->latest('id')
            ->take(6)
            ->get();

        // Danh sách 3 ảnh hoạt động tiêu biểu cho khung "Tình nguyện & Đền ơn đáp nghĩa"
        $activityImages = $latestActivities->pluck('thumbnail')->filter()->values()->take(3);
        if ($activityImages->isEmpty()) {
            $activityImages = collect([
                'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=600&q=80',
                'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=600&q=80',
                'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&q=80',
            ]);
        }

        // 3. Khung phong trào nổi bật (Chuẩn theo thiết kế Figma)
        $movementHighlight = [
            'badge' => 'VÌ CỘNG ĐỒNG',
            'title' => 'Tình nguyện & Đền ơn đáp nghĩa',
            'description' => 'Hiến máu tình nguyện, tiếp sức mùa thi, thắp nến tri ân, mùa hè xanh về vùng sâu vùng xa – mỗi hành trình là một bài học về trách nhiệm và tình yêu thương.',
            'tags' => ['Hiến máu', 'Tiếp sức mùa thi', 'Thắp nến tri ân', 'Mùa hè xanh', 'Về nguồn'],
            'button' => ['label' => 'Xem các phong trào', 'url' => '/hoat-dong'],
            'images' => $activityImages,
        ];

        // 4. Số liệu thống kê (Counter Metrics)
        $metrics = [
            [
                'key' => 'tradition_years',
                'value' => '68',
                'label' => 'Năm truyền thống',
            ],
            [
                'key' => 'total_clubs',
                'value' => '8+',
                'label' => 'Câu lạc bộ - Đội - Nhóm',
            ],
            [
                'key' => 'total_members',
                'value' => '9.6K',
                'label' => 'Đoàn viên toàn Học viện',
            ],
            [
                'key' => 'title_badge',
                'value' => 'Sinh viên 5 Tốt',
                'label' => 'Danh hiệu sinh viên tiêu biểu',
            ],
        ];

        // 5. 5 Tiêu chí Sinh viên 5 Tốt
        $student5Criteria = [
            ['step' => 1, 'title' => 'Đạo đức tốt'],
            ['step' => 2, 'title' => 'Học tập tốt'],
            ['step' => 3, 'title' => 'Thể lực tốt'],
            ['step' => 4, 'title' => 'Tình nguyện tốt'],
            ['step' => 5, 'title' => 'Hội nhập tốt'],
        ];

        // 6. Câu lạc bộ & Gương mặt tiêu biểu
        $featuredClubs = Club::query()
            ->with('category')
            ->latest('id')
            ->take(6)
            ->get();

        $featuredPeople = OutstandingPerson::query()
            ->where('is_active', true)
            ->latest('id')
            ->take(4)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu trang chủ thành công',
            'data' => [
                'hero_banner' => $heroBanner,
                'banners' => $banners,
                'metrics' => $metrics,
                'student_5_criteria' => $student5Criteria,
                'movement_highlight' => $movementHighlight,
                'latest_activities' => $latestActivities,
                'activity_images' => $activityImages,
                'featured_clubs' => $featuredClubs,
                'featured_people' => $featuredPeople,
            ],
        ], 200);
    }
}
