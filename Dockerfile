# Dockerfile — Rute & Jadwal Service
# Image mandiri (tanpa Laravel Sail): "composer install" dijalankan DI DALAM build,
# sehingga tidak bergantung pada folder vendor/ di host (penyebab gagal sebelumnya).
FROM php:8.3-cli

# Dependency sistem + ekstensi PHP yang dibutuhkan Laravel & MySQL
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libonig-dev \
        default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath \
    && rm -rf /var/lib/apt/lists/*

# Composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin source code
COPY . .

# Siapkan .env untuk proses build (package:discover), lalu install dependency
RUN cp .env.example .env \
    && composer install --no-interaction --prefer-dist --optimize-autoloader

# Pastikan entrypoint berakhiran LF + executable (hindari error CRLF dari Windows)
RUN sed -i 's/\r$//' docker/entrypoint.sh \
    && chmod +x docker/entrypoint.sh

EXPOSE 8000
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
