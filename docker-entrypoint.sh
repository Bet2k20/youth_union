#!/bin/sh
set -e

# Đảm bảo các thư mục storage và upload tồn tại
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs \
         /var/www/html/database \
         /var/www/html/public/uploads/activities \
         /var/www/html/public/uploads/clubs \
         /var/www/html/public/uploads/people \
         /var/www/html/public/uploads/banners \
         /var/www/html/public/uploads/avatars \
         /var/www/html/public/uploads/general \
         /var/www/html/public/uploads/documents

# Đảm bảo file database.sqlite tồn tại
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Cấp quyền ghi đầy đủ cho SQLite, Storage và Uploads
chmod -R 777 /var/www/html/database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/public/uploads
chown -R www-data:www-data /var/www/html/public/uploads || true

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
