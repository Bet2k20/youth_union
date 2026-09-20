#!/bin/sh
set -e

# Đảm bảo các thư mục storage tồn tại
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs \
         /var/www/html/database

# Đảm bảo file database.sqlite tồn tại
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Cấp quyền ghi đầy đủ cho SQLite và Storage
chmod -R 777 /var/www/html/database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Khởi tạo config cache và package discover
php artisan config:clear || true
php artisan package:discover --ansi || true

# Chạy migration tự động tạo bảng nếu chưa có
php artisan migrate --force || true

# Chỉ seed dữ liệu mẫu ban đầu nếu database hoàn toàn mới / chưa có user nào
NEED_SEED=$(php artisan tinker --execute="echo App\Models\User::count() === 0 ? 'yes' : 'no';" 2>/dev/null || echo "yes")
if [ "$NEED_SEED" = "yes" ]; then
    echo "==> Database trống, tiến hành nạp dữ liệu mẫu ban đầu..."
    php artisan db:seed --class="Database\\Seeders\\SampleDataSeeder" --force || true
else
    echo "==> Database đã có dữ liệu, bảo toàn dữ liệu chỉnh sửa của người dùng."
fi

# Cấp lại quyền ghi sau khi tạo bảng
chmod -R 777 /var/www/html/database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Khởi chạy Apache web server
exec apache2-foreground
