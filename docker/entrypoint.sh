#!/usr/bin/env bash
set -e

cd /var/www/html

# 1. Pastikan .env ada
[ -f .env ] || cp .env.example .env

# Sesuaikan file .env di dalam container untuk lingkungan Docker
sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST:-db}/g" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD:-rootpassword}/g" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE:-102022430022_rute_jadwal_service}/g" .env

# 2. Generate APP_KEY bila belum ada
if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

# 3. Bersihkan cache config agar ENV dari docker-compose terbaca
php artisan config:clear || true

# 4. Tunggu MySQL benar-benar siap.
#    Pakai PDO (driver yang sama dengan Laravel), karena client mysqladmin bawaan
#    Debian (MariaDB) tidak kompatibel dengan auth caching_sha2_password MySQL 8.
echo "Menunggu MySQL di ${DB_HOST}:${DB_PORT} ..."
until php -r '
    try {
        new PDO(
            "mysql:host=".getenv("DB_HOST").";port=".(getenv("DB_PORT") ?: "3306"),
            getenv("DB_USERNAME") ?: "root",
            getenv("DB_PASSWORD") ?: ""
        );
        exit(0);
    } catch (Throwable $e) {
        exit(1);
    }
' 2>/dev/null; do
    sleep 2
done
echo "MySQL siap."

# 5. Migrasi + seed data contoh (seeder dibuat idempotent, aman diulang)
php artisan migrate --force
php artisan db:seed --force || true

# 6. Jalankan service
echo "Service berjalan di http://0.0.0.0:8000"
exec php artisan serve --host=0.0.0.0 --port=8000
