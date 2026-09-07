<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Banner;
use App\Models\Club;
use App\Models\ClubCategory;
use App\Models\OutstandingPerson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    /**
     * Nạp dữ liệu mẫu chuẩn cho Web Đoàn Thanh Niên Học Viện CSND
     */
    public function run(): void
    {
        // 0. Tạo tài khoản mẫu (Users & Roles)
        $users = [
            [
                'name' => 'Quản Trị Viên Tối Cao',
                'email' => 'admin@youthunion.edu.vn',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '0901234567',
                'is_active' => true,
            ],
            [
                'name' => 'Bí Thư Đoàn Học Viện',
                'email' => 'bithu@youthunion.edu.vn',
                'password' => Hash::make('123456'),
                'role' => 'editor',
                'phone' => '0912345678',
                'is_active' => true,
            ],
            [
                'name' => 'Đoàn Viên Sinh Viên',
                'email' => 'sinhvien@youthunion.edu.vn',
                'password' => Hash::make('123456'),
                'role' => 'student',
                'phone' => '0923456789',
                'is_active' => true,
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }

        // 1. Tạo 5 Thể loại CLB chuẩn hóa
        $catAcademic = ClubCategory::updateOrCreate(
            ['name' => 'Học thuật'],
            ['description' => 'Các CLB học tập, nghiên cứu khoa học, văn hóa đọc và kỹ năng chuyên ngành']
        );
        $catArts = ClubCategory::updateOrCreate(
            ['name' => 'Văn hóa – Nghệ thuật'],
            ['description' => 'Các CLB âm nhạc, đàn guitar, nhảy hiện đại, dân vũ và kịch nghệ']
        );
        $catSports = ClubCategory::updateOrCreate(
            ['name' => 'Thể thao – Võ thuật'],
            ['description' => 'Các CLB rèn luyện thể lực, thi đấu thể thao và các bộ môn võ thuật truyền thống & quốc tế']
        );
        $catMedia = ClubCategory::updateOrCreate(
            ['name' => 'Truyền thông – Tuyên truyền'],
            ['description' => 'Các CLB báo chí, phát thanh, truyền hình đa phương tiện, nhiếp ảnh và tổ chức sự kiện']
        );
        $catVolunteer = ClubCategory::updateOrCreate(
            ['name' => 'Tình nguyện – Kỹ năng'],
            ['description' => 'Các đội nhóm tình nguyện, công tác xã hội, hiến máu nhân đạo và kỹ năng sống']
        );

        // 2. Nạp dữ liệu Danh sách Câu Lạc Bộ thực tế của Học viện CSND
        $clubs = [
            [
                'name' => 'CLB Nội San – Truyền Thanh',
                'logo' => 'https://images.unsplash.com/photo-1588681664899-f142ff2dc9b1?w=200&q=80',
                'founded_date' => '2006-03-18',
                'category_id' => $catMedia->id,
                'description' => 'Sân chơi học thuật và nghiệp vụ dành cho các đoàn viên có niềm đam mê với công tác truyền thông, phóng sự, nhiếp ảnh, biên tập báo chí và kỹ thuật phát thanh tuyên truyền (Chủ nhiệm: Đ/c Huy Trần).',
            ],
            [
                'name' => 'CLB Truyền Hình PPA TV',
                'logo' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=200&q=80',
                'founded_date' => '2017-09-25',
                'category_id' => $catMedia->id,
                'description' => 'Kênh truyền thông đa phương tiện trực thuộc Đoàn trường, quy tụ các bạn trẻ đam mê báo chí, sản xuất video, dẫn chương trình (MC) và tổ chức các sự kiện lớn của Học viện (Chủ nhiệm: Đ/c Tuấn Phong).',
            ],
            [
                'name' => 'CLB Dân Vũ Học Viện CSND',
                'logo' => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=200&q=80',
                'founded_date' => '2012-09-30',
                'category_id' => $catArts->id,
                'description' => 'Môi trường sinh hoạt nghệ thuật năng động dành cho các bạn trẻ yêu thích các vũ điệu dân vũ, nhảy hiện đại và flashmob, góp phần lan tỏa năng lượng tích cực và nhiệt huyết tuổi trẻ (Chủ nhiệm: Đ/c Hà Nghĩa).',
            ],
            [
                'name' => 'CLB Sách Và Hành Động PPA',
                'logo' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=200&q=80',
                'founded_date' => '2021-05-30',
                'category_id' => $catAcademic->id,
                'description' => 'Không gian học thuật kết nối niềm đam mê đọc sách, phát triển kỹ năng viết lách, tư duy phản biện và lan tỏa văn hóa đọc sâu rộng trong toàn thể đoàn viên, sinh viên (Chủ nhiệm: Đ/c Ma Nguyệt Hà).',
            ],
            [
                'name' => 'CLB Guitar Học Viện CSND (PGC)',
                'logo' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=200&q=80',
                'founded_date' => '2010-04-22',
                'category_id' => $catArts->id,
                'description' => 'Ngôi nhà chung của những trái tim yêu âm nhạc và nhạc cụ mộc (Acoustic), nơi giao lưu tài năng âm nhạc và biểu diễn sân khấu trong các chương trình văn nghệ của Học viện (Chủ nhiệm: Đ/c Minh).',
            ],
            [
                'name' => 'CLB Karate PPA',
                'logo' => 'https://images.unsplash.com/photo-1555597673-b21d5c935865?w=200&q=80',
                'founded_date' => '2015-10-01',
                'category_id' => $catSports->id,
                'description' => 'Môi trường rèn luyện thể lực, kỷ luật và kỹ năng thực chiến dành cho các bạn đam mê bộ môn Karate, nâng cao bản lĩnh tự vệ và rèn luyện thể chất dẻo dai.',
            ],
            [
                'name' => 'CLB Taekwondo PPA',
                'logo' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=200&q=80',
                'founded_date' => '2016-10-15',
                'category_id' => $catSports->id,
                'description' => 'Nơi hội tụ các võ sinh đam mê nghệ thuật đòn chân và tinh thần thượng võ của Taekwondo, giúp tăng cường thể lực, ý chí kiên định và phong thái tự tin cho học viên.',
            ],
        ];

        foreach ($clubs as $c) {
            Club::updateOrCreate(['name' => $c['name']], $c);
        }

        // 3. Nạp dữ liệu Banners (Slider ảnh bìa)
        $banners = [
            [
                'title' => 'Tuổi trẻ Học viện: Bản lĩnh • Kỷ cương • Trách nhiệm • Sáng tạo',
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200&q=80',
                'link_url' => '/hoat-dong',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Chiến Dịch Tình Nguyện Mùa Hè Xanh 2026',
                'image_url' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=1200&q=80',
                'link_url' => '/hoat-dong',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Ngày Hội Câu Lạc Bộ Sinh Viên Đoàn Trường',
                'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1200&q=80',
                'link_url' => '/',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $b) {
            Banner::updateOrCreate(['title' => $b['title']], $b);
        }

        // 4. Nạp dữ liệu Hoạt động / Tin tức
        $activities = [
            [
                'title' => 'Lễ Ra Quân Chiến Dịch Mùa Hè Xanh Năm 2026',
                'thumbnail' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=400&q=80',
                'content' => 'Sáng ngày 15/07, Ban Thường vụ Đoàn trường đã long trọng tổ chức Lễ ra quân Chiến dịch tình nguyện Mùa Hè Xanh với sự tham gia của hơn 500 chiến sĩ tình nguyện tại các xã vùng sâu vùng xa.',
                'is_active' => true,
            ],
            [
                'title' => 'Hội Thi "Tiếng Hát Sinh Viên" Chào Mừng Ngày 26/3',
                'thumbnail' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=400&q=80',
                'content' => 'Hội thi văn nghệ truyền thống quy tụ 30 tiết mục đặc sắc đến từ các Liên chi đoàn và Câu lạc bộ, mang đậm dấu ấn tuổi trẻ nhiệt huyết và tình yêu quê hương đất nước.',
                'is_active' => true,
            ],
            [
                'title' => 'Ngày Hội Hiến Máu Tình Nguyện "Giọt Hồng Tri Ân"',
                'thumbnail' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=400&q=80',
                'content' => 'Chương trình đã thu về hơn 350 đơn vị máu quý giá, thể hiện tinh thần tương thân tương ái cao đẹp của đoàn viên, thanh niên nhà trường.',
                'is_active' => true,
            ],
            [
                'title' => 'Tập Huấn Kỹ Năng Cán Bộ Đoàn - Hội Năm Học Mới',
                'thumbnail' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&q=80',
                'content' => 'Chương trình trang bị kỹ năng quản lý tổ chức, kỹ năng truyền thông mạng xã hội và kỹ năng tổ chức sự kiện cho các Bí thư chi đoàn và Ban chủ nhiệm CLB.',
                'is_active' => true,
            ],
        ];

        foreach ($activities as $act) {
            Activity::updateOrCreate(['title' => $act['title']], $act);
        }

        // 5. Nạp dữ liệu Gương mặt tiêu biểu
        $people = [
            [
                'name' => 'ThS. Nguyễn Văn Hùng',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&q=80',
                'role_group' => 'BGD',
                'class_unit' => 'Ban Giám Đốc / Đảng Ủy',
                'achievement' => 'Phụ trách công tác thanh niên và hỗ trợ sinh viên khởi nghiệp đổi mới sáng tạo.',
                'is_active' => true,
            ],
            [
                'name' => 'Nguyễn Văn Nam',
                'avatar' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=200&q=80',
                'role_group' => 'BI_THU_DOAN',
                'class_unit' => 'Chi đoàn K15 CNTT 1',
                'achievement' => 'Đạt danh hiệu Cán bộ Đoàn tiêu biểu cấp Trường, Điểm rèn luyện Xuất sắc 3 năm liên tiếp.',
                'is_active' => true,
            ],
            [
                'name' => 'Trần Thị Thu Trang',
                'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=200&q=80',
                'role_group' => 'DOAN_VIEN',
                'class_unit' => 'Chi đoàn K16 Kinh tế',
                'achievement' => 'Đạt danh hiệu "Sinh viên 5 tốt" cấp Thành phố, Giải Nhì cuộc thi Ý tưởng Khởi nghiệp sáng tạo.',
                'is_active' => true,
            ],
            [
                'name' => 'Lê Hoàng Long',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80',
                'role_group' => 'DOAN_VIEN',
                'class_unit' => 'Chi đoàn K14 Tự động hóa',
                'achievement' => 'Giải Nhất Hội nghị NCKH Sinh viên toàn trường, có 2 bài báo quốc tế thuộc danh mục Scopus.',
                'is_active' => true,
            ],
        ];

        foreach ($people as $p) {
            OutstandingPerson::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
