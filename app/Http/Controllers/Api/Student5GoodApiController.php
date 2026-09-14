<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutstandingPerson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Student5GoodApiController extends Controller
{
    /**
     * API dữ liệu tổng hợp toàn bộ màn hình Hành trình phấn đấu (Figma)
     * GET /api/student-5-good hoặc GET /api/hanh-trinh-phan-dau
     */
    public function index(): JsonResponse
    {
        $awardsData = $this->getAwardsData();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu Hành trình phấn đấu thành công',
            'data' => [
                'title' => 'Hành Trình Phấn Đấu Của Đoàn Viên PPA',
                'subtitle' => 'Chặng đường rèn luyện và trưởng thành của người chiến sĩ Cảnh sát nhân dân tương lai – Từ Đoàn viên tiêu biểu đến Đảng viên Đảng Cộng sản Việt Nam.',
                'student_5_good' => $this->getStudent5GoodData(),
                'awards_comparison' => $awardsData['levels_comparison'],
                'sao_thang_gieng' => $awardsData['sao_thang_gieng'],
                'thu_khoa' => $awardsData['thu_khoa'],
                'vu_a_dinh' => $awardsData['vu_a_dinh'],
                'ly_tu_trong' => $awardsData['ly_tu_trong'],
                'awards_list' => $awardsData['awards_list'],
                'danh_sach_giai_thuong' => $awardsData['awards_list'],
                'member_classification' => $this->getMemberClassificationData(),
                'elite_member' => $this->getEliteMemberData(),
                'party_admission' => $this->getPartyAdmissionData(),
                'exemplary_students' => OutstandingPerson::where('role_group', 'DOAN_VIEN')
                    ->where('is_active', true)
                    ->orderBy('order', 'asc')
                    ->orderBy('id', 'desc')
                    ->get(),
                'download_documents' => [
                    [
                        'name' => 'Hướng dẫn xếp loại đoàn viên, đoàn viên ưu tú và kết nạp Đảng',
                        'code' => 'HD 638 & HD 02-HD/ĐTN-T02',
                        'file_url' => url('/uploads/documents/phan_loai_doan_vien.docx'),
                        'type' => 'DOCX',
                    ],
                    [
                        'name' => 'Quy định tiêu chuẩn Giải thưởng Sinh viên 5 Tốt các cấp và Giải thưởng Sao Tháng Giêng',
                        'code' => 'SV5T & STG - TW Đoàn & ĐTN BCA',
                        'file_url' => url('/uploads/documents/giai_thuong_so_tay.docx'),
                        'type' => 'DOCX',
                    ],
                ],
            ],
        ], 200);
    }

    /**
     * API chuyên biệt: Tiêu chí xếp loại đoàn viên (HD 638)
     * GET /api/phan-loai-doan-vien
     */
    public function memberClassification(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu tiêu chí xếp loại đoàn viên thành công',
            'data' => $this->getMemberClassificationData(),
        ], 200);
    }

    /**
     * API chuyên biệt: Lộ trình 4 giai đoạn phấn đấu (Đoàn viên -> Đoàn viên ưu tú -> Cảm tình Đảng -> Đảng viên)
     * GET /api/doan-vien-uu-tu
     */
    public function eliteMember(Request $request): JsonResponse
    {
        $stages = $this->getEliteMemberStages();

        // Nếu Frontend tìm kiếm hoặc lọc theo giai đoạn cụ thể (?stage=doan-vien hoặc ?slug=cam-tinh-dang)
        if ($request->filled('stage') || $request->filled('slug') || $request->filled('name')) {
            $keyword = mb_strtolower(trim($request->input('stage') ?? $request->input('slug') ?? $request->input('name')));
            $filtered = array_values(array_filter($stages, function ($item) use ($keyword) {
                return mb_strtolower($item['name']) === $keyword ||
                       mb_strtolower($item['slug']) === $keyword ||
                       str_contains(mb_strtolower($item['name']), $keyword);
            }));
            if (!empty($filtered)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lấy chi tiết giai đoạn thành công',
                    'data' => $filtered[0],
                ], 200);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu lộ trình phấn đấu và Đoàn viên ưu tú thành công',
            'total' => count($stages),
            'title' => 'Lộ Trình Phấn Đấu: Từ Đoàn Viên Đến Đảng Viên',
            'legal_basis' => 'Hướng dẫn số 02-HD/ĐTN-T02, Hướng dẫn 638-HD/ĐTNCA & Thông tư 26/2022/TT-BCA',
            'data' => $stages,
            'stages' => $stages,
            'items' => $stages,
            'danh_sach' => $stages,
            'details' => [
                'title' => 'Công Tác Xét Chọn & Giới Thiệu Đoàn Viên Ưu Tú',
                'legal_basis' => 'Hướng dẫn số 02-HD/ĐTN-T02 ngày 20/02/2025 của Ban Chấp hành Đoàn Học viện Cảnh sát nhân dân',
                'standards' => $this->getEliteMemberStandards(),
                'process' => $this->getEliteMemberProcess(),
            ],
        ], 200);
    }

    /**
     * API chuyên biệt: Điều kiện kết nạp Đảng / Chuyển Đảng chính thức
     * GET /api/ket-nap-dang
     */
    public function partyAdmission(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu điều kiện kết nạp Đảng thành công',
            'data' => $this->getPartyAdmissionData(),
        ], 200);
    }

    /**
     * Dữ liệu 5 Tiêu chí Sinh viên 5 Tốt chuẩn PPA
     */
    private function getStudent5GoodData(): array
    {
        return [
            'title' => 'Phong Trào "Sinh Viên 5 Tốt"',
            'subtitle' => 'Danh hiệu cao quý ghi nhận sự phấn đấu toàn diện của học viên Cảnh sát nhân dân trên 5 phương diện.',
            'criteria' => [
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
            ],
        ];
    }

    /**
     * Dữ liệu Tiêu chí xếp loại đoàn viên (HD 638-HD/ĐTNCA)
     */
    private function getMemberClassificationData(): array
    {
        return [
            'title' => 'Tiêu Chí Xếp Loại Chất Lượng Đoàn Viên Hằng Năm',
            'legal_basis' => 'Hướng dẫn 638-HD/ĐTNCA ngày 13/12/2019 của Ban Chấp hành Đoàn Bộ Công an về việc kiểm điểm và đánh giá, xếp loại chất lượng hằng năm đối với tổ chức đoàn, tập thể lãnh đạo và cá nhân.',
            'target_subjects' => [
                'title' => '1. Đối Tượng & Điều Kiện Đánh Giá',
                'items' => [
                    [
                        'label' => 'Điều kiện chung',
                        'content' => 'Sinh hoạt tại chi đoàn liên tục từ 06 tháng trở lên.',
                    ],
                    [
                        'label' => 'Chuyển sinh hoạt chưa đủ 6 tháng',
                        'content' => 'Đơn vị mới đánh giá dựa trên cơ sở nhận xét, đánh giá của đơn vị sinh hoạt cũ.',
                    ],
                    [
                        'label' => 'Thai sản / Học tập, công tác xa (< 3 tháng)',
                        'content' => 'Đánh giá xếp loại căn cứ theo thời gian sinh hoạt thực tế tại chi đoàn.',
                    ],
                    [
                        'label' => 'Nghỉ ốm (> 3 tháng)',
                        'content' => 'Không xếp loại từ mức "Hoàn thành tốt nhiệm vụ" trở lên.',
                    ],
                    [
                        'label' => 'Đảng viên đang sinh hoạt Đoàn',
                        'content' => 'Không xếp loại Đoàn viên; chỉ tham gia đánh giá Chương trình rèn luyện Đoàn viên để chuyển kết quả nhận xét cho Chi bộ phục vụ phân loại Đảng viên.',
                    ],
                ],
            ],
            'quality_levels' => [
                'title' => '2. Bảng Mức Xếp Loại Chất Lượng Đoàn Viên',
                'columns' => [
                    ['key' => 'criterion', 'label' => 'Tiêu chí đánh giá'],
                    ['key' => 'htxs', 'label' => 'Hoàn thành xuất sắc (HTXS)'],
                    ['key' => 'htt', 'label' => 'Hoàn thành tốt (HTT)'],
                    ['key' => 'ht', 'label' => 'Hoàn thành (HT)'],
                    ['key' => 'kht', 'label' => 'Không hoàn thành (KHT)'],
                ],
                'rows' => [
                    [
                        'criterion' => 'Tư tưởng chính trị',
                        'htxs' => 'Mẫu mực, chấp hành nghiêm pháp luật, quy định của Đảng, Nhà nước và Học viện',
                        'htt' => 'Chấp hành nghiêm pháp luật, quy định của Đảng, Nhà nước và Học viện',
                        'ht' => 'Chấp hành pháp luật, quy định của Đảng, Nhà nước và Học viện',
                        'kht' => 'Bị kỷ luật (≥ khiển trách), vi phạm điều lệ/nội quy Học viện',
                    ],
                    [
                        'criterion' => 'Tham gia hoạt động Đoàn',
                        'htxs' => '≥ 90% các hoạt động, phong trào',
                        'htt' => '≥ 80% các hoạt động, phong trào',
                        'ht' => '≥ 50% các hoạt động, phong trào',
                        'kht' => 'Dưới 50% các hoạt động, phong trào',
                    ],
                    [
                        'criterion' => 'Nhiệm vụ công tác được giao',
                        'htxs' => '100% đúng tiến độ, bảo đảm chất lượng và hiệu quả cao',
                        'htt' => '100% nhiệm vụ (có việc trễ hạn/chưa đạt do nguyên nhân chủ quan)',
                        'ht' => 'Từ 80% đến dưới 100% nhiệm vụ (có việc bị nhắc nhở)',
                        'kht' => 'Dưới 80% nhiệm vụ (do nguyên nhân chủ quan)',
                    ],
                    [
                        'criterion' => 'Học tập (Điểm trung bình môn - TBM)',
                        'htxs' => '≥ 7.0 (Đối với hệ Cử tuyển / Cán bộ đi học: ≥ 6.5)',
                        'htt' => '≥ 6.5 (Đối với hệ Cử tuyển / Cán bộ đi học: ≥ 6.0)',
                        'ht' => 'Từ 5.0 đến dưới 6.5 (hoặc 5.0 - 6.0)',
                        'kht' => '< 5.0 hoặc chưa hoàn thành các môn học theo quy định',
                    ],
                    [
                        'criterion' => 'Điểm rèn luyện',
                        'htxs' => 'Đạt loại Tốt trở lên',
                        'htt' => 'Đạt loại Tốt trở lên',
                        'ht' => 'Đạt loại Khá trở lên',
                        'kht' => 'Xếp loại Trung bình hoặc Yếu',
                    ],
                    [
                        'criterion' => 'Rèn luyện tư cách người CAND theo 6 điều Bác Hồ dạy',
                        'htxs' => 'Hoàn thành xuất sắc',
                        'htt' => 'Hoàn thành tốt',
                        'ht' => 'Hoàn thành',
                        'kht' => 'Không hoàn thành',
                    ],
                    [
                        'criterion' => 'Đoàn phí & Nghĩa vụ nơi cư trú',
                        'htxs' => 'Đóng đoàn phí đúng quy định; tích cực tham gia hoạt động nơi cư trú',
                        'htt' => 'Đóng đoàn phí đúng quy định; thực hiện đầy đủ nghĩa vụ nơi cư trú',
                        'ht' => 'Đoàn phí đóng chưa đúng hạn; còn thiếu sót tại nơi cư trú',
                        'kht' => 'Đóng thiếu hoặc không đóng đoàn phí; vi phạm quy định nơi cư trú',
                    ],
                ],
            ],
        ];
    }

    /**
     * Danh sách 4 cấp bậc / chặng đường theo chuẩn Figma & yêu cầu:
     * 1. Đoàn viên
     * 2. Đoàn viên ưu tú
     * 3. Cảm tình Đảng
     * 4. Đảng viên
     */
    private function getEliteMemberStages(): array
    {
        return [
            [
                'id' => 1,
                'step' => 1,
                'name' => 'Đoàn viên',
                'ten' => 'Đoàn viên',
                'title' => 'Đoàn viên',
                'slug' => 'doan-vien',
                'description' => 'Chấp hành Điều lệ, gương mẫu',
                'mo_ta' => 'Chấp hành Điều lệ, gương mẫu',
                'content' => 'Đoàn viên là hạt nhân nòng cốt của tuổi trẻ, gương mẫu chấp hành Điều lệ Đoàn, tích cực tham gia rèn luyện, học tập và các phong trào thi đua của Học viện.',
                'noi_dung' => 'Đoàn viên là hạt nhân nòng cốt của tuổi trẻ, gương mẫu chấp hành Điều lệ Đoàn, tích cực tham gia rèn luyện, học tập và các phong trào thi đua của Học viện.',
                'requirements' => [
                    'Tuổi từ đủ 16 tuổi đến 30 tuổi, tự nguyện viết đơn gia nhập Đoàn và tán thành Điều lệ Đoàn TNCS Hồ Chí Minh.',
                    'Gương mẫu chấp hành nghiêm chỉnh Điều lệ Đoàn, Điều lệnh CAND, pháp luật của Nhà nước và quy chế của Học viện.',
                    'Tích cực học tập, rèn luyện tư cách người chiến sĩ CAND theo 6 điều Bác Hồ dạy.',
                    'Tham gia đầy đủ, tích cực các hoạt động phong trào thanh niên do Chi đoàn và Đoàn trường tổ chức.',
                    'Đóng đoàn phí đúng hạn và thực hiện đầy đủ nghĩa vụ nơi cư trú theo quy định.',
                ],
                'danh_sach_yeu_cau' => [
                    'Tuổi từ đủ 16 tuổi đến 30 tuổi, tự nguyện viết đơn gia nhập Đoàn và tán thành Điều lệ Đoàn TNCS Hồ Chí Minh.',
                    'Gương mẫu chấp hành nghiêm chỉnh Điều lệ Đoàn, Điều lệnh CAND, pháp luật của Nhà nước và quy chế của Học viện.',
                    'Tích cực học tập, rèn luyện tư cách người chiến sĩ CAND theo 6 điều Bác Hồ dạy.',
                    'Tham gia đầy đủ, tích cực các hoạt động phong trào thanh niên do Chi đoàn và Đoàn trường tổ chức.',
                    'Đóng đoàn phí đúng hạn và thực hiện đầy đủ nghĩa vụ nơi cư trú theo quy định.',
                ],
                'badge' => 'Chặng 1',
                'level' => 'Đoàn viên',
            ],
            [
                'id' => 2,
                'step' => 2,
                'name' => 'Đoàn viên ưu tú',
                'ten' => 'Đoàn viên ưu tú',
                'title' => 'Đoàn viên ưu tú',
                'slug' => 'doan-vien-uu-tu',
                'description' => 'Phấn đấu nổi bật, được công nhận',
                'mo_ta' => 'Phấn đấu nổi bật, được công nhận',
                'content' => 'Đoàn viên ưu tú là những đoàn viên có phẩm chất, năng lực và kết quả phấn đấu nổi bật, được tổ chức Đoàn xem xét, công nhận và giới thiệu cho tổ chức Đảng bồi dưỡng theo quy định.',
                'noi_dung' => 'Đoàn viên ưu tú là những đoàn viên có phẩm chất, năng lực và kết quả phấn đấu nổi bật, được tổ chức Đoàn xem xét, công nhận và giới thiệu cho tổ chức Đảng bồi dưỡng theo quy định.',
                'requirements' => [
                    'Xếp loại chất lượng đoàn viên đạt mức "Hoàn thành xuất sắc nhiệm vụ" trong năm học theo Hướng dẫn 638-HD/ĐTNCA.',
                    'Điểm trung bình học tập cả năm: ≥ 7.0 (học sinh phổ thông, chiến sĩ nghĩa vụ) hoặc ≥ 6.5 (cán bộ đi học).',
                    'Điểm rèn luyện các tháng trong năm học đạt loại Tốt liên tục.',
                    'Đạt ít nhất 01 giải thưởng cấp Học viện trở lên (NCKH, văn hóa văn nghệ, TDTT) hoặc được tặng Giấy khen cấp Đoàn Học viện trở lên.',
                    'Được tập thể Chi đoàn tổ chức họp bình xét, suy tôn và biểu quyết thống nhất giới thiệu.',
                ],
                'danh_sach_yeu_cau' => [
                    'Xếp loại chất lượng đoàn viên đạt mức "Hoàn thành xuất sắc nhiệm vụ" trong năm học theo Hướng dẫn 638-HD/ĐTNCA.',
                    'Điểm trung bình học tập cả năm: ≥ 7.0 (học sinh phổ thông, chiến sĩ nghĩa vụ) hoặc ≥ 6.5 (cán bộ đi học).',
                    'Điểm rèn luyện các tháng trong năm học đạt loại Tốt liên tục.',
                    'Đạt ít nhất 01 giải thưởng cấp Học viện trở lên (NCKH, văn hóa văn nghệ, TDTT) hoặc được tặng Giấy khen cấp Đoàn Học viện trở lên.',
                    'Được tập thể Chi đoàn tổ chức họp bình xét, suy tôn và biểu quyết thống nhất giới thiệu.',
                ],
                'badge' => 'Chặng 2',
                'level' => 'Đoàn viên ưu tú',
            ],
            [
                'id' => 3,
                'step' => 3,
                'name' => 'Cảm tình Đảng',
                'ten' => 'Cảm tình Đảng',
                'title' => 'Cảm tình Đảng',
                'slug' => 'cam-tinh-dang',
                'description' => 'Học lớp bồi dưỡng nhận thức',
                'mo_ta' => 'Học lớp bồi dưỡng nhận thức',
                'content' => 'Đoàn viên ưu tú sau khi được giới thiệu sẽ tham gia học lớp bồi dưỡng nhận thức về Đảng, tiếp tục rèn luyện bản lĩnh chính trị và thử thách qua các nhiệm vụ thực tiễn.',
                'noi_dung' => 'Đoàn viên ưu tú sau khi được giới thiệu sẽ tham gia học lớp bồi dưỡng nhận thức về Đảng, tiếp tục rèn luyện bản lĩnh chính trị và thử thách qua các nhiệm vụ thực tiễn.',
                'requirements' => [
                    'Đã được công nhận danh hiệu Đoàn viên ưu tú và được Chi đoàn giới thiệu cho Chi bộ Đảng xem xét.',
                    'Hoàn thành khóa học và được cấp Giấy chứng nhận Lớp bồi dưỡng nhận thức về Đảng (Lớp Cảm tình Đảng).',
                    'Có đơn tự nguyện xin gia nhập Đảng Cộng sản Việt Nam và lý lịch cá nhân rõ ràng.',
                    'Được Chi bộ phân công Đảng viên chính thức theo dõi, kèm cặp và giúp đỡ.',
                    'Tiếp tục duy trì điểm rèn luyện hằng tháng đạt loại Tốt và điểm học tập từ loại Khá trở lên.',
                ],
                'danh_sach_yeu_cau' => [
                    'Đã được công nhận danh hiệu Đoàn viên ưu tú và được Chi đoàn giới thiệu cho Chi bộ Đảng xem xét.',
                    'Hoàn thành khóa học và được cấp Giấy chứng nhận Lớp bồi dưỡng nhận thức về Đảng (Lớp Cảm tình Đảng).',
                    'Có đơn tự nguyện xin gia nhập Đảng Cộng sản Việt Nam và lý lịch cá nhân rõ ràng.',
                    'Được Chi bộ phân công Đảng viên chính thức theo dõi, kèm cặp và giúp đỡ.',
                    'Tiếp tục duy trì điểm rèn luyện hằng tháng đạt loại Tốt và điểm học tập từ loại Khá trở lên.',
                ],
                'badge' => 'Chặng 3',
                'level' => 'Cảm tình Đảng',
            ],
            [
                'id' => 4,
                'step' => 4,
                'name' => 'Đảng viên',
                'ten' => 'Đảng viên',
                'title' => 'Đảng viên',
                'slug' => 'dang-vien',
                'description' => 'Đứng trong hàng ngũ của Đảng',
                'mo_ta' => 'Đứng trong hàng ngũ của Đảng',
                'content' => 'Vinh dự đứng trong hàng ngũ của Đảng Cộng sản Việt Nam, tiên phong gương mẫu trên mọi mặt trận công tác và cống hiến trọn đời cho sự nghiệp của Đảng và dân tộc.',
                'noi_dung' => 'Vinh dự đứng trong hàng ngũ của Đảng Cộng sản Việt Nam, tiên phong gương mẫu trên mọi mặt trận công tác và cống hiến trọn đời cho sự nghiệp của Đảng và dân tộc.',
                'requirements' => [
                    'Tuổi đời từ đủ 18 tuổi trở lên tại thời điểm Chi bộ họp xét kết nạp.',
                    'Có bản thẩm tra lý lịch chính trị đạt tiêu chuẩn quy định của Bộ Công an đối với người vào Đảng trong lực lượng CAND.',
                    'Kết quả học tập đạt loại Khá trở lên, điểm rèn luyện đạt loại Tốt trở lên liên tục (theo Thông tư 26/2022/TT-BCA).',
                    'Được sự bảo đảm và giới thiệu của 02 đảng viên chính thức (hoặc 01 đảng viên chính thức và Ban Chấp hành Đoàn trường).',
                    'Được Chi bộ biểu quyết thông qua và Đảng ủy Học viện CSND ra Quyết định kết nạp.',
                    'Trải qua 12 tháng thử thách với tư cách Đảng viên dự bị trước khi được xét công nhận Đảng viên chính thức.',
                ],
                'danh_sach_yeu_cau' => [
                    'Tuổi đời từ đủ 18 tuổi trở lên tại thời điểm Chi bộ họp xét kết nạp.',
                    'Có bản thẩm tra lý lịch chính trị đạt tiêu chuẩn quy định của Bộ Công an đối với người vào Đảng trong lực lượng CAND.',
                    'Kết quả học tập đạt loại Khá trở lên, điểm rèn luyện đạt loại Tốt trở lên liên tục (theo Thông tư 26/2022/TT-BCA).',
                    'Được sự bảo đảm và giới thiệu của 02 đảng viên chính thức (hoặc 01 đảng viên chính thức và Ban Chấp hành Đoàn trường).',
                    'Được Chi bộ biểu quyết thông qua và Đảng ủy Học viện CSND ra Quyết định kết nạp.',
                    'Trải qua 12 tháng thử thách với tư cách Đảng viên dự bị trước khi được xét công nhận Đảng viên chính thức.',
                ],
                'badge' => 'Chặng 4',
                'level' => 'Đảng viên',
            ],
        ];
    }

    /**
     * Dữ liệu Tiêu chuẩn & Quy trình Đoàn viên ưu tú (HD 02-HD/ĐTN-T02)
     */
    private function getEliteMemberData(): array
    {
        $stages = $this->getEliteMemberStages();

        return [
            'title' => 'Công Tác Xét Chọn & Giới Thiệu Đoàn Viên Ưu Tú',
            'legal_basis' => 'Hướng dẫn số 02-HD/ĐTN-T02 ngày 20/02/2025 của Ban Chấp hành Đoàn Học viện Cảnh sát nhân dân về Hướng dẫn công tác, đánh giá, công nhận và giới thiệu đoàn viên ưu tú cho Đảng.',
            'stages' => $stages,
            'items' => $stages,
            'danh_sach' => $stages,
            'standards' => $this->getEliteMemberStandards(),
            'process' => $this->getEliteMemberProcess(),
        ];
    }

    /**
     * Tiêu chuẩn xét công nhận Đoàn viên ưu tú chi tiết
     */
    private function getEliteMemberStandards(): array
    {
        return [
            'title' => '1. Tiêu Chuẩn Xét Công Nhận Đoàn Viên Ưu Tú',
            'academic_and_training' => [
                'group_name' => 'Điều kiện học tập & rèn luyện',
                'requirements' => [
                    [
                        'target' => 'Học sinh phổ thông, Chiến sĩ nghĩa vụ',
                        'academic' => 'Điểm trung bình học tập ≥ 7.0',
                        'training' => 'Điểm rèn luyện các tháng trong năm đạt loại Tốt',
                    ],
                    [
                        'target' => 'Cán bộ đi học',
                        'academic' => 'Điểm trung bình học tập ≥ 6.5',
                        'training' => 'Điểm rèn luyện các tháng trong năm đạt loại Tốt',
                    ],
                ],
            ],
            'additional_conditions' => [
                'group_name' => 'Điều kiện bổ sung (Bắt buộc đạt tối thiểu 01 trong các tiêu chí sau)',
                'items' => [
                    'Đạt giải thưởng trong các cuộc thi Văn hóa – Văn nghệ – Thể dục thể thao từ cấp Học viện trở lên.',
                    'Là cán bộ Đoàn (Ủy viên BCH Đoàn Học viện, Liên chi đoàn, Chi đoàn) và tập thể Chi đoàn đạt danh hiệu "Hoàn thành xuất sắc nhiệm vụ".',
                    'Được tặng ít nhất 01 Giấy khen cấp Đoàn Học viện trở lên về thành tích trong công tác Đoàn và phong trào thanh niên.',
                    'Là thành viên hoạt động tích cực của các Câu lạc bộ trực thuộc Đoàn trường (CLB Nội san – Truyền thanh, CLB Guitar, CLB Dân vũ, CLB Karate, CLB Tiếng Anh, CLB Taekwondo,...) được Ban Chủ nhiệm ghi nhận và đề xuất.',
                ],
            ],
        ];
    }

    /**
     * Quy trình 4 bước giới thiệu Đoàn viên ưu tú cho Đảng
     */
    private function getEliteMemberProcess(): array
    {
        return [
            'title' => '2. Quy Trình 4 Bước Giới Thiệu Đoàn Viên Ưu Tú Cho Đảng',
            'steps' => [
                [
                    'step' => 1,
                    'title' => 'Suy tôn tại Chi đoàn',
                    'description' => 'Chi đoàn tổ chức họp bình xét, suy tôn từ lực lượng Đoàn viên đạt mức xếp loại "Hoàn thành xuất sắc nhiệm vụ" trong năm học.',
                ],
                [
                    'step' => 2,
                    'title' => 'Bồi dưỡng & Rèn luyện',
                    'description' => 'Ban Chấp hành Chi đoàn phân công Đảng viên và Cán bộ Đoàn trực tiếp bồi dưỡng, giao việc thử thách và hướng dẫn rèn luyện nâng cao phẩm chất chính trị.',
                ],
                [
                    'step' => 3,
                    'title' => 'Giới thiệu kết nạp Đảng',
                    'description' => 'Chi đoàn hoàn thiện hồ sơ báo cáo Đoàn cấp trên và Chi bộ xét cử đi học Lớp bồi dưỡng nhận thức về Đảng (Lớp Cảm tình Đảng) và làm thủ tục kết nạp Đảng viên mới.',
                ],
                [
                    'step' => 4,
                    'title' => 'Theo dõi & Giúp đỡ Đảng viên dự bị',
                    'description' => 'Tổ chức Đoàn tiếp tục đồng hành, theo dõi, giúp đỡ trong suốt 12 tháng thử thách khi trở thành Đảng viên dự bị cho đến khi chuyển Đảng chính thức.',
                ],
            ],
        ];
    }

    /**
     * Dữ liệu Điều kiện kết nạp Đảng / Chuyển Đảng chính thức
     */
    private function getPartyAdmissionData(): array
    {
        return [
            'title' => 'Điều Kiện Kết Nạp Đảng & Chuyển Đảng Chính Thức',
            'legal_basis' => 'Thông tư số 26/2022/TT-BCA của Bộ Công an và Quy định về phát triển Đảng viên của Đảng ủy Học viện Cảnh sát nhân dân.',
            'academic_and_training' => [
                'title' => '1. Điều Kiện Về Kết Quả Học Tập & Rèn Luyện',
                'items' => [
                    [
                        'criteria' => 'Năm học & Học kỳ liền kề',
                        'requirement' => 'Học tập đạt loại Khá trở lên; Điểm rèn luyện đạt loại Tốt trở lên (theo Thông tư 26/2022/TT-BCA).',
                    ],
                    [
                        'criteria' => 'Các tháng tiếp theo (đến thời điểm kết nạp)',
                        'requirement' => 'Điểm rèn luyện hằng tháng tiếp tục duy trì đạt loại Tốt trở lên liên tục.',
                    ],
                    [
                        'criteria' => 'Danh hiệu thi đua bắt buộc',
                        'requirement' => 'Được công nhận danh hiệu "Đoàn viên ưu tú" (đối với nam/nữ đoàn viên thanh niên) hoặc "Hội viên xuất sắc" (đối với hội viên Hội Phụ nữ).',
                    ],
                ],
            ],
            'transfer_rules' => [
                'title' => '2. Quy Định Đối Với Cảm Tình Đảng Chuyển Đến',
                'summary' => 'Học viên đã được công nhận là Cảm tình Đảng từ công an các đơn vị, địa phương hoặc cơ sở đào tạo khác chuyển đến Học viện CSND:',
                'rule' => 'Chỉ được xem xét kết nạp Đảng sau khi đã có kết quả học tập và rèn luyện của ít nhất 01 năm học liền kề tại Học viện Cảnh sát nhân dân đạt yêu cầu theo quy định.',
            ],
        ];
    }

    /**
     * API chuyên biệt: Tiêu chuẩn các Giải thưởng Sổ tay Đoàn viên
     * (Sinh viên 5 Tốt 3 cấp, Sao Tháng Giêng, Tuyên dương Thủ khoa, Vừ A Dính, Lý Tự Trọng)
     * GET /api/giai-thuong-so-tay hoặc GET /api/sinh-vien-5-tot-cac-cap
     */
    public function awards(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu các giải thưởng sổ tay thành công',
            'data' => $this->getAwardsData(),
        ], 200);
    }

    /**
     * Dữ liệu các giải thưởng dành cho đoàn viên, sinh viên Học viện CSND
     */
    private function getAwardsData(): array
    {
        $levelsComparison = [
            'title' => 'Tiêu Chuẩn Giải Thưởng "Sinh Viên 5 Tốt" Các Cấp (Học Viện, Bộ Công An & Trung Ương)',
            'columns' => [
                ['key' => 'standard', 'label' => 'Tiêu chuẩn'],
                ['key' => 'academy', 'label' => 'Cấp Học viện'],
                ['key' => 'ministry', 'label' => 'Cấp Bộ (Bộ Công an)'],
                ['key' => 'central', 'label' => 'Cấp Trung ương'],
            ],
            'rows' => [
                [
                    'standard' => 'Đạo đức tốt',
                    'academy' => 'Điểm rèn luyện ≥ 8.0. Không vi phạm pháp luật, điều lệnh CAND, nội quy Học viện.',
                    'ministry' => 'Bắt buộc: Điểm rèn luyện ≥ 95/100; Không vi phạm pháp luật/quy chế. Đạt thêm (chọn 1): Gương thanh niên tiêu biểu ≥ cấp tỉnh biểu dương HOẶC Đảng viên xuất sắc năm gần nhất.',
                    'central' => 'Tương tự cấp Bộ về tiêu chí bắt buộc. Yêu cầu mức độ khen thưởng/biểu dương ở cấp độ cao hơn hoặc danh hiệu cấp Trung ương.',
                ],
                [
                    'standard' => 'Học tập tốt',
                    'academy' => 'ĐTB cả năm ≥ 8.0/10. Có tham gia NCKH (viết đề tài, chuyên đề, bài đăng hội thảo...).',
                    'ministry' => 'Bắt buộc: ĐTB cả năm ≥ 3.4/4 (tín chỉ) hoặc ≥ 8.5/10 (niên chế). Đạt thêm (chọn 1): Đề tài NCKH đạt giải ≥ cấp tỉnh; Tác giả bài báo WoS/Scopus (Q1–Q4); Sản phẩm sáng tạo/bằng sáng chế; Giải Ba trở lên cuộc thi học thuật/KHKT/khởi nghiệp cấp quốc gia/quốc tế.',
                    'central' => 'Bắt buộc: ĐTB cả năm ≥ 3.4/4 hoặc ≥ 8.5/10. Đạt thêm: Yêu cầu giải thưởng NCKH/học thuật cấp quốc gia/quốc tế hoặc bài báo ISI/Scopus uy tín cao.',
                ],
                [
                    'standard' => 'Thể lực tốt',
                    'academy' => 'Tham gia các phong trào, hoạt động thể thao do Bộ Công an, Học viện hoặc Đoàn Học viện tổ chức.',
                    'ministry' => 'Bắt buộc: Tham gia & đạt giải thể thao ≥ cấp trường HOẶC tham gia cấp Trung ương. Đạt thêm: Đạt giải Ba trở lên cấp tỉnh.',
                    'central' => 'Bắt buộc: Tham gia & đạt giải thể thao cấp Trung ương/toàn quốc hoặc thành viên đội tuyển đại diện tham gia cấp quốc tế.',
                ],
                [
                    'standard' => 'Tình nguyện tốt',
                    'academy' => 'Tham gia ≥ 03 ngày tình nguyện/năm (tính cộng dồn/quy đổi). Ưu tiên: Thành viên tích cực (>1 năm) CLB/đội tình nguyện hoặc được khen thưởng.',
                    'ministry' => 'Bắt buộc: Tham gia ≥ 05 ngày tình nguyện/năm (tính cộng dồn). Đạt thêm (chọn 1): Sáng lập/đồng sáng lập dự án tình nguyện hiệu quả; Bằng khen ≥ cấp tỉnh về tình nguyện.',
                    'central' => 'Bắt buộc: Tham gia ≥ 05 ngày tình nguyện/năm. Đạt thêm: Được khen thưởng cấp Trung ương/Bằng khen Trung ương Đoàn về hoạt động tình nguyện.',
                ],
                [
                    'standard' => 'Hội nhập tốt',
                    'academy' => 'Đạt ít nhất 01 tiêu chí: Ngoại ngữ chứng chỉ ≥ A1 hoặc ĐTB môn ngoại ngữ ≥ 7.5; Tham gia ≥ 01 hoạt động giao lưu quốc tế; Tham gia cuộc thi kiến thức/ngoại ngữ ≥ cấp trường.',
                    'ministry' => 'Bắt buộc: Ngoại ngữ B2 (hoặc tương đương) / điểm học phần ≥ 3.4/4 hay ≥ 8.5/10; Giao lưu quốc tế tham gia ≥ 01 hoạt động/hội thảo quốc tế. Đạt thêm: BCN CLB Ngoại ngữ, giải Ba cuộc thi ngoại ngữ ≥ cấp tỉnh, hoặc có chứng chỉ ≥ B1 ngoại ngữ thứ 2.',
                    'central' => 'Bắt buộc: Sử dụng ngoại ngữ thành thạo (chứng chỉ B2/IELTS/TOEIC tương đương); Tham gia chính thức các chương trình/diễn đàn giao lưu thanh niên quốc tế cấp Trung ương. Đạt thêm: Đạt giải cấp quốc gia/quốc tế bằng ngoại ngữ.',
                ],
            ],
            'special_mechanism' => 'Cơ chế vận dụng / Miễn xét: Cá nhân chưa đủ tiêu chuẩn nhưng có 01 Giấy khen của Đoàn Học viện CSND về thành tích xuất sắc công tác Đoàn/phong trào có thể được xem xét.',
        ];

        $saoThangGieng = [
            'id' => 2,
            'slug' => 'sao-thang-gieng',
            'name' => 'Giải thưởng Sao Tháng Giêng',
            'ten' => 'Giải thưởng Sao Tháng Giêng',
            'title' => 'Giải Thưởng "Sao Tháng Giêng"',
            'short_title' => 'Sao Tháng Giêng',
            'badge' => 'TW Hội Sinh viên Việt Nam',
            'awarding_body' => 'Ban Chấp hành Trung ương Hội Sinh viên Việt Nam',
            'description' => 'Giải thưởng cao quý của Ban Chấp hành Trung ương Hội Sinh viên Việt Nam dành cho cán bộ Đoàn - Hội xuất sắc có thành tích học tập và rèn luyện nổi bật.',
            'mo_ta' => 'Giải thưởng cao quý của Ban Chấp hành Trung ương Hội Sinh viên Việt Nam dành cho cán bộ Đoàn - Hội xuất sắc.',
            'targets' => [
                [
                    'target' => 'Sinh viên trong nước',
                    'training_standard' => 'Điểm rèn luyện ≥ 90/100 (hoặc loại Xuất sắc); Làm cán bộ Đoàn - Hội (≥ Bí thư Chi đoàn/Chi hội trưởng) từ 02 năm trở lên; Có khen thưởng ≥ cấp trường về công tác Đoàn - Hội.',
                    'academic_standard' => 'ĐTB cả năm ≥ 3.2/4 (tín chỉ) hoặc ≥ 8.0/10 (niên chế). Riêng DTTS, miền núi, biên giới, hải đảo: ≥ 3.0/4 hoặc ≥ 7.5/10.',
                ],
                [
                    'target' => 'Sinh viên ngoài nước',
                    'training_standard' => 'Chấp hành tốt pháp luật Việt Nam, nước sở tại và nội quy trường; Tích cực tham gia hoạt động Đoàn, Hội, Đại sứ quán; đóng góp cho cộng đồng người Việt.',
                    'academic_standard' => 'Xếp loại học tập cả năm đạt loại Giỏi (theo thang điểm nước sở tại).',
                ],
            ],
            'standards' => [
                'Là cán bộ Đoàn - Hội (từ Bí thư Chi đoàn/Chi hội trưởng trở lên) giữ chức vụ từ 02 năm trở lên',
                'Điểm rèn luyện cả năm đạt loại Xuất sắc (≥ 90/100 điểm)',
                'Điểm trung bình học tập cả năm đạt từ 3.2/4.0 hoặc 8.0/10 trở lên (học viên DTTS ≥ 3.0/4.0 hoặc ≥ 7.5/10)',
                'Được tặng Giấy khen cấp trường trở lên về thành tích xuất sắc trong công tác Đoàn - Hội',
            ],
            'rewards' => [
                'Bằng khen của Ban Chấp hành Trung ương Hội Sinh viên Việt Nam',
                'Biểu trưng Giải thưởng Sao Tháng Giêng và tiền thưởng theo quy định',
                'Được vinh danh trong Ngày truyền thống Học sinh - Sinh viên Việt Nam (09/01) hằng năm',
            ],
        ];

        $thuKhoa = [
            'id' => 3,
            'slug' => 'thu-khoa-xuat-sac',
            'name' => 'Tuyên dương Thủ khoa xuất sắc tốt nghiệp',
            'ten' => 'Tuyên dương Thủ khoa xuất sắc tốt nghiệp',
            'title' => 'Tuyên Dương Thủ Khoa Xuất Sắc Tốt Nghiệp Các Trường Đại Học, Học Viện',
            'short_title' => 'Thủ khoa xuất sắc',
            'badge' => 'UBND TP Hà Nội & Học viện CSND',
            'awarding_body' => 'Ủy ban nhân dân thành phố Hà Nội & Học viện Cảnh sát nhân dân',
            'co_quan_trao_thuong' => 'UBND TP Hà Nội & Học viện Cảnh sát nhân dân',
            'target_audience' => 'Học viên tốt nghiệp đại học chính quy tại Học viện CSND có điểm học tập và rèn luyện dẫn đầu toàn khóa hoặc ngành đào tạo.',
            'doi_tuong' => 'Học viên tốt nghiệp đứng đầu khóa/ngành học',
            'description' => 'Chương trình vinh danh thường niên những thủ khoa xuất sắc tốt nghiệp các trường đại học, học viện trên địa bàn Thủ đô Hà Nội, tôn vinh trí tuệ, bản lĩnh và tinh thần cống hiến của tuổi trẻ CAND.',
            'mo_ta' => 'Vinh danh những thủ khoa xuất sắc tốt nghiệp có thành tích học tập và rèn luyện dẫn đầu toàn khóa đào tạo.',
            'criteria' => [
                [
                    'name' => 'Tiêu chuẩn học tập',
                    'detail' => 'Điểm trung bình tích lũy toàn khóa đạt loại Xuất sắc (≥ 3.6/4.0 hoặc ≥ 9.0/10) hoặc Giỏi cao nhất ngành; không nợ môn, không thi lại bất kỳ môn nào trong suốt quá trình đào tạo.',
                ],
                [
                    'name' => 'Tiêu chuẩn rèn luyện',
                    'detail' => 'Điểm rèn luyện toàn khóa đạt loại Xuất sắc liên tục; gương mẫu, chấp hành tuyệt đối Điều lệnh CAND, không vi phạm kỷ luật.',
                ],
                [
                    'name' => 'Nghiên cứu khoa học & Phong trào',
                    'detail' => 'Đạt giải thưởng NCKH sinh viên cấp Học viện, cấp Bộ Công an hoặc cấp Quốc gia; tích cực tham gia các phong trào xung kích, tình nguyện của Đoàn trường.',
                ],
            ],
            'standards' => [
                'Điểm trung bình tích lũy toàn khóa đạt loại Xuất sắc (đứng đầu toàn khóa hoặc đứng đầu chuyên ngành đào tạo)',
                'Không phải thi lại hoặc học lại bất kỳ học phần nào trong suốt các năm học',
                'Điểm rèn luyện toàn khóa đạt loại Xuất sắc liên tục qua tất cả các học kỳ',
                'Đạt giải thưởng Nghiên cứu khoa học sinh viên hoặc có đề tài/bài báo khoa học tiêu biểu',
                'Chấp hành nghiêm chỉnh Điều lệnh CAND, lễ tiết tác phong chuẩn mực, không vi phạm kỷ luật',
                'Được tập thể Chi đoàn và Liên chi đoàn tín nhiệm, biểu quyết đề nghị tuyên dương',
            ],
            'rewards' => [
                'Được ghi danh vào Sổ vàng Thủ khoa xuất sắc và tuyên dương trang trọng tại Văn Miếu – Quốc Tử Giám',
                'Bằng khen của Chủ tịch UBND thành phố Hà Nội kèm Cúp/Biểu trưng Thủ khoa xuất sắc',
                'Được Bộ trưởng Bộ Công an xem xét phong cấp bậc hàm / thăng cấp bậc hàm trước niên hạn khi nhận công tác',
                'Ưu tiên tuyển chọn, bố trí công tác tại các đơn vị nghiệp vụ trọng điểm của Bộ Công an',
            ],
        ];

        $vuADinh = [
            'id' => 4,
            'slug' => 'giai-thuong-vu-a-dinh',
            'name' => 'Giải thưởng Vừ A Dính',
            'ten' => 'Giải thưởng Vừ A Dính',
            'title' => 'Giải Thưởng Quỹ Học Bổng Vừ A Dính - Trung Ương Đoàn',
            'short_title' => 'Giải thưởng Vừ A Dính',
            'badge' => 'TW Đoàn & Quỹ Vừ A Dính',
            'awarding_body' => 'Quỹ học bổng Vừ A Dính – Ban Bí thư Trung ương Đoàn TNCS Hồ Chí Minh',
            'co_quan_trao_thuong' => 'Quỹ học bổng Vừ A Dính & Trung ương Đoàn TNCS Hồ Chí Minh',
            'target_audience' => 'Cán bộ, đoàn viên, học viên là đồng bào dân tộc thiểu số, cán bộ chiến sĩ trẻ có nhiều cống hiến cho vùng cao, biên cương Tổ quốc.',
            'doi_tuong' => 'Đoàn viên, học viên dân tộc thiểu số hoặc vùng biên giới, biển đảo tiêu biểu',
            'description' => 'Giải thưởng cao quý trao tặng các cá nhân, tập thể dân tộc thiểu số hoặc vùng biên cương, hải đảo có tinh thần quật khởi, vượt khó vươn lên đạt thành tích đặc biệt xuất sắc trong học tập, rèn luyện và bảo vệ Tổ quốc.',
            'mo_ta' => 'Tôn vinh học viên dân tộc thiểu số có thành tích học tập xuất sắc và đóng góp tích cực cho an ninh trật tự vùng biên cương Tổ quốc.',
            'criteria' => [
                [
                    'name' => 'Đối tượng & Phẩm chất',
                    'detail' => 'Là người dân tộc thiểu số hoặc có quá trình gắn bó, cống hiến tiêu biểu tại các địa bàn miền núi, biên giới, hải đảo; kiên định bản lĩnh chính trị CAND.',
                ],
                [
                    'name' => 'Thành tích học tập & NCKH',
                    'detail' => 'Kết quả học tập đạt loại Giỏi trở lên; có đề tài nghiên cứu hoặc sáng kiến ứng dụng hiệu quả giải quyết vấn đề thực tiễn vùng đồng bào DTTS.',
                ],
                [
                    'name' => 'Rèn luyện & Hoạt động xã hội',
                    'detail' => 'Điểm rèn luyện đạt loại Tốt/Xuất sắc; tích cực tham gia các phong trào thanh niên tình nguyện hướng về đồng bào vùng sâu, vùng xa, biên giới.',
                ],
            ],
            'standards' => [
                'Là học viên, đoàn viên người dân tộc thiểu số (hoặc có nhiều cống hiến nổi bật tại địa bàn miền núi, biên giới, hải đảo)',
                'Điểm trung bình học tập cả năm đạt loại Giỏi trở lên, không thi lại môn học nào',
                'Điểm rèn luyện đạt loại Tốt hoặc Xuất sắc liên tục',
                'Có sáng kiến, đề tài NCKH hoặc mô hình ứng dụng thiết thực phục vụ công tác đảm bảo ANTT, phát triển văn hóa - xã hội vùng DTTS',
                'Gương mẫu trong nếp sống, rèn luyện đạo đức người chiến sĩ CAND, có ý chí vượt khó vươn lên',
                'Tích cực tham gia các chiến dịch tình nguyện Mùa hè xanh, Tiếp sức mùa thi hoặc các hoạt động đền ơn đáp nghĩa',
            ],
            'rewards' => [
                'Bằng khen của Ban Bí thư Trung ương Đoàn TNCS Hồ Chí Minh',
                'Huy hiệu và Giấy chứng nhận Giải thưởng Vừ A Dính kèm phần thưởng tiền mặt/học bổng từ Quỹ',
                'Được vinh danh trong Lễ trao giải thưởng Vừ A Dính toàn quốc và đưa vào nguồn cán bộ trẻ triển vọng',
            ],
        ];

        $lyTuTrong = [
            'id' => 5,
            'slug' => 'giai-thuong-ly-tu-trong',
            'name' => 'Giải thưởng Lý Tự Trọng',
            'ten' => 'Giải thưởng Lý Tự Trọng',
            'title' => 'Giải Thưởng Lý Tự Trọng - Trung Ương Đoàn TNCS Hồ Chí Minh',
            'short_title' => 'Giải thưởng Lý Tự Trọng',
            'badge' => 'Phần thưởng cao quý nhất cho Cán bộ Đoàn',
            'awarding_body' => 'Ban Bí thư Trung ương Đoàn TNCS Hồ Chí Minh',
            'co_quan_trao_thuong' => 'Ban Chấp hành Trung ương Đoàn TNCS Hồ Chí Minh',
            'target_audience' => 'Bí thư Chi đoàn, Phó Bí thư Chi đoàn, Ủy viên BCH Đoàn các cấp tại Học viện CSND (dưới 35 tuổi) có thành tích xuất sắc tiêu biểu toàn quốc.',
            'doi_tuong' => 'Cán bộ Đoàn, Bí thư Chi đoàn tiêu biểu toàn quốc',
            'description' => 'Phần thưởng cao quý nhất của Trung ương Đoàn TNCS Hồ Chí Minh trao tặng cán bộ Đoàn có thành tích xuất sắc trong học tập, lao động, công tác, có nhiều sáng kiến, mô hình mới đóng góp cho phong trào thanh niên cả nước.',
            'mo_ta' => 'Giải thưởng cao quý nhất của Trung ương Đoàn dành cho cán bộ Đoàn cơ sở tiêu biểu toàn quốc.',
            'criteria' => [
                [
                    'name' => 'Tiêu chuẩn chức vụ & Độ tuổi',
                    'detail' => 'Tuổi đời không quá 35 tuổi; giữ chức vụ cán bộ Đoàn (từ Bí thư Chi đoàn trở lên) từ đủ 01 năm trở lên.',
                ],
                [
                    'name' => 'Hiệu quả công tác Đoàn',
                    'detail' => 'Tập thể tổ chức Đoàn trực tiếp phụ trách đạt danh hiệu "Hoàn thành xuất sắc nhiệm vụ" liên tục; có ít nhất 01 mô hình, giải pháp công tác Đoàn được cấp trên công nhận, nhân rộng.',
                ],
                [
                    'name' => 'Học tập & Rèn luyện CAND',
                    'detail' => 'Điểm rèn luyện đạt loại Xuất sắc; kết quả học tập/công tác chuyên môn đạt loại Khá/Giỏi trở lên; chấp hành nghiêm Điều lệnh CAND.',
                ],
            ],
            'standards' => [
                'Độ tuổi không quá 35 tuổi tính đến thời điểm xét trao giải',
                'Có thời gian giữ chức vụ cán bộ Đoàn (Bí thư Chi đoàn, UV BCH Liên chi đoàn, Đoàn trường) từ đủ 01 năm trở lên',
                'Tập thể Chi đoàn do đồng chí phụ trách đạt xếp loại "Hoàn thành xuất sắc nhiệm vụ" trong năm xét trao',
                'Chủ trì hoặc trực tiếp xây dựng ít nhất 01 công trình thanh niên, mô hình đổi mới sáng tạo trong công tác Đoàn được công nhận, nhân rộng',
                'Điểm rèn luyện hằng năm đạt loại Xuất sắc; điểm học tập đạt loại Khá trở lên (ưu tiên loại Giỏi/Xuất sắc)',
                'Đã được tặng Bằng khen của Ban Thường vụ Tỉnh/Thành đoàn, Đoàn Bộ Công an hoặc Giấy khen của Giám đốc Học viện về công tác Đoàn',
            ],
            'rewards' => [
                'Bằng khen của Ban Chấp hành Trung ương Đoàn TNCS Hồ Chí Minh',
                'Kỷ niệm chương Lý Tự Trọng và biểu trưng giải thưởng cao quý',
                'Tiền thưởng theo quy định khen thưởng của Trung ương Đoàn',
                'Được tuyên dương trọng thể trong Lễ trao giải thưởng Lý Tự Trọng toàn quốc nhân dịp kỷ niệm Ngày thành lập Đoàn (26/3)',
                'Được ưu tiên bình xét các danh hiệu thi đua cao cấp và quy hoạch cán bộ chỉ huy trong lực lượng CAND',
            ],
        ];

        // Danh sách 5 giải thưởng tóm gọn để Frontend dễ dàng hiển thị thẻ / danh sách / tabs
        $awardsList = [
            [
                'id' => 1,
                'slug' => 'sinh-vien-5-tot',
                'name' => 'Giải thưởng Sinh viên 5 Tốt',
                'ten' => 'Giải thưởng Sinh viên 5 Tốt',
                'short_title' => 'Sinh viên 5 Tốt',
                'badge' => 'Cấp Học viện - Cấp Bộ - Cấp TW',
                'awarding_body' => 'Đoàn Học viện CSND, Đoàn Bộ Công an & TW Hội Sinh viên Việt Nam',
                'target' => 'Học viên hệ chính quy đào tạo tại Học viện CSND',
                'description' => 'Danh hiệu toàn diện ghi nhận sự phấn đấu trên 5 tiêu chí: Đạo đức tốt, Học tập tốt, Thể lực tốt, Tình nguyện tốt, Hội nhập tốt.',
                'standards_summary' => [
                    'Đạo đức tốt: Điểm rèn luyện ≥ 80 (cấp trường), ≥ 95 (cấp Bộ/TW)',
                    'Học tập tốt: ĐTB ≥ 8.0/10 hoặc ≥ 3.4/4.0; có tham gia NCKH hoặc đạt giải học thuật',
                    'Thể lực tốt: Đạt chuẩn thể lực CAND, tham gia hội thao/giải thể thao các cấp',
                    'Tình nguyện tốt: Tham gia ≥ 3 - 5 ngày tình nguyện/năm, ưu tiên có khen thưởng',
                    'Hội nhập tốt: Ngoại ngữ tốt (A1/B2/IELTS), tham gia giao lưu quốc tế/cuộc thi',
                ],
                'rewards_summary' => 'Giấy chứng nhận / Bằng khen Sinh viên 5 Tốt các cấp, huy hiệu và tiền thưởng theo quy định.',
            ],
            [
                'id' => 2,
                'slug' => $saoThangGieng['slug'],
                'name' => $saoThangGieng['name'],
                'ten' => $saoThangGieng['ten'],
                'short_title' => $saoThangGieng['short_title'],
                'badge' => $saoThangGieng['badge'],
                'awarding_body' => $saoThangGieng['awarding_body'],
                'target' => 'Cán bộ Đoàn - Hội tiêu biểu (từ Bí thư Chi đoàn trở lên)',
                'description' => $saoThangGieng['description'],
                'standards_summary' => $saoThangGieng['standards'],
                'rewards_summary' => 'Bằng khen TW Hội Sinh viên Việt Nam, biểu trưng và tiền thưởng.',
            ],
            [
                'id' => 3,
                'slug' => $thuKhoa['slug'],
                'name' => $thuKhoa['name'],
                'ten' => $thuKhoa['ten'],
                'short_title' => $thuKhoa['short_title'],
                'badge' => $thuKhoa['badge'],
                'awarding_body' => $thuKhoa['awarding_body'],
                'target' => $thuKhoa['target_audience'],
                'description' => $thuKhoa['description'],
                'standards_summary' => $thuKhoa['standards'],
                'rewards_summary' => 'Vinh danh tại Văn Miếu – Quốc Tử Giám, Bằng khen Chủ tịch UBND TP Hà Nội, thăng cấp bậc hàm trước niên hạn.',
            ],
            [
                'id' => 4,
                'slug' => $vuADinh['slug'],
                'name' => $vuADinh['name'],
                'ten' => $vuADinh['ten'],
                'short_title' => $vuADinh['short_title'],
                'badge' => $vuADinh['badge'],
                'awarding_body' => $vuADinh['awarding_body'],
                'target' => $vuADinh['target_audience'],
                'description' => $vuADinh['description'],
                'standards_summary' => $vuADinh['standards'],
                'rewards_summary' => 'Bằng khen TW Đoàn, biểu trưng và học bổng Giải thưởng Vừ A Dính.',
            ],
            [
                'id' => 5,
                'slug' => $lyTuTrong['slug'],
                'name' => $lyTuTrong['name'],
                'ten' => $lyTuTrong['ten'],
                'short_title' => $lyTuTrong['short_title'],
                'badge' => $lyTuTrong['badge'],
                'awarding_body' => $lyTuTrong['awarding_body'],
                'target' => $lyTuTrong['target_audience'],
                'description' => $lyTuTrong['description'],
                'standards_summary' => $lyTuTrong['standards'],
                'rewards_summary' => 'Bằng khen Ban Bí thư TW Đoàn, Kỷ niệm chương Lý Tự Trọng, vinh danh toàn quốc dịp 26/3.',
            ],
        ];

        return [
            'title' => 'Hệ Thống Giải Thưởng & Danh Hiệu Sổ Tay Đoàn Viên PPA',
            'total_awards' => count($awardsList),
            'levels_comparison' => $levelsComparison,
            'sao_thang_gieng' => $saoThangGieng,
            'thu_khoa' => $thuKhoa,
            'vu_a_dinh' => $vuADinh,
            'ly_tu_trong' => $lyTuTrong,
            'awards_list' => $awardsList,
            'danh_sach_giai_thuong' => $awardsList,
        ];
    }
}
