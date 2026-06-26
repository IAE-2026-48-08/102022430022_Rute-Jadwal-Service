#!/usr/bin/env bash
set -e

cd /var/www/html

# 1. Pastikan .env ada
[ -f .env ] || cp .env.example .env

# 2. Generate APP_KEY bila belum ada
if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

# 3. Siapkan database SQLite (file-based; tidak ada container DB terpisah)
mkdir -p database
touch database/database.sqlite

# 4. Bersihkan cache config agar ENV dari docker-compose terbaca
php artisan config:clear || true

# 5. Migrasi + seed data contoh (idempotent, aman diulang)
php artisan migrate --force || true
php artisan db:seed --force || true

# 6. Generate dokumentasi Swagger/OpenAPI terbaru (mencerminkan endpoint REST)
php artisan l5-swagger:generate || true

# 7. Jalankan service
echo "Service berjalan di http://0.0.0.0:8000"
exec php artisan serve --host=0.0.0.0 --port=8000
