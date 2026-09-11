<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutstandingPerson;
use Illuminate\Http\JsonResponse;

class Student5GoodApiController extends Controller
{
    /**
     * API dữ liệu tổng hợp toàn bộ màn hình Hành trình phấn đấu (Figma)
     * GET /api/student-5-good hoặc GET /api/hanh-trinh-phan-dau
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu Hành trình phấn đấu thành công',
            'data' => [
                'title' => 'Hành Trình Phấn Đấu Của Đoàn Viên PPA',
                'subtitle' => 'Chặng đường rèn luyện và trưởng thành của người chiến sĩ Cảnh sát nhân dân tương lai – Từ Đoàn viên tiêu biểu đến Đảng viên Đảng Cộng sản Việt Nam.',
                'student_5_good' => $this->getStudent5GoodData(),
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
     * API chuyên biệt: Tiêu chuẩn & Quy trình xét Đoàn viên ưu tú (HD 02)
     * GET /api/doan-vien-uu-tu
     */
    public function eliteMember(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu Đoàn viên ưu tú thành công',
            'data' => $this->getEliteMemberData(),
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
     * Dữ liệu Tiêu chuẩn & Quy trình Đoàn viên ưu tú (HD 02-HD/ĐTN-T02)
     */
    private function getEliteMemberData(): array
    {
        return [
            'title' => 'Công Tác Xét Chọn & Giới Thiệu Đoàn Viên Ưu Tú',
            'legal_basis' => 'Hướng dẫn số 02-HD/ĐTN-T02 ngày 20/02/2025 của Ban Chấp hành Đoàn Học viện Cảnh sát nhân dân về Hướng dẫn công tác, đánh giá, công nhận và giới thiệu đoàn viên ưu tú cho Đảng.',
            'standards' => [
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
            ],
            'process' => [
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
}
