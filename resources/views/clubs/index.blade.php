<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Câu lạc bộ | Đoàn Thanh niên</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f3f7f6; color: #17352d; font-family: Arial, sans-serif; }
        header { background: #087f5b; color: #fff; padding: 44px 20px; text-align: center; }
        header h1 { margin: 0; font-size: 32px; }
        header p { margin: 10px 0 0; opacity: .9; }
        main { max-width: 1080px; margin: 0 auto; padding: 36px 20px 60px; }
        .count { margin: 0 0 22px; color: #4d635d; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .card { background: #fff; border: 1px solid #dbe8e2; border-radius: 14px; padding: 22px; box-shadow: 0 4px 14px rgba(23,53,45,.06); }
        .category { display: inline-block; padding: 5px 10px; border-radius: 999px; background: #d9f3e8; color: #087f5b; font-size: 13px; font-weight: bold; }
        h2 { margin: 16px 0 10px; font-size: 21px; }
        .date { color: #60736d; font-size: 14px; }
        .description { margin: 16px 0 0; color: #40534e; line-height: 1.55; }
        .empty { background: #fff; border-radius: 14px; padding: 28px; text-align: center; color: #4d635d; }
    </style>
</head>
<body>
    <header>
        <h1>Câu lạc bộ Đoàn Thanh niên</h1>
        <p>Khám phá các câu lạc bộ đang hoạt động</p>
    </header>

    <main>
        <p class="count">Có {{ $clubs->count() }} câu lạc bộ.</p>

        @forelse ($clubs as $club)
            @if ($loop->first)
                <div class="grid">
            @endif
                <article class="card">
                    <span class="category">{{ $club->category?->name ?? 'Chưa phân loại' }}</span>
                    <h2>{{ $club->name }}</h2>
                    @if ($club->founded_date)
                        <p class="date">Thành lập: {{ $club->founded_date->format('d/m/Y') }}</p>
                    @endif
                    @if ($club->description)
                        <p class="description">{{ $club->description }}</p>
                    @endif
                </article>
            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="empty">Chưa có câu lạc bộ nào trong database.</div>
        @endforelse
    </main>
</body>
</html>
