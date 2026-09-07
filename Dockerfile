FROM php:8.2-apache

# 1. Cài đặt các thư viện hệ thống cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Cài đặt Composer
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

# 7. Script khởi động đảm bảo phân quyền 777 cho storage/logs và tự động migrate database
RUN echo '#!/bin/sh' > /usr/local/bin/docker-entrypoint.sh \
    && echo 'mkdir -p /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/framework/cache /var/www/html/storage/logs' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'php artisan config:clear' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'php artisan package:discover --ansi' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'php artisan migrate --force' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'php artisan db:seed --class=Database\\Seeders\\SampleDataSeeder --force' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'exec apache2-foreground' >> /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]
