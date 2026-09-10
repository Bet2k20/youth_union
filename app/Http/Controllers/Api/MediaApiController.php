<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Club;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaApiController extends Controller
{
    /**
     * Dữ liệu danh sách hình ảnh phong trào & CLB
     */
    private function getPhotos(): array
    {
        $photos = [
            [
                'id' => 1,
                'title' => 'Lễ Ra Quân Chiến Dịch Tình Nguyện Mùa Hè Xanh 2026',
                'image_url' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=1200&q=80',
                'category' => 'Phong trào tình nguyện',
                'date' => '2026-07-15',
            ],
            [
                'id' => 2,
                'title' => 'Chuyến xe về quê ăn Tết - Thắp nến tri ân tại Nghĩa trang Liệt sĩ TP. Hà Nội',
                'image_url' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=1200&q=80',
                'category' => 'Đền ơn đáp nghĩa',
                'date' => '2026-01-20',
            ],
            [
                'id' => 3,
                'title' => 'Ngày Hội Hiến Máu Tình Nguyện "Giọt Máu Nghĩa Tình Vì Đồng Đội Thân Yêu"',
                'image_url' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=1200&q=80',
                'category' => 'Hoạt động vì cộng đồng',
                'date' => '2026-08-19',
            ],
            [
                'id' => 4,
                'title' => 'Tập luyện võ thuật & kỹ năng thực chiến - CLB Karate PPA',
                'image_url' => url('/images/clubs/karate/karate-1.jpg'),
                'category' => 'Câu lạc bộ & Thể thao',
                'date' => '2026-08-25',
            ],
            [
                'id' => 5,
                'title' => 'Biểu diễn dân vũ flashmob chào mừng năm học mới - CLB Dân Vũ PPA',
                'image_url' => url('/images/clubs/dan-vu/dan-vu-1.jpg'),
                'category' => 'Văn hóa – Nghệ thuật',
                'date' => '2026-09-02',
            ],
            [
                'id' => 6,
                'title' => 'Đêm nhạc Acoustic gây quỹ từ thiện - CLB Guitar PGC',
                'image_url' => url('/images/clubs/guitar/guitar-1.jpg'),
                'category' => 'Văn hóa – Nghệ thuật',
                'date' => '2026-09-05',
            ],
            [
                'id' => 7,
                'title' => 'Ngày hội Văn hóa đọc "Sách - Hành trang người chiến sĩ" - CLB Sách PPA',
                'image_url' => url('/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-1.jpg'),
                'category' => 'Học thuật & Đọc sách',
                'date' => '2026-09-08',
            ],
            [
                'id' => 8,
                'title' => 'Tác nghiệp hiện trường & sản xuất bản tin - CLB Nội San – Truyền Thanh',
                'image_url' => url('/images/clubs/noi-san-truyen-thanh/noi-san-truyen-thanh-1.jpg'),
                'category' => 'Truyền thông & Báo chí',
                'date' => '2026-09-09',
            ],
            [
                'id' => 9,
                'title' => 'Kỳ thi thăng cấp đai Taekwondo chuẩn quốc gia - CLB Taekwondo PPA',
                'image_url' => url('/images/clubs/taekwondo/taekwondo-1.jpg'),
                'category' => 'Câu lạc bộ & Thể thao',
                'date' => '2026-09-10',
            ],
        ];

        return $photos;
    }

    /**
     * Dữ liệu danh sách Thông tư, quy định, văn bản chỉ đạo & biểu mẫu
     */
    private function getDocuments(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Thông tư số 04/2021/TT-BCA quy định về công tác thanh niên trong Công an nhân dân',
                'code_number' => '04/2021/TT-BCA',
                'issuer' => 'Bộ Công an',
                'issued_date' => '2021-01-18',
                'category' => 'Thông tư & Văn bản pháp quy',
                'description' => 'Quy định nguyên tắc, nội dung, biện pháp thực hiện công tác thanh niên và trách nhiệm của Công an các đơn vị, địa phương.',
                'file_type' => 'PDF',
                'file_size' => '2.8 MB',
                'download_url' => url('/uploads/documents/thong_tu_04_2021_tt_bca.pdf'),
            ],
            [
                'id' => 2,
                'title' => 'Quy chế Tổ chức và Hoạt động của Đoàn Thanh niên Học viện CSND',
                'code_number' => 'QC-ĐTN-T02',
                'issuer' => 'Đoàn Thanh niên Học viện CSND',
                'issued_date' => '2023-10-15',
                'category' => 'Quy chế & Quy định',
                'description' => 'Quy chế khung về chức năng, nhiệm vụ, quyền hạn của Ban Chấp hành, các Liên chi đoàn và Chi đoàn cơ sở.',
                'file_type' => 'PDF',
                'file_size' => '1.9 MB',
                'download_url' => url('/uploads/documents/quy_che_doan_t02.pdf'),
            ],
            [
                'id' => 3,
                'title' => 'Hướng dẫn tiêu chuẩn và quy trình xét tặng danh hiệu "Sinh viên 5 Tốt" các cấp',
                'code_number' => '12/HD-ĐTN',
                'issuer' => 'Ban Thường vụ Đoàn trường',
                'issued_date' => '2025-09-20',
                'category' => 'Hướng dẫn rèn luyện',
                'description' => 'Chi tiết 5 tiêu chí: Đạo đức tốt, Học tập tốt, Thể lực tốt, Tình nguyện tốt và Hội nhập tốt dành cho học viên.',
                'file_type' => 'PDF',
                'file_size' => '1.5 MB',
                'download_url' => url('/uploads/documents/huong_dan_sinh_vien_5_tot.pdf'),
            ],
            [
                'id' => 4,
                'title' => 'Mẫu đơn đăng ký rèn luyện tiêu chuẩn "Sinh viên 5 Tốt" và Phiếu tự đánh giá',
                'code_number' => 'BM-01-SV5T',
                'issuer' => 'Đoàn Thanh niên Học viện',
                'issued_date' => '2025-09-22',
                'category' => 'Biểu mẫu sinh viên',
                'description' => 'Bản mẫu word chuẩn để đoàn viên kê khai thành tích rèn luyện, học tập nộp cho Bí thư chi đoàn.',
                'file_type' => 'DOCX',
                'file_size' => '850 KB',
                'download_url' => url('/uploads/documents/mau_dang_ky_sv5t.docx'),
            ],
            [
                'id' => 5,
                'title' => 'Quy định về quản lý, tổ chức và sinh hoạt của các Câu Lạc Bộ trực thuộc Đoàn trường',
                'code_number' => 'QĐ-18/QĐ-ĐTN',
                'issuer' => 'Đoàn Thanh niên Học viện CSND',
                'issued_date' => '2024-03-26',
                'category' => 'Quy chế & Quy định',
                'description' => 'Nguyên tắc thành lập, quy trình phê duyệt kế hoạch hoạt động, khen thưởng và kỷ luật đối với các CLB sinh viên.',
                'file_type' => 'PDF',
                'file_size' => '2.1 MB',
                'download_url' => url('/uploads/documents/quy_dinh_clb_ppa.pdf'),
            ],
            [
                'id' => 6,
                'title' => 'Mẫu đơn xin thành lập / gia nhập Câu Lạc Bộ – Đội – Nhóm sở thích',
                'code_number' => 'BM-03-CLB',
                'issuer' => 'Đoàn Thanh niên Học viện',
                'issued_date' => '2024-03-28',
                'category' => 'Biểu mẫu sinh viên',
                'description' => 'Mẫu đơn chuẩn dành cho học viên đăng ký tham gia các CLB võ thuật, nghệ thuật, học thuật.',
                'file_type' => 'DOCX',
                'file_size' => '620 KB',
                'download_url' => url('/uploads/documents/mau_don_gia_nhap_clb.docx'),
            ],
            [
                'id' => 7,
                'title' => 'Kế hoạch tổ chức Chiến dịch Thanh niên Tình nguyện Hè năm 2026',
                'code_number' => 'KH-45/KH-ĐTN',
                'issuer' => 'Ban Thường vụ Đoàn trường',
                'issued_date' => '2026-05-15',
                'category' => 'Kế hoạch phong trào',
                'description' => 'Kế hoạch phân bổ địa bàn tình nguyện Mùa hè xanh và Tiếp sức mùa thi tại các tỉnh miền núi phía Bắc.',
                'file_type' => 'PDF',
                'file_size' => '3.4 MB',
                'download_url' => url('/uploads/documents/ke_hoach_tinh_nguyen_he_2026.pdf'),
            ],
        ];
    }

    /**
     * [R] Lấy toàn bộ dữ liệu trang Thư Viện (gồm cả Ảnh và Thông tư, quy định)
     * GET /api/media
     * Hỗ trợ lọc: ?type=photos hoặc ?type=documents
     */
    public function index(Request $request): JsonResponse
    {
        $type = $request->input('type');

        if ($type === 'photos') {
            return $this->photos($request);
        }

        if ($type === 'documents') {
            return $this->documents($request);
        }

        $photos = $this->getPhotos();
        $documents = $this->getDocuments();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy dữ liệu trang Thư viện thành công',
            'data' => [
                'photos' => $photos,
                'documents' => $documents,
                'categories' => [
                    'photo_categories' => [
                        'Tất cả',
                        'Phong trào tình nguyện',
                        'Đền ơn đáp nghĩa',
                        'Hoạt động vì cộng đồng',
                        'Câu lạc bộ & Thể thao',
                        'Văn hóa – Nghệ thuật',
                        'Học thuật & Đọc sách',
                    ],
                    'document_categories' => [
                        'Tất cả',
                        'Thông tư & Văn bản pháp quy',
                        'Quy chế & Quy định',
                        'Hướng dẫn rèn luyện',
                        'Kế hoạch phong trào',
                        'Biểu mẫu sinh viên',
                    ],
                ],
            ],
        ], 200);
    }

    /**
     * [R] Lấy danh sách Thư viện Ảnh
     * GET /api/media/photos hoặc GET /api/photos
     */
    public function photos(Request $request): JsonResponse
    {
        $photos = $this->getPhotos();

        // Lọc theo thể loại ảnh nếu có ?category=...
        if ($request->filled('category') && $request->input('category') !== 'Tất cả') {
            $cat = $request->input('category');
            $photos = array_values(array_filter($photos, fn($p) => $p['category'] === $cat));
        }

        // Tìm kiếm theo tiêu đề ảnh nếu có ?search=...
        if ($request->filled('search')) {
            $keyword = mb_strtolower($request->input('search'));
            $photos = array_values(array_filter($photos, fn($p) => str_contains(mb_strtolower($p['title']), $keyword)));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách thư viện ảnh thành công',
            'total' => count($photos),
            'data' => $photos,
        ], 200);
    }

    /**
     * [R] Lấy danh sách Thông tư, Quy định & Biểu mẫu
     * GET /api/media/documents hoặc GET /api/documents
     */
    public function documents(Request $request): JsonResponse
    {
        $documents = $this->getDocuments();

        // Lấy chi tiết 1 văn bản nếu có ?id=...
        if ($request->filled('id')) {
            $id = (int) $request->input('id');
            $doc = collect($documents)->firstWhere('id', $id);
            if (!$doc) {
                return response()->json(['status' => 'error', 'message' => 'Không tìm thấy văn bản này'], 404);
            }
            return response()->json(['status' => 'success', 'data' => $doc], 200);
        }

        // Lọc theo thể loại nếu có ?category=...
        if ($request->filled('category') && $request->input('category') !== 'Tất cả') {
            $cat = $request->input('category');
            $documents = array_values(array_filter($documents, fn($d) => $d['category'] === $cat));
        }

        // Tìm kiếm theo tên văn bản nếu có ?search=...
        if ($request->filled('search')) {
            $keyword = mb_strtolower($request->input('search'));
            $documents = array_values(array_filter($documents, fn($d) => str_contains(mb_strtolower($d['title']), $keyword) || str_contains(mb_strtolower($d['code_number']), $keyword)));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách Thông tư, Quy định & Biểu mẫu thành công',
            'total' => count($documents),
            'data' => $documents,
        ], 200);
    }
}
