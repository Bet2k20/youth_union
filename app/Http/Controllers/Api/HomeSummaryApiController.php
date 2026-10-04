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

        // 4. Số liệu thống kê (Counter Metrics chuẩn: 4114 Đoàn viên, 24 Cơ sở Đoàn, 9 CLB, 100+ Chương trình/năm)
        $metrics = MetricsApiController::getMetricsData();

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

        $quickAccess = self::getQuickAccessData();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu trang chủ thành công',
            'data' => [
                'hero_banner' => $heroBanner,
                'banners' => $banners,
                'quick_access' => $quickAccess,
                'explore_sections' => $quickAccess,
                'kham_pha_so_tay' => $quickAccess,
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

    /**
     * API chuyên biệt cho 8 mục Khám phá Sổ tay / Truy cập nhanh
     * GET /api/quick-access hoặc GET /api/kham-pha hoặc GET /api/kham-pha-so-tay
     */
    public function quickAccess(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách 8 mục Khám phá Sổ tay thành công',
            'total' => 8,
            'data' => self::getQuickAccessData(),
        ], 200);
    }

    /**
     * Dữ liệu chuẩn hóa cho 8 mục Khám phá Sổ tay (hình ảnh từ thư mục MỤC ẢNH)
     */
    public static function getQuickAccessData(): array
    {
        return [
            [
                'id' => 1,
                'slug' => 'tong-quan-hoc-vien',
                'title' => 'Tổng quan Học viện',
                'subtitle' => 'Sứ mệnh, mục tiêu & giá trị cốt lõi.',
                'description' => 'Sứ mệnh, mục tiêu & giá trị cốt lõi.',
                'image' => url('/images/kham-pha/tong-quan-hoc-vien.webp'),
                'image_url' => url('/images/kham-pha/tong-quan-hoc-vien.webp'),
                'link' => '/gioi-thieu',
                'url' => '/gioi-thieu',
            ],
            [
                'id' => 2,
                'slug' => 'ban-giam-doc-hoc-vien',
                'title' => 'Ban Giám đốc Học viện',
                'subtitle' => 'Tập thể lãnh đạo Học viện CSND.',
                'description' => 'Tập thể lãnh đạo Học viện CSND.',
                'image' => url('/images/kham-pha/ban-giam-doc.png'),
                'image_url' => url('/images/kham-pha/ban-giam-doc.png'),
                'link' => '/ban-giam-doc',
                'url' => '/ban-giam-doc',
            ],
            [
                'id' => 3,
                'slug' => 'doan-thanh-nien',
                'title' => 'Đoàn Thanh niên',
                'subtitle' => 'Chức năng, cơ cấu & thành tích.',
                'description' => 'Chức năng, cơ cấu & thành tích.',
                'image' => url('/images/kham-pha/doan-thanh-nien.webp'),
                'image_url' => url('/images/kham-pha/doan-thanh-nien.webp'),
                'link' => '/doan-thanh-nien',
                'url' => '/doan-thanh-nien',
            ],
            [
                'id' => 4,
                'slug' => 'clb-doi-nhom',
                'title' => 'CLB – Đội – Nhóm',
                'subtitle' => 'Nội san, PPA TV, Dân vũ, Guitar...',
                'description' => 'Nội san, PPA TV, Dân vũ, Guitar...',
                'image' => url('/images/kham-pha/cau-lac-bo.jpg'),
                'image_url' => url('/images/kham-pha/cau-lac-bo.jpg'),
                'link' => '/cau-lac-bo',
                'url' => '/cau-lac-bo',
            ],
            [
                'id' => 5,
                'slug' => 'phong-trao-doan',
                'title' => 'Phong trào Đoàn',
                'subtitle' => 'Tình nguyện, sáng tạo, đền ơn đáp...',
                'description' => 'Tình nguyện, sáng tạo, đền ơn đáp...',
                'image' => url('/images/kham-pha/phong-trao-doan.jpg'),
                'image_url' => url('/images/kham-pha/phong-trao-doan.jpg'),
                'link' => '/hoat-dong',
                'url' => '/hoat-dong',
            ],
            [
                'id' => 6,
                'slug' => 'hanh-trinh-phan-dau',
                'title' => 'Hành trình phấn đấu',
                'subtitle' => 'Từ đoàn viên đến đảng viên.',
                'description' => 'Từ đoàn viên đến đảng viên.',
                'image' => url('/images/kham-pha/hanh-trinh-phan-dau.jpg'),
                'image_url' => url('/images/kham-pha/hanh-trinh-phan-dau.jpg'),
                'link' => '/hanh-trinh-phan-dau',
                'url' => '/hanh-trinh-phan-dau',
            ],
            [
                'id' => 7,
                'slug' => 'guong-sang-doan-vien',
                'title' => 'Gương sáng Đoàn viên',
                'subtitle' => 'Những tấm gương lan tỏa.',
                'description' => 'Những tấm gương lan tỏa.',
                'image' => url('/images/kham-pha/guong-sang-doan-vien.jpg'),
                'image_url' => url('/images/kham-pha/guong-sang-doan-vien.jpg'),
                'link' => '/guong-sang-doan-vien',
                'url' => '/guong-sang-doan-vien',
            ],
            [
                'id' => 8,
                'slug' => 'thu-vien',
                'title' => 'Thư viện',
                'subtitle' => 'Hình ảnh, video & thông tư PDF.',
                'description' => 'Hình ảnh, video & thông tư PDF.',
                'image' => url('/images/kham-pha/thu-vien-anh.webp'),
                'image_url' => url('/images/kham-pha/thu-vien-anh.webp'),
                'link' => '/thu-vien',
                'url' => '/thu-vien',
            ],
        ];
    }
}
