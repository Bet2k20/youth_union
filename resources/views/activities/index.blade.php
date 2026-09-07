<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoạt động & Sự kiện | Đoàn Thanh niên</title>
    <!-- Nhúng CSS Bootstrap 5 để làm giao diện đẹp -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: sans-serif; }
        .card { border-radius: 12px; transition: transform 0.2s; }
        .card:hover { transform: translateY(-4px); }
    </style>
</head>
<body>

    <!-- Thanh Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('clubs.index') }}">Đoàn Thanh Niên</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('clubs.index') }}">Câu Lạc Bộ</a>
                <a class="nav-link text-white active fw-bold" href="{{ route('activities.index') }}">Hoạt Động</a>
            </div>
        </div>
    </nav>

    <div class="container py-3">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-success">CÁC HOẠT ĐỘNG & SỰ KIỆN NỔI BẬT</h2>
            <p class="text-muted">Tổng hợp tin tức hoạt động phong trào của Đoàn trường</p>
        </div>

        <div class="row">
            {{-- Vòng lặp lấy từng hoạt động từ Database ra --}}
            @forelse ($activities as $act)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        {{-- Hiển thị hình ảnh nếu có --}}
                        @if($act->thumbnail)
                            <img src="{{ asset($act->thumbnail) }}" class="card-img-top" alt="{{ $act->title }}" style="height: 200px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <span>(Chưa có hình ảnh)</span>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark">{{ $act->title }}</h5>
                            <p class="card-text text-secondary">
                                {{ Str::limit(strip_tags($act->content), 120) }}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small pb-3">
                            📅 Ngày đăng: {{ $act->created_at ? $act->created_at->format('d/m/Y') : 'Mới cập nhật' }}
                        </div>
                    </div>
                </div>
            @empty
                {{-- Thông báo nếu database chưa có bài nào --}}
                <div class="col-12 text-center">
                    <div class="alert alert-info py-4">
                        <h4>Hiện tại chưa có dữ liệu hoạt động nào trong Database!</h4>
                        <p class="mb-0">Dữ liệu lấy từ bảng <code>activities</code> đang rỗng hoặc chưa có bài nào có <code>is_active = 1</code>.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>
