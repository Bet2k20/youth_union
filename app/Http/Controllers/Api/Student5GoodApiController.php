<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutstandingPerson;
use Illuminate\Http\JsonResponse;

class Student5GoodApiController extends Controller
{
    /**
     * API dữ liệu cho màn hình Hành trình phấn đấu / Tiêu chí Sinh viên 5 Tốt (Figma)
     * GET /api/student-5-good
     */
    public function index(): JsonResponse
    {
        // 1. Chi tiết 5 Tiêu chuẩn Sinh viên 5 Tốt chuẩn Học viện CSND
        $criteria = [
            [
                'step' => 1,
                'title' => 'Đạo đức tốt',
                'description' => 'Điểm rèn luyện đạt từ loại Tốt trở lên. Chấp hành nghiêm chỉnh chủ trương của Đảng, pháp luật của Nhà nước và Điều lệnh Công an nhân dân. Không vi phạm kỷ luật.',
                'standards' => [
                    'Điểm rèn luyện cả năm học đạt từ 80 điểm trở lên',
                    'Tuyệt đối trung thành với Đảng, Nhà nước và nhân dân',
                    'Tác phong quân tư trang chuẩn mực, lễ tiết tác phong chuẩn CAND',
                ],
            ],
            [
                'step' => 2,
                'title' => 'Học tập tốt',
                'description' => 'Có tinh thần vượt khó, chủ động sáng tạo trong học tập; tích cực tham gia các phong trào nghiên cứu khoa học và chuyên ngành nghiệp vụ Cảnh sát.',
                'standards' => [
                    'Điểm trung bình học tập cả năm đạt từ 8.0 (hoặc 3.2/4.0) trở lên',
                    'Tham gia ít nhất 01 đề tài Nghiên cứu khoa học sinh viên hoặc có bài viết đăng kỷ yếu/hội thảo',
                    'Không nợ môn, không thi lại trong năm học xét chọn',
                ],
            ],
            [
                'step' => 3,
                'title' => 'Thể lực tốt',
                'description' => 'Rèn luyện thân thể theo gương Bác Hồ vĩ đại, đạt chuẩn thể lực CAND, có kỹ năng võ thuật và bắn súng vững vàng.',
                'standards' => [
                    'Đạt tiêu chuẩn rèn luyện thể lực chiến sĩ CAND hằng năm',
                    'Tham gia các hội thao thể thao, giải bóng đá, bóng chuyền, chạy vũ trang hoặc CLB võ thuật',
                    'Biết ít nhất 01 môn thể thao hoặc võ thuật ứng dụng CAND',
                ],
            ],
            [
                'step' => 4,
                'title' => 'Tình nguyện tốt',
                'description' => 'Xung kích vì cộng đồng, có tinh thần tương thân tương ái, sẵn sàng nhận nhiệm vụ tình nguyện nơi vùng sâu, vùng xa.',
                'standards' => [
                    'Tham gia ít nhất 01 đợt hiến máu tình nguyện trong năm học',
                    'Tham gia chiến dịch Mùa hè xanh, Tiếp sức mùa thi hoặc các hoạt động đền ơn đáp nghĩa',
                    'Được đoàn trường hoặc địa phương tặng giấy khen/chứng nhận tình nguyện',
                ],
            ],
            [
                'step' => 5,
                'title' => 'Hội nhập tốt',
                'description' => 'Nâng cao năng lực ngoại ngữ, kỹ năng giao tiếp quốc tế, tin học ứng dụng và chuyển đổi số.',
                'standards' => [
                    'Đạt chứng chỉ ngoại ngữ quốc tế (IELTS/TOEIC/VSTEP) hoặc có thành tích trong CLB tiếng Anh',
                    'Thành thạo kỹ năng tin học văn phòng, kỹ năng số 4.0',
                    'Tham gia các diễn đàn thanh niên, hội thảo giao lưu quốc tế nếu có',
                ],
            ],
        ];

        // 2. Danh sách gương mặt tiêu biểu đạt danh hiệu Sinh viên 5 Tốt
        $exemplaryStudents = OutstandingPerson::where('role_group', 'DOAN_VIEN')
            ->where('is_active', true)
            ->latest('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu tiêu chí Sinh viên 5 Tốt thành công',
            'data' => [
                'title' => 'Hành trình phấn đấu danh hiệu "Sinh viên 5 Tốt"',
                'subtitle' => 'Chặng đường trưởng thành của người đoàn viên – từ đoàn viên gương mẫu tới người chiến sĩ Cảnh sát nhân dân bản lĩnh, nhân văn, vì nhân dân phục vụ.',
                'criteria' => $criteria,
                'exemplary_students' => $exemplaryStudents,
                'guide_pdf_url' => '/uploads/documents/huong_dan_sinh_vien_5_tot.pdf',
            ],
        ], 200);
    }
}
