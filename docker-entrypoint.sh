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

# Chạy migration và seeder nạp dữ liệu mẫu
php artisan migrate --force || true
php artisan db:seed --class="Database\\Seeders\\SampleDataSeeder" --force || true

# Cấp lại quyền ghi sau khi tạo bảng
chmod -R 777 /var/www/html/database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Khởi chạy Apache web server
exec apache2-foreground
