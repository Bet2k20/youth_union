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
            User::firstOrCreate(['email' => $u['email']], $u);
        }

        // 1. Tạo các Thể loại CLB chuẩn hóa
        $catMartialArts = ClubCategory::updateOrCreate(
            ['name' => 'Võ thuật'],
            ['description' => 'Các CLB võ thuật truyền thống, võ thuật CAND, Karate, Taekwondo, rèn luyện bản lĩnh chiến đấu và kỹ năng tự vệ']
        );
        $catMusic = ClubCategory::updateOrCreate(
            ['name' => 'Âm nhạc'],
            ['description' => 'Các CLB âm nhạc, đàn guitar, piano, organ, acoustic, thanh nhạc và ban nhạc biểu diễn']
        );
        $catDance = ClubCategory::updateOrCreate(
            ['name' => 'Dân vũ – Nghệ thuật'],
            ['description' => 'Các CLB dân vũ, nhảy hiện đại, flashmob, khiêu vũ và dàn dựng tiết mục sân khấu']
        );
        $catMedia = ClubCategory::updateOrCreate(
            ['name' => 'Truyền thông – Báo chí'],
            ['description' => 'Các CLB báo chí, phát thanh, truyền hình đa phương tiện PPA TV, phóng sự và nhiếp ảnh']
        );
        $catAcademic = ClubCategory::updateOrCreate(
            ['name' => 'Học thuật – Nghiên cứu'],
            ['description' => 'Các CLB học tập, nghiên cứu khoa học, văn hóa đọc, tranh biện và kỹ năng chuyên ngành Cảnh sát']
        );
        $catSports = ClubCategory::updateOrCreate(
            ['name' => 'Thể thao'],
            ['description' => 'Các CLB thể thao phong trào, bóng đá, bóng chuyền, rèn luyện thể lực chiến sĩ CAND']
        );
        $catVolunteer = ClubCategory::updateOrCreate(
            ['name' => 'Tình nguyện – Kỹ năng'],
            ['description' => 'Các đội nhóm tình nguyện, công tác xã hội, hiến máu nhân đạo và kỹ năng sống']
        );

        // 2. Nạp dữ liệu Danh sách Câu Lạc Bộ thực tế của Học viện CSND
        $clubs = [
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
                'category_id' => $catMartialArts->id,
                'description' => 'Dành cho các bạn có sở thích và niềm đam mê với võ thuật nói chung và bộ môn Karate nói riêng. CLB hoạt động mục đích rèn luyện sức khoẻ kết nối mọi người có chung niềm đam mê với môn Karate.',
                'missions' => [
                    'Tổ chức tập luyện các kỹ thuật căn bản, kata, kumite, tự vệ...',
                    'Rèn luyện thể lực, kỷ luật thép, sự nhạy bén và ý chí kiên cường cho học viên.',
                    'Biểu diễn võ thuật, tham gia thi đấu các giải thể thao phong trào và võ thuật.',
                ],
                'regular_activities' => [
                    'Các buổi tập luyện võ thuật định kỳ tại sân bãi/nhà thi đấu của Học viện.',
                    'Tập huấn nâng cao đai đẳng, kỹ thuật đối kháng và quyền pháp.',
                    'Tổ chức giao lưu, thi đấu cọ xát với các câu lạc bộ võ thuật ngoài đơn vị.',
                ],
                'membership_requirements' => [
                    'Có sức khỏe tốt, yêu thích võ thuật và đam mê bộ môn Karate.',
                    'Chấp hành nghiêm chỉnh nội quy võ đường, kỷ luật huấn luyện và điều lệnh CAND.',
                    'Cam kết tham gia đều đặn các buổi tập luyện.',
                ],
                'management_structure' => [
                    'Chủ nhiệm',
                    'Phó Chủ nhiệm chuyên môn',
                    'Phó Chủ nhiệm phong trào',
                    'Các ban: Huấn luyện, Hậu cần – Đội hình biểu diễn',
                ],
                'achievements' => [
                    'Giành nhiều huy chương và giải thưởng tại các giải vô địch Karate, các giải thể thao học sinh, sinh viên và lực lượng vũ trang.',
                    'Đóng góp lực lượng nòng cốt cho các màn biểu diễn võ thuật cấp Bộ, cấp Học viện.',
                ],
                'recruitment_process' => [
                    'Nộp đơn & sản phẩm dự tuyển',
                    'Test thể lực chung (hít đất, gập bụng, sức bền cơ bản).',
                    'Phỏng vấn với ban chủ nhiệm',
                ],
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
                'category_id' => $catMartialArts->id,
                'description' => 'Dành cho các bạn có sở thích và niềm đam mê với võ thuật nói chung và bộ môn Taekwondo nói riêng. CLB hoạt động mục đích rèn luyện sức khoẻ kết nối mọi người có chung niềm đam mê với môn Taekwondo.',
                'missions' => [
                    'Đào tạo và huấn luyện các kỹ thuật đòn chân, quyền (Poomsae), đối kháng...',
                    'Xây dựng đội hình biểu diễn võ thuật phục vụ các sự kiện lớn, lễ hội, ngày hội.',
                    'Rèn luyện tác phong nhanh nhẹn, ý chí kiên định và tinh thần võ đạo thượng võ.',
                ],
                'regular_activities' => [
                    'Duy trì các buổi tập luyện kỹ thuật, thể lực định kỳ trong tuần.',
                    'Tập luyện nâng đai, kiểm tra trình độ định kỳ cho các võ sinh.',
                    'Tham gia giao lưu, thi đấu các giải thể thao, võ thuật do Học viện, ngành.',
                ],
                'membership_requirements' => [
                    'Sức khỏe tốt, đam mê võ thuật và đặc biệt yêu thích các đòn cước uy lực của Taekwondo.',
                    'Có tinh thần kỷ luật cao, tôn trọng võ đạo, tôn trọng huấn luyện viên và đồng môn.',
                    'Sắp xếp thời gian tham gia đầy đủ lịch tập luyện của CLB.',
                ],
                'management_structure' => [
                    'Chủ nhiệm',
                    'Phó Chủ nhiệm chuyên môn',
                    'Phó Chủ nhiệm phong trào',
                    'Các ban: Đội tuyển đối kháng, Đội biểu diễn quyền, Ban Hậu cần',
                ],
                'achievements' => [
                    'Đạt nhiều huy chương vàng, bạc, đồng tại các giải đấu Taekwondo học sinh, sinh viên và các cấp tổ chức.',
                    'Thực hiện thành công các màn đồng diễn võ thuật quy mô lớn trong các buổi lễ chào mừng, bế mạc hội thao.',
                ],
                'recruitment_process' => [
                    'Nộp đơn đăng ký tuyển sinh viên vào CLB',
                    'Kiểm tra thể lực nền tảng (sự dẻo dai, sức bền và độ linh hoạt)',
                    'Phỏng vấn với ban chủ nhiệm',
                ],
            ],
            [
                'name' => 'CLB Guitar Học Viện CSND (PGC)',
                'logo' => '/images/clubs/guitar/guitar-2.jpg',
                'images' => [
                    '/images/clubs/guitar/guitar-2.jpg',
                    '/images/clubs/guitar/guitar-3.jpg',
                    '/images/clubs/guitar/guitar-4.jpg',
                    '/images/clubs/guitar/guitar-5.jpg',
                    '/images/clubs/guitar/guitar-6.jpg',
                    '/images/clubs/guitar/guitar-1.jpg',
                ],
                'founded_date' => '2010-04-22',
                'category_id' => $catMusic->id,
                'description' => 'Câu lạc bộ dành cho những bạn có niềm đam mê về âm nhạc, đặc biệt là nhạc cụ Guitar; tạo môi trường giao lưu lành mạnh, nuôi dưỡng tâm hồn nghệ thuật và gắn kết đoàn viên thanh niên.',
                'missions' => [
                    'Tổ chức các lớp hướng dẫn, truyền lửa và dạy đàn guitar từ cơ bản - nâng cao.',
                    'Xây dựng đội ngũ biểu diễn phục vụ các hoạt động ngoại khóa, biểu diễn.',
                    'Tạo không gian sinh hoạt âm nhạc ấm cúng, thư giãn.',
                ],
                'regular_activities' => [
                    'Buổi sinh hoạt đệm đàn, hát giao lưu định kỳ hàng tuần.',
                    'Tổ chức các buổi workshop hướng dẫn hợp âm, ngón tay (fingerstyle), kỹ thuật.',
                    'Biểu diễn acoustic tại các chương trình lửa trại, ngày hội sinh viên,...',
                ],
                'membership_requirements' => [
                    'Yêu thích âm nhạc và có niềm đam mê với tiếng đàn Guitar (có hoặc chưa biết chơi đều có thể tham gia).',
                    'Sẵn sàng học hỏi, luyện tập chăm chỉ và tham gia các hoạt động biểu diễn tập thể.',
                    'Có tinh thần trách nhiệm với tập thể CLB.',
                ],
                'management_structure' => [
                    'Chủ nhiệm',
                    'Phó Chủ nhiệm chuyên môn',
                    'Phó Chủ nhiệm hậu cần – sự kiện',
                    'Các ban: Đào tạo, Biểu diễn, Hậu cần – Truyền thông',
                ],
                'achievements' => [
                    'Cung cấp nguồn nhân lực nòng cốt cho các chương trình văn nghệ lớn của Học viện.',
                    'Tổ chức thành công nhiều đêm nhạc acoustic tạo tiếng vang lớn trong thanh niên nhà trường.',
                ],
                'recruitment_process' => [
                    'Nộp đơn & sản phẩm dự tuyển',
                    'Kiểm tra năng khiếu/mức độ tiếp cận nhạc cụ',
                    'Phỏng vấn với ban chủ nhiệm',
                ],
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
                'category_id' => $catDance->id,
                'description' => 'Câu lạc bộ dành cho những bạn đam mê và muốn thử sức với bộ môn nhảy hiện đại, làm truyền thông với một câu lạc bộ chuyên về nhảy, không cần năng khiếu không thiếu niềm vui',
                'missions' => [
                    'Xây dựng các tiết mục nhảy dân vũ, nhảy hiện đại phục vụ các chương trình.',
                    'Tổ chức các lớp tập huấn, giao lưu văn nghệ rèn luyện thể chất.',
                    'Xây dựng hình ảnh, làm truyền thông lan tỏa phong trào văn nghệ.',
                ],
                'regular_activities' => [
                    'Buổi tập luyện vũ đạo định kỳ hàng tuần.',
                    'Tham gia biểu diễn tại các chương trình chào tân sinh viên, hội diễn văn nghệ...',
                    'Quay dựng các video cover dance, clip ngắn bắt trend làm truyền thông.',
                ],
                'membership_requirements' => [
                    'Yêu thích âm nhạc, đam mê vũ đạo và muốn thử sức với bộ môn nhảy hiện đại.',
                    'Không yêu cầu năng khiếu đầu vào khắt khe, quan trọng là tinh thần nhiệt huyết và sự cầu tiến.',
                    'Sắp xếp thời gian tham gia đầy đủ các buổi tập luyện chung.',
                ],
                'management_structure' => [
                    'Chủ nhiệm',
                    'Phó Chủ nhiệm chuyên môn (Biên đạo)',
                    'Phó Chủ nhiệm hậu cần – truyền thông',
                    'Các ban: Hậu cần, Truyền thông...',
                ],
                'achievements' => [
                    'Đạt giải cao tại các hội diễn văn nghệ, hội thi nhảy dân vũ cấp Học viện và tuổi trẻ lực lượng vũ trang.',
                    'Biểu diễn thành công tại nhiều sự kiện lớn quy mô cấp trường và liên kết.',
                ],
                'recruitment_process' => [
                    'Nộp đơn đăng ký thành viên trực tuyến.',
                    'Buổi thử sức năng động (Thực hiện các động tác nhảy cơ bản theo hướng dẫn).',
                    'Phỏng vấn với ban chủ nhiệm',
                ],
            ],
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
                'description' => 'CLB là sân chơi cho các bạn đam mê truyền thông, nhiếp ảnh, biên tập, phát thanh',
                'missions' => [
                    'Sản xuất các bản tin phát thanh, bài viết, chuyên mục nội san của Học viện.',
                    'Quản lý hệ thống phát thanh nội bộ và các chuyên trang thông tin.',
                    'Thực hiện công tác truyền thông, đưa tin cho các sự kiện, hoạt động lớn.',
                ],
                'regular_activities' => [
                    'Thực hiện các số phát thanh định kỳ trên hệ thống loa/kênh thông tin nội bộ.',
                    'Biên tập và phát hành ấn phẩm nội san.',
                    'Tổ chức tập huấn kỹ năng viết bản tin, giọng đọc phát thanh, kỹ năng biên tập.',
                ],
                'membership_requirements' => [
                    'Đam mê truyền thông, phát thanh, viết lách hoặc nhiếp ảnh.',
                    'Có giọng đọc tốt (đối với phát thanh viên) hoặc kỹ năng biên tập, xử lý âm thanh.',
                    'Chủ động, trách nhiệm và gắn bó với công việc của CLB.',
                ],
                'management_structure' => [
                    'Chủ nhiệm',
                    'Phó Chủ nhiệm nội dung',
                    'Phó Chủ nhiệm kỹ thuật',
                    'Các ban: Phát thanh, Biên tập, Kỹ thuật – Âm thanh.',
                ],
                'achievements' => [
                    'Nhiều ấn phẩm nội san và chương trình phát thanh đạt giải cao cấp Học viện và các cơ quan cấp trên.',
                    'Đóng góp tích cực trong công tác tuyên truyền chung của tuổi trẻ Học viện.',
                ],
                'recruitment_process' => [
                    'Nộp đơn đăng ký & sản phẩm thử sức (giọng đọc/bài viết).',
                    'Thử giọng / Test kỹ năng chuyên môn.',
                    'Phỏng vấn trực tiếp với Ban Chủ nhiệm.',
                ],
            ],
            [
                'name' => 'CLB Truyền Hình PPA TV',
                'logo' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=200&q=80',
                'images' => [],
                'founded_date' => '2017-09-25',
                'category_id' => $catMedia->id,
                'description' => 'CLB dành cho các bạn có niềm đam mê với báo chí, truyền thông, tổ chức sự kiện',
                'missions' => [
                    'Sản xuất tin, bài, ảnh, video về hoạt động Đoàn',
                    'Quản trị fanpage, website và các nền tảng số',
                    'Truyền thông cho các sự kiện, phong trào lớn',
                ],
                'regular_activities' => [
                    'Livestream, đưa tin các sự kiện của Học viện',
                    'Chuỗi phóng sự "Tuổi trẻ Học viện"',
                    'Workshop kỹ năng truyền thông, nhiếp ảnh',
                ],
                'membership_requirements' => [
                    'Đam mê truyền thông, sáng tạo',
                    'Có kỹ năng viết, quay, dựng hoặc thiết kế',
                    'Chủ động, chịu áp lực tiến độ',
                ],
                'management_structure' => [
                    'Chủ nhiệm',
                    'Phó Chủ nhiệm nội dung',
                    'Phó Chủ nhiệm kỹ thuật',
                    'Các ban: Quay dựng, Thiết kế, Nội dung',
                ],
                'achievements' => [
                    'Giải nhất Liên hoan truyền thông sinh viên khối CAND',
                    'Nhiều sản phẩm đạt giải cấp Bộ',
                ],
                'recruitment_process' => [
                    'Nộp đơn & sản phẩm dự tuyển',
                    'Test kỹ năng chuyên môn',
                    'Phỏng vấn với ban chủ nhiệm',
                ],
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
                'description' => 'Câu lạc bộ học thuật dành cho những bạn đam mê đọc sách, viết lách và truyền thông; khơi dậy văn hóa đọc, lan tỏa tri thức và biến những trang sách thành hành động thiết thực trong học tập và rèn luyện.',
                'missions' => [
                    'Tổ chức các diễn đàn, buổi tọa đàm chia sẻ, review sách và phương pháp đọc.',
                    'Thúc đẩy phong trào tự học, nghiên cứu và phát triển kỹ năng viết.',
                    'Thực hiện các dự án cộng đồng, hoạt động truyền thông',
                ],
                'regular_activities' => [
                    'Sinh hoạt CLB định kỳ: Thảo luận sách, chia sẻ góc nhìn chuyên môn.',
                    'Viết bài cảm nhận, làm video review sách đăng tải trên các nền tảng số.',
                    'Tổ chức các cuộc thi viết, hội sách hoặc tủ sách tri thức tại đơn vị.',
                ],
                'membership_requirements' => [
                    'Có niềm đam mê với việc đọc sách, viết lách và mong muốn rèn luyện tư duy.',
                    'Yêu thích hoạt động học thuật, truyền thông và lan tỏa tri thức.',
                    'Tinh thần ham học hỏi, chủ động tham gia các buổi sinh hoạt định kỳ.',
                ],
                'management_structure' => [
                    'Chủ nhiệm',
                    'Phó Chủ nhiệm nội dung',
                    'Phó Chủ nhiệm truyền thông - dự án',
                    'Các ban: Nội dung, Truyền thông - Sự kiện',
                ],
                'achievements' => [
                    'Xây dựng thành công nhiều tủ sách thanh niên và các diễn đàn học thuật uy tín trong học viện.',
                    'Nhiều bài viết, dự án đọc sách đạt giải thưởng cao trong hệ thống phong trào Sách và Hành động rộng rãi.',
                ],
                'recruitment_process' => [
                    'Nộp đơn & sản phẩm dự tuyển',
                    'Test kỹ năng chuyên môn',
                    'Phỏng vấn với ban chủ nhiệm',
                ],
            ],
        ];

        foreach ($clubs as $c) {
            Club::updateOrCreate(['name' => $c['name']], $c);
        }

        // 3. Nạp dữ liệu Banners (Slider ảnh bìa thực tế từ Đoàn Học viện)
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
            Banner::firstOrCreate(['image_url' => $b['image_url']], $b);
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
            Activity::firstOrCreate(['title' => $act['title']], $act);
        }

        // 5. Nạp dữ liệu Ban Giám Đốc Học viện, Ban Thường Vụ Đoàn & Gương mặt tiêu biểu
        $people = [
            // --- BAN GIÁM ĐỐC HỌC VIỆN CẢNH SÁT NHÂN DÂN ---
            [
                'name' => 'Trung tướng, GS.TS, NGƯT Trần Minh Hưởng',
                'position' => 'Bí thư Đảng ủy, Giám đốc Học viện',
                'order' => 1,
                'avatar' => '/images/bgd-hoc-vien/tran-minh-huong.jpg',
                'role_group' => 'BGD',
                'class_unit' => 'Ban Giám đốc Học viện CSND',
                'achievement' => 'Trung tướng, Giáo sư, Tiến sĩ, Nhà giáo Ưu tú Trần Minh Hưởng - Bí thư Đảng ủy, Giám đốc Học viện Cảnh sát nhân dân',
                'is_active' => true,
            ],
            [
                'name' => 'Thiếu tướng, TS Chử Văn Dũng',
                'position' => 'Ủy viên BTV Đảng ủy, Phó Giám đốc Học viện',
                'order' => 2,
                'avatar' => '/images/bgd-hoc-vien/chu-van-dung.jpg',
                'role_group' => 'BGD',
                'class_unit' => 'Ban Giám đốc Học viện CSND',
                'achievement' => 'Thiếu tướng, Tiến sĩ Chử Văn Dũng - Ủy viên Ban Thường vụ Đảng ủy, Phó Giám đốc Học viện Cảnh sát nhân dân',
                'is_active' => true,
            ],
            [
                'name' => 'Thiếu tướng, PGS.TS Trần Quang Huyên',
                'position' => 'Ủy viên BTV Đảng ủy, Phó Giám đốc Học viện',
                'order' => 3,
                'avatar' => '/images/bgd-hoc-vien/tran-quang-huyen.jpg',
                'role_group' => 'BGD',
                'class_unit' => 'Ban Giám đốc Học viện CSND',
                'achievement' => 'Thiếu tướng, Phó Giáo sư, Tiến sĩ Trần Quang Huyên - Ủy viên Ban Thường vụ Đảng ủy, Phó Giám đốc Học viện Cảnh sát nhân dân',
                'is_active' => true,
            ],
            [
                'name' => 'Đại tá, PGS.TS Nguyễn Đăng Sáu',
                'position' => 'Ủy viên BTV Đảng ủy, Phó Giám đốc Học viện',
                'order' => 4,
                'avatar' => '/images/bgd-hoc-vien/nguyen-dang-sau.jpg',
                'role_group' => 'BGD',
                'class_unit' => 'Ban Giám đốc Học viện CSND',
                'achievement' => 'Đại tá, Phó Giáo sư, Tiến sĩ Nguyễn Đăng Sáu - Ủy viên Ban Thường vụ Đảng ủy, Phó Giám đốc Học viện Cảnh sát nhân dân',
                'is_active' => true,
            ],
            [
                'name' => 'Đại tá, PGS.TS Hoàng Anh Tuấn',
                'position' => 'Ủy viên BTV Đảng ủy, Phó Giám đốc Học viện',
                'order' => 5,
                'avatar' => '/images/bgd-hoc-vien/hoang-anh-tuan.jpg',
                'role_group' => 'BGD',
                'class_unit' => 'Ban Giám đốc Học viện CSND',
                'achievement' => 'Đại tá, Phó Giáo sư, Tiến sĩ Hoàng Anh Tuấn - Ủy viên Ban Thường vụ Đảng ủy, Phó Giám đốc Học viện Cảnh sát nhân dân',
                'is_active' => true,
            ],

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
                'position' => 'Cán bộ Đoàn chuyên trách',
                'order' => 4,
                'avatar' => '/images/btv-doan/nguyen-thanh-nghia.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Cán bộ Đoàn chuyên trách Đoàn Học viện CSND',
                'is_active' => true,
            ],
            [
                'name' => 'Nguyễn Xuân Hiếu',
                'position' => 'Cán bộ Đoàn chuyên trách',
                'order' => 5,
                'avatar' => '/images/btv-doan/nguyen-xuan-hieu.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Cán bộ Đoàn chuyên trách Đoàn Học viện CSND',
                'is_active' => true,
            ],

            // --- ỦY VIÊN BAN THƯỜNG VỤ ĐOÀN HỌC VIỆN ---
            [
                'name' => 'Đại úy Nguyễn Trường Giang',
                'position' => 'Ủy viên Ban Thường vụ',
                'order' => 6,
                'avatar' => '/images/btv-doan/nguyen-truong-giang.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Ủy viên Ban Thường vụ Đoàn Thanh niên Học viện CSND',
                'is_active' => true,
            ],
            [
                'name' => 'Đại úy Đinh Văn Thành',
                'position' => 'Ủy viên Ban Thường vụ',
                'order' => 7,
                'avatar' => '/images/btv-doan/dinh-van-thanh.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Ủy viên Ban Thường vụ Đoàn Thanh niên Học viện CSND',
                'is_active' => true,
            ],
            [
                'name' => 'Đại úy Lê Mạnh Quyết',
                'position' => 'Ủy viên Ban Thường vụ',
                'order' => 8,
                'avatar' => '/images/btv-doan/le-manh-quyet.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Ủy viên Ban Thường vụ Đoàn Thanh niên Học viện CSND',
                'is_active' => true,
            ],
            [
                'name' => 'Đại úy Lê Thị Vân Anh',
                'position' => 'Ủy viên Ban Thường vụ',
                'order' => 9,
                'avatar' => '/images/btv-doan/le-thi-van-anh.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Ủy viên Ban Thường vụ Đoàn Thanh niên Học viện CSND',
                'is_active' => true,
            ],
            [
                'name' => 'Đại úy Lê Văn Đức',
                'position' => 'Ủy viên Ban Thường vụ',
                'order' => 10,
                'avatar' => '/images/btv-doan/le-van-duc.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Ủy viên Ban Thường vụ Đoàn Thanh niên Học viện CSND',
                'is_active' => true,
            ],
            [
                'name' => 'Đại úy Nguyễn Thế Vinh',
                'position' => 'Ủy viên Ban Thường vụ',
                'order' => 11,
                'avatar' => '/images/btv-doan/nguyen-the-vinh.jpg',
                'role_group' => 'BTV_DOAN',
                'class_unit' => 'Ban Thường vụ Đoàn Học viện CSND',
                'achievement' => 'Ủy viên Ban Thường vụ Đoàn Thanh niên Học viện CSND',
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
            OutstandingPerson::firstOrCreate(['name' => $p['name']], $p);
        }
    }
}
