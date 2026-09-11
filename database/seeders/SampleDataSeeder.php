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
                'logo' => '/images/clubs/noi-san-truyen-thanh/noi-san-truyen-thanh-1.jpg',
                'images' => [
                    '/images/clubs/noi-san-truyen-thanh/noi-san-truyen-thanh-1.jpg',
                    '/images/clubs/noi-san-truyen-thanh/noi-san-truyen-thanh-2.jpg',
                    '/images/clubs/noi-san-truyen-thanh/noi-san-truyen-thanh-3.jpg',
                ],
                'founded_date' => '2006-03-18',
                'category_id' => $catMedia->id,
                'description' => 'Sân chơi học thuật và nghiệp vụ dành cho các đoàn viên có niềm đam mê với công tác truyền thông, phóng sự, nhiếp ảnh, biên tập báo chí và kỹ thuật phát thanh tuyên truyền (Chủ nhiệm: Đ/c Huy Trần).',
            ],
            [
                'name' => 'CLB Truyền Hình PPA TV',
                'logo' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=200&q=80',
                'images' => [],
                'founded_date' => '2017-09-25',
                'category_id' => $catMedia->id,
                'description' => 'Kênh truyền thông đa phương tiện trực thuộc Đoàn trường, quy tụ các bạn trẻ đam mê báo chí, sản xuất video, dẫn chương trình (MC) và tổ chức các sự kiện lớn của Học viện (Chủ nhiệm: Đ/c Tuấn Phong).',
            ],
            [
                'name' => 'CLB Dân Vũ Học Viện CSND',
                'logo' => '/images/clubs/dan-vu/dan-vu-1.jpg',
                'images' => [
                    '/images/clubs/dan-vu/dan-vu-1.jpg',
                    '/images/clubs/dan-vu/dan-vu-2.jpg',
                    '/images/clubs/dan-vu/dan-vu-3.jpg',
                    '/images/clubs/dan-vu/dan-vu-4.jpg',
                    '/images/clubs/dan-vu/dan-vu-5.jpg',
                    '/images/clubs/dan-vu/dan-vu-6.jpg',
                    '/images/clubs/dan-vu/dan-vu-7.jpg',
                    '/images/clubs/dan-vu/dan-vu-8.jpg',
                ],
                'founded_date' => '2012-09-30',
                'category_id' => $catArts->id,
                'description' => 'Môi trường sinh hoạt nghệ thuật năng động dành cho các bạn trẻ yêu thích các vũ điệu dân vũ, nhảy hiện đại và flashmob, góp phần lan tỏa năng lượng tích cực và nhiệt huyết tuổi trẻ (Chủ nhiệm: Đ/c Hà Nghĩa).',
            ],
            [
                'name' => 'CLB Sách Và Hành Động PPA',
                'logo' => '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-1.jpg',
                'images' => [
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-1.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-2.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-3.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-4.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-5.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-6.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-7.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-8.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-9.jpg',
                    '/images/clubs/sach-va-hanh-dong/sach-va-hanh-dong-10.jpg',
                ],
                'founded_date' => '2021-05-30',
                'category_id' => $catAcademic->id,
                'description' => 'Không gian học thuật kết nối niềm đam mê đọc sách, phát triển kỹ năng viết lách, tư duy phản biện và lan tỏa văn hóa đọc sâu rộng trong toàn thể đoàn viên, sinh viên (Chủ nhiệm: Đ/c Ma Nguyệt Hà).',
            ],
            [
                'name' => 'CLB Guitar Học Viện CSND (PGC)',
                'logo' => '/images/clubs/guitar/guitar-1.jpg',
                'images' => [
                    '/images/clubs/guitar/guitar-1.jpg',
                    '/images/clubs/guitar/guitar-2.jpg',
                    '/images/clubs/guitar/guitar-3.jpg',
                    '/images/clubs/guitar/guitar-4.jpg',
                    '/images/clubs/guitar/guitar-5.jpg',
                    '/images/clubs/guitar/guitar-6.jpg',
                ],
                'founded_date' => '2010-04-22',
                'category_id' => $catArts->id,
                'description' => 'Ngôi nhà chung của những trái tim yêu âm nhạc và nhạc cụ mộc (Acoustic), nơi giao lưu tài năng âm nhạc và biểu diễn sân khấu trong các chương trình văn nghệ của Học viện (Chủ nhiệm: Đ/c Minh).',
            ],
            [
                'name' => 'CLB Karate PPA',
                'logo' => '/images/clubs/karate/karate-1.jpg',
                'images' => [
                    '/images/clubs/karate/karate-1.jpg',
                    '/images/clubs/karate/karate-2.jpg',
                    '/images/clubs/karate/karate-3.jpg',
                    '/images/clubs/karate/karate-4.jpg',
                    '/images/clubs/karate/karate-5.jpg',
                    '/images/clubs/karate/karate-6.jpg',
                    '/images/clubs/karate/karate-7.jpg',
                    '/images/clubs/karate/karate-8.jpg',
                    '/images/clubs/karate/karate-9.jpg',
                    '/images/clubs/karate/karate-10.jpg',
                    '/images/clubs/karate/karate-11.jpg',
                    '/images/clubs/karate/karate-12.jpg',
                    '/images/clubs/karate/karate-13.jpg',
                    '/images/clubs/karate/karate-14.jpg',
                    '/images/clubs/karate/karate-15.jpg',
                ],
                'founded_date' => '2015-10-01',
                'category_id' => $catSports->id,
                'description' => 'Môi trường rèn luyện thể lực, kỷ luật và kỹ năng thực chiến dành cho các bạn đam mê bộ môn Karate, nâng cao bản lĩnh tự vệ và rèn luyện thể chất dẻo dai.',
            ],
            [
                'name' => 'CLB Taekwondo PPA',
                'logo' => '/images/clubs/taekwondo/taekwondo-1.jpg',
                'images' => [
                    '/images/clubs/taekwondo/taekwondo-1.jpg',
                    '/images/clubs/taekwondo/taekwondo-2.jpg',
                    '/images/clubs/taekwondo/taekwondo-3.jpg',
                    '/images/clubs/taekwondo/taekwondo-4.jpg',
                    '/images/clubs/taekwondo/taekwondo-5.jpg',
                    '/images/clubs/taekwondo/taekwondo-6.jpg',
                    '/images/clubs/taekwondo/taekwondo-7.jpg',
                    '/images/clubs/taekwondo/taekwondo-8.jpg',
                ],
                'founded_date' => '2016-10-15',
                'category_id' => $catSports->id,
                'description' => 'Nơi hội tụ các võ sinh đam mê nghệ thuật đòn chân và tinh thần thượng võ của Taekwondo, giúp tăng cường thể lực, ý chí kiên định và phong thái tự tin cho học viên.',
            ],
        ];

        foreach ($clubs as $c) {
            Club::updateOrCreate(['name' => $c['name']], $c);
        }

        // 3. Nạp dữ liệu Banners (Slider ảnh bìa thực tế từ Đoàn Học viện)
        Banner::truncate();
        $banners = [
            [
                'title' => 'Tuổi trẻ Học viện CSND: Bản lĩnh • Kỷ cương • Trách nhiệm • Sáng tạo',
                'image_url' => '/images/banners/banner-2.jpg',
                'link_url' => '/hoat-dong',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sổ tay Đoàn viên - Học viện Cảnh sát nhân dân',
                'image_url' => '/images/banners/banner-7.jpg',
                'link_url' => '/gioi-thieu',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Đoàn viên thanh niên Học viện vững bước dưới cờ Đảng quang vinh',
                'image_url' => '/images/banners/banner-5.jpg',
                'link_url' => '/hanh-trinh-phan-dau',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Chiến dịch Thanh niên tình nguyện hè - Tuổi trẻ Học viện vì cộng đồng',
                'image_url' => '/images/banners/banner-4.jpg',
                'link_url' => '/hoat-dong',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Công trình thanh niên "Cầu Kha Hạ" - Dự án Ánh sáng núi rừng',
                'image_url' => '/images/banners/banner-1.jpg',
                'link_url' => '/hoat-dong',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Đẩy mạnh phong trào học tập, nghiên cứu khoa học và văn hóa đọc',
                'image_url' => '/images/banners/banner-6.jpg',
                'link_url' => '/cau-lac-bo',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Xung kích, sáng tạo trong công tác Đoàn và phong trào thanh niên Học viện',
                'image_url' => '/images/banners/banner-3.jpg',
                'link_url' => '/thanh-tich',
                'order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $b) {
            Banner::create($b);
        }

        // 4. Nạp dữ liệu Hoạt động / Tin tức (kèm thông số Phong trào chuẩn Figma)
        $activities = [
            [
                'title' => 'Chuyến xe về quê ăn Tết - Tặng quà cho đoàn viên, hội viên Xuân Bính Ngọ 2026',
                'thumbnail' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&q=80',
                'content' => '<p>Chương trình dâng hương, thắp nến tri ân và tặng quà ý nghĩa tại Nghĩa trang Liệt sĩ TP. Hà Nội, thể hiện đạo lý Uống nước nhớ nguồn của tuổi trẻ Học viện Cảnh sát nhân dân.</p>',
                'movement_type' => 'den_on_dap_nghia',
                'activity_type' => 'Thắp nến tri ân',
                'location' => 'Nghĩa trang Liệt sĩ TP. Hà Nội',
                'target_audience' => 'Các anh hùng liệt sĩ',
                'participants' => '300+ đoàn viên',
                'summary_content' => 'Dâng hương, thắp nến, dọn vệ sinh phần mộ',
                'significance' => 'Giáo dục lòng biết ơn, truyền thống "Uống nước nhớ nguồn"',
                'result' => 'Chăm sóc, thắp nến toàn bộ khu nghĩa trang',
                'is_active' => true,
            ],
            [
                'title' => 'Hành trình Tri ân - Tặng quà người có công với cách mạng',
                'thumbnail' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=800&q=80',
                'content' => '<p>Tuổi trẻ Học viện đến thăm hỏi, tặng quà và phụ giúp sửa sang nhà cửa cho các Mẹ Việt Nam Anh Hùng, thương bệnh binh và gia đình chính sách tại các huyện ngoại thành Hà Nội.</p>',
                'movement_type' => 'den_on_dap_nghia',
                'activity_type' => 'Tặng quà người có công',
                'location' => 'Các huyện ngoại thành Hà Nội',
                'target_audience' => 'Mẹ VNAH, thương binh, gia đình chính sách',
                'participants' => '120 đoàn viên',
                'summary_content' => 'Thăm hỏi, tặng quà, sửa sang nhà cửa',
                'significance' => 'Lan tỏa đạo lý tri ân, gắn kết với nhân dân',
                'result' => 'Trao 150 suất quà, hỗ trợ 8 hộ gia đình',
                'is_active' => true,
            ],
            [
                'title' => 'Chiến dịch Mùa hè xanh',
                'thumbnail' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=800&q=80',
                'content' => '<p>Chiến dịch Mùa hè xanh của tuổi trẻ Học viện tại các xã miền núi tỉnh Hòa Bình, chung tay cùng đồng bào xây dựng nông thôn mới và mang lại niềm vui cho các em nhỏ.</p>',
                'movement_type' => 'thanh_nien_xung_kich',
                'activity_type' => 'Chiến dịch tiêu biểu',
                'objective' => 'Hỗ trợ địa phương xây dựng nông thôn mới',
                'location' => 'Xã miền núi tỉnh Hòa Bình',
                'cooperation' => 'Đoàn xã, Đồn Công an địa phương',
                'result' => 'Sửa 2km đường, mở lớp học hè cho 80 em nhỏ',
                'value' => 'Gắn kết quân dân, rèn luyện bản lĩnh đoàn viên',
                'comment' => [
                    'content' => 'Một mùa hè không nghỉ ngơi nhưng đầy ý nghĩa – nơi chúng tôi hiểu hơn về trách nhiệm của người chiến sĩ Công an nhân dân với nhân dân.',
                    'author' => 'Đoàn viên Nguyễn Văn A',
                    'class_unit' => 'D48',
                ],
                'is_active' => true,
            ],
            [
                'title' => 'Ngày hội Hiến máu tình nguyện',
                'thumbnail' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=800&q=80',
                'content' => '<p>Ngày hội Hiến máu tình nguyện thường niên "Giọt máu nghĩa tình vì đồng đội thân yêu" thu hút đông đảo cán bộ, giảng viên và học viên Học viện tham gia sẻ chia giọt máu cứu người.</p>',
                'movement_type' => 'thanh_nien_xung_kich',
                'activity_type' => 'Chiến dịch tiêu biểu',
                'objective' => 'Bổ sung nguồn máu cứu người',
                'location' => 'Học viện CSND',
                'cooperation' => 'Viện Huyết học - Truyền máu TW',
                'result' => 'Tiếp nhận hơn 800 đơn vị máu',
                'value' => 'Lan tỏa tinh thần "Mỗi giọt máu cho đi, một cuộc đời ở lại"',
                'comment' => [
                    'content' => 'Cảm giác được góp một phần nhỏ để cứu giúp người khác khiến tôi thấy màu áo mình đang khoác thật ý nghĩa.',
                    'author' => 'Đoàn viên Nguyễn Văn B',
                    'class_unit' => 'D49',
                ],
                'is_active' => true,
            ],
            [
                'title' => 'Tập Huấn Kỹ Năng Cán Bộ Đoàn - Hội Năm Học Mới',
                'thumbnail' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&q=80',
                'content' => 'Chương trình trang bị kỹ năng quản lý tổ chức, kỹ năng truyền thông mạng xã hội và kỹ năng tổ chức sự kiện cho các Bí thư chi đoàn và Ban chủ nhiệm CLB.',
                'movement_type' => null,
                'activity_type' => 'Tập huấn cán bộ',
                'location' => 'Hội trường Lớn Học viện CSND',
                'is_active' => true,
            ],
        ];

        foreach ($activities as $act) {
            Activity::updateOrCreate(['title' => $act['title']], $act);
        }

        // 5. Nạp dữ liệu Ban Thường Vụ Đoàn Học viện & Gương mặt tiêu biểu
        $people = [
            // --- BAN THƯỜNG VỤ ĐOÀN THANH NIÊN HỌC VIỆN CSND ---
            [
                'name' => 'Nguyễn Văn Duy',
                'position' => 'Bí thư Đoàn Học viện',
                'order' => 1,
                'avatar' => '/images/btv-doan/nguyen-van-duy.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Bí thư Đoàn TNCS Hồ Chí Minh Học viện Cảnh sát nhân dân',
                'is_active' => true,
            ],
            [
                'name' => 'Nguyễn Xuân Vinh',
                'position' => 'Phó Bí thư Đoàn Học viện',
                'order' => 2,
                'avatar' => '/images/btv-doan/nguyen-xuan-vinh.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Phó Bí thư Đoàn TNCS Hồ Chí Minh Học viện Cảnh sát nhân dân',
                'is_active' => true,
            ],
            [
                'name' => 'Trần Văn Phú',
                'position' => 'Phó Bí thư Đoàn Học viện',
                'order' => 3,
                'avatar' => '/images/btv-doan/tran-van-phu.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Phó Bí thư Đoàn TNCS Hồ Chí Minh Học viện Cảnh sát nhân dân',
                'is_active' => true,
            ],
            [
                'name' => 'Nguyễn Thành Nghĩa',
                'position' => 'Ủy viên Thường trực BTV',
                'order' => 4,
                'avatar' => '/images/btv-doan/nguyen-thanh-nghia.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Ủy viên Thường trực Ban Thường vụ Đoàn Học viện CSND',
                'is_active' => true,
            ],
            [
                'name' => 'Nguyễn Xuân Hiếu',
                'position' => 'Ủy viên Thường trực BTV',
                'order' => 5,
                'avatar' => '/images/btv-doan/nguyen-xuan-hieu.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Ủy viên Thường trực Ban Thường vụ Đoàn Học viện CSND',
                'is_active' => true,
            ],

            // --- CÁN BỘ ĐOÀN & SINH VIÊN TIÊU BIỂU ---
            [
                'name' => 'Thượng úy Trần Minh Tuấn',
                'position' => 'Bí thư Liên chi đoàn',
                'order' => 10,
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&q=80',
                'role_group' => 'BI_THU_DOAN',
                'class_unit' => 'Liên chi đoàn Khóa D47',
                'achievement' => 'Đạt danh hiệu Thanh niên tiên tiến làm theo lời Bác cấp Bộ Công an, Giải Nhất Báo cáo viên giỏi cấp Học viện.',
                'is_active' => true,
            ],
            [
                'name' => 'Nguyễn Văn Nam',
                'position' => 'Bí thư Chi đoàn',
                'order' => 11,
                'avatar' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=300&q=80',
                'role_group' => 'BI_THU_DOAN',
                'class_unit' => 'Chi đoàn B11 - Khóa D48',
                'achievement' => 'Cán bộ Đoàn xuất sắc 3 năm liên tiếp, Chủ nhiệm CLB Truyền thông PPA Media, Điểm rèn luyện Xuất sắc.',
                'is_active' => true,
            ],
            [
                'name' => 'Trần Thị Thu Trang',
                'position' => 'Đoàn viên xuất sắc',
                'order' => 12,
                'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=300&q=80',
                'role_group' => 'DOAN_VIEN',
                'class_unit' => 'Chi đoàn B3 - Khóa D48',
                'achievement' => 'Danh hiệu "Sinh viên 5 tốt" cấp Trung ương, Giải Nhì cuộc thi Olympic Tiếng Anh sinh viên toàn quốc.',
                'is_active' => true,
            ],
            [
                'name' => 'Lê Hoàng Long',
                'position' => 'Đoàn viên xuất sắc',
                'order' => 13,
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&q=80',
                'role_group' => 'DOAN_VIEN',
                'class_unit' => 'Chi đoàn B1 - Khóa D47',
                'achievement' => 'Giải Nhất Hội nghị NCKH Học viên Cảnh sát, Tác giả 2 bài báo quốc tế thuộc danh mục Scopus, Học viên Giỏi.',
                'is_active' => true,
            ],
            [
                'name' => 'Phạm Phương Linh',
                'position' => 'Đoàn viên xuất sắc',
                'order' => 14,
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&q=80',
                'role_group' => 'DOAN_VIEN',
                'class_unit' => 'Chi đoàn B5 - Khóa D49',
                'achievement' => 'Huy chương Vàng Giải Bắn súng - Võ thuật ứng dụng CAND 2025, Danh hiệu "Sinh viên 5 tốt" cấp Học viện.',
                'is_active' => true,
            ],
        ];

        foreach ($people as $p) {
            OutstandingPerson::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
