# Dockerfile — Rute & Jadwal Service
# Image mandiri: "composer install" dijalankan DI DALAM build, dan database
# memakai SQLite (file-based) sehingga tidak ada dependency container DB.
FROM php:8.3-cli

# Dependency sistem + ekstensi PHP yang dibutuhkan Laravel & SQLite
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libonig-dev \
        libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite mbstring zip bcmath \
    && rm -rf /var/lib/apt/lists/*

# Composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin source code (termasuk .env yang ikut di-commit)
COPY . .

# Pastikan .env tersedia (fallback ke template bila .env tidak ada),
# lalu install dependency.
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Pastikan entrypoint berakhiran LF + executable (hindari error CRLF dari Windows)
RUN sed -i 's/\r$//' docker/entrypoint.sh \
    && chmod +x docker/entrypoint.sh

EXPOSE 8000
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
