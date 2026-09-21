FROM php:8.2-apache

# 1. Cài đặt các thư viện hệ thống cần thiết cho Laravel (bao gồm SQLite, MySQL và PostgreSQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql pdo_pgsql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Cấu hình PHP & Composer (Dung lượng upload ảnh tối đa 50MB)
RUN echo "memory_limit = 512M\nupload_max_filesize = 50M\npost_max_size = 50M\nmax_execution_time = 300" > /usr/local/etc/php/conf.d/uploads.ini
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Thiết lập thư mục làm việc
WORKDIR /var/www/html

# 4. Sao chép toàn bộ mã nguồn vào container
COPY . .

# 5. Cài đặt dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# 6. Cấu hình Apache DocumentRoot trỏ vào /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

# 7. Cấp quyền cho Database SQLite, Storage và Thư mục uploads
RUN mkdir -p /var/www/html/public/uploads/activities \
             /var/www/html/public/uploads/clubs \
             /var/www/html/public/uploads/people \
             /var/www/html/public/uploads/banners \
             /var/www/html/public/uploads/avatars \
             /var/www/html/public/uploads/general \
             /var/www/html/public/uploads/documents \
    && chmod -R 777 /var/www/html/database \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && sed -i 's/\r$//' /var/www/html/docker-entrypoint.sh \
    && cp /var/www/html/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]

