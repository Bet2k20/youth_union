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

        // 3. Cơ cấu tổ chức Đoàn trường
        $organizationStructure = [
            'executive_board' => [
                'name' => 'Ban Thường Vụ & Ban Chấp Hành Đoàn Học Viện',
                'description' => 'Cơ quan lãnh đạo cao nhất của Đoàn trường giữa hai kỳ Đại hội, chỉ đạo toàn diện công tác Đoàn và phong trào thanh niên trong toàn Học viện.',
                'members' => OutstandingPerson::where('role_group', 'BI_THU_DOAN')
                    ->where('is_active', true)
                    ->get(),
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
            ],
        ], 200);
    }
}
