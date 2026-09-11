<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutstandingPerson;
use Illuminate\Http\JsonResponse;

class AboutApiController extends Controller
{
    /**
     * API dữ liệu cho màn hình Giới thiệu & Đoàn Thanh niên Học viện CSND (Figma)
     * GET /api/about
     */
    public function index(): JsonResponse
    {
        // 1. Thông tin tổng quan
        $generalInfo = [
            'name' => 'Đoàn Thanh niên Cộng sản Hồ Chí Minh Học viện Cảnh sát nhân dân',
            'short_name' => 'Đoàn Thanh niên Học viện CSND (Tuổi trẻ PPA)',
            'founded_tradition_years' => 68,
            'slogan' => 'Bản lĩnh • Kỷ cương • Trách nhiệm • Sáng tạo',
            'intro' => 'Đoàn Thanh niên Học viện Cảnh sát nhân dân là tổ chức chính trị - xã hội của đoàn viên, thanh niên, sinh viên Học viện; là cánh tay đắc lực và lực lượng hậu bị tin cậy của Đảng ủy, Ban Giám đốc Học viện trong sự nghiệp giáo dục, đào tạo nguồn cán bộ Cảnh sát nhân dân tương lai.',
            'mission' => 'Bồi dưỡng lý tưởng cách mạng, nâng cao bản lĩnh chính trị, chấp hành nghiêm điều lệnh CAND, rèn luyện thể lực và tác phong tinh nhuệ; xung kích trong nghiên cứu khoa học, chuyển đổi số và các hoạt động tình nguyện vì cộng đồng.',
            'cover_image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200&q=80',
        ];

        // 2. Lãnh đạo & Cố vấn (Ban Giám Đốc / Đảng Ủy)
        $leadership = OutstandingPerson::where('role_group', 'BGD')
            ->where('is_active', true)
            ->get();

        // 3. Cơ cấu tổ chức Đoàn trường (Ban Thường Vụ ĐTN Học viện)
        $btvMembers = OutstandingPerson::where('role_group', 'BTV_DOAN')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($btvMembers->isEmpty()) {
            $btvMembers = OutstandingPerson::where('role_group', 'BI_THU_DOAN')
                ->where('is_active', true)
                ->orderBy('order', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        $organizationStructure = [
            'executive_board' => [
                'name' => 'Ban Thường Vụ Đoàn Thanh Niên Học Viện CSND',
                'description' => 'Cơ quan lãnh đạo cao nhất của Đoàn trường giữa hai kỳ Đại hội, chỉ đạo toàn diện công tác Đoàn và phong trào thanh niên trong toàn Học viện.',
                'members' => $btvMembers,
            ],
            'departments' => [
                [
                    'name' => 'Ban Tuyên Giáo',
                    'role' => 'Tuyên truyền, giáo dục chính trị, tư tưởng, truyền thống cách mạng, điều lệnh CAND và đạo đức lối sống cho đoàn viên sinh viên.',
                ],
                [
                    'name' => 'Ban Phong Trào',
                    'role' => 'Tổ chức các phong trào xung kích, tình nguyện Mùa Hè Xanh, hiến máu nhân đạo, văn hóa văn nghệ và thể dục thể thao.',
                ],
                [
                    'name' => 'Ban Tổ Chức – Kiểm Tra',
                    'role' => 'Quản lý hồ sơ đoàn viên, xét phân loại chi đoàn, công tác kiểm tra, giám sát và bồi dưỡng đoàn viên ưu tú phát triển Đảng.',
                ],
                [
                    'name' => 'Văn Phòng Đoàn',
                    'role' => 'Tham mưu, tổng hợp, điều phối hoạt động thường nhật, quản lý cơ sở vật chất và công tác thi đua khen thưởng.',
                ],
            ],
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu trang Giới thiệu thành công',
            'data' => [
                'general_info' => $generalInfo,
                'leadership' => $leadership,
                'organization_structure' => $organizationStructure,
                'achievements' => $this->getAchievements(),
            ],
        ], 200);
    }

    /**
     * API chuyên biệt: Những thành tích, Bằng khen & Khen thưởng của ĐTN T02
     * GET /api/achievements hoặc GET /api/thanh-tich
     */
    public function achievements(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách thành tích, bằng khen, khen thưởng ĐTN T02 thành công',
            'total' => count($this->getAchievements()),
            'data' => $this->getAchievements(),
        ], 200);
    }

    /**
     * Dữ liệu Huân chương, Cờ thi đua, Bằng khen ĐTN Học viện CSND (T02)
     */
    private function getAchievements(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Huân chương Bảo vệ Tổ quốc hạng Ba',
                'category' => 'Huân chương cao quý',
                'issuer' => 'Chủ tịch nước Cộng hòa Xã hội Chủ nghĩa Việt Nam',
                'description' => 'Phần thưởng cao quý của Đảng và Nhà nước ghi nhận những cống hiến đặc biệt xuất sắc của Đoàn Thanh niên Học viện Cảnh sát nhân dân trong sự nghiệp bảo vệ Tổ quốc, giữ gìn an ninh trật tự và giáo dục đào tạo thế hệ trẻ.',
                'featured_image' => url('/images/achievements/huan-chuong-bao-ve-to-quoc-hang-3.png'),
                'images' => [
                    url('/images/achievements/huan-chuong-bao-ve-to-quoc-hang-3.png'),
                ],
            ],
            [
                'id' => 2,
                'title' => 'Cờ thi đua của Ban Chấp hành Trung ương Đoàn TNCS Hồ Chí Minh',
                'category' => 'Cờ thi đua xuất sắc',
                'issuer' => 'BCH Trung ương Đoàn TNCS Hồ Chí Minh',
                'time_span' => 'Các năm học: 2016-2017; 2019-2020; 2022-2023',
                'description' => 'Đơn vị xuất sắc dẫn đầu công tác Đoàn và phong trào thanh niên khối trường Đại học, Học viện toàn quốc các năm học 2016-2017, 2019-2020, 2022-2023.',
                'featured_image' => url('/images/achievements/co-thi-dua-tw-doan-1.png'),
                'images' => [
                    url('/images/achievements/co-thi-dua-tw-doan-1.png'),
                    url('/images/achievements/co-thi-dua-tw-doan-2.jpg'),
                ],
            ],
            [
                'id' => 3,
                'title' => 'Cờ thi đua của Ban Chấp hành Đoàn TNCS Hồ Chí Minh Bộ Công an',
                'category' => 'Cờ thi đua xuất sắc',
                'issuer' => 'Đoàn Thanh niên Bộ Công an',
                'time_span' => 'Các năm học: từ 2015-2016 đến năm học 2022-2023',
                'description' => 'Đơn vị xuất sắc liên tục 8 năm liền (từ năm học 2015-2016 đến 2022-2023) trong phong trào thi đua của Tuổi trẻ lực lượng Công an nhân dân.',
                'featured_image' => url('/images/achievements/co-thi-dua-doan-bo-cong-an.jpg'),
                'images' => [
                    url('/images/achievements/co-thi-dua-doan-bo-cong-an.jpg'),
                ],
            ],
            [
                'id' => 4,
                'title' => 'Bằng khen của Bộ trưởng Bộ Công an, Tổng cục trưởng Tổng cục Chính trị CAND',
                'category' => 'Bằng khen cấp Bộ & Tổng cục',
                'issuer' => 'Bộ Công an & Tổng cục Chính trị CAND',
                'time_span' => 'Với 30 lượt trong giai đoạn 2015-2025',
                'description' => 'Ghi nhận thành tích xuất sắc trong công tác đào tạo, xung kích đảm bảo an ninh trật tự, nghiên cứu khoa học và các đợt thi đua cao điểm của lực lượng CAND.',
                'featured_image' => url('/images/achievements/bang-khen-bo-truong-bca-1.jpg'),
                'images' => [
                    url('/images/achievements/bang-khen-bo-truong-bca-1.jpg'),
                    url('/images/achievements/bang-khen-bo-truong-bca-2.jpg'),
                    url('/images/achievements/bang-khen-tong-cuc-chinh-tri-cand.jpg'),
                ],
            ],
            [
                'id' => 5,
                'title' => 'Bằng khen của Trung ương Đoàn TNCS Hồ Chí Minh, Đoàn Thanh niên Bộ Công an',
                'category' => 'Bằng khen Trung ương Đoàn & Đoàn Bộ',
                'issuer' => 'Trung ương Đoàn & Đoàn Thanh niên Bộ Công an',
                'time_span' => 'Với 50 lượt trong giai đoạn 2010-2025',
                'description' => 'Ghi nhận thành tích xuất sắc trong công tác Đoàn và phong trào thanh thiếu nhi, các chiến dịch tình nguyện Mùa hè xanh, hiến máu tình nguyện và đền ơn đáp nghĩa.',
                'featured_image' => url('/images/achievements/bang-khen-tw-doan-1.jpg'),
                'images' => [
                    url('/images/achievements/bang-khen-tw-doan-1.jpg'),
                    url('/images/achievements/bang-khen-tw-doan-2.jpg'),
                    url('/images/achievements/bang-khen-doan-bca.jpg'),
                ],
            ],
        ];
    }
}
