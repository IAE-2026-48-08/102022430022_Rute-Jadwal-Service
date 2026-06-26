# Rute & Jadwal Service

Mini-service untuk mengelola data rute dan jadwal keberangkatan transportasi publik. Service ini bagian dari ekosistem Integrasi Aplikasi Enterprise (IAE). Pada Tugas 3, service sudah terhubung ke infrastruktur pusat: SSO (login JWT), audit SOAP, dan message broker RabbitMQ.

## Pengembang

- Nama: Alvin Hibatullah
- NIM: 102022430022
- Kelas: SI4808
- Team: TEAM-12

## Gambaran Singkat

Service ini menyediakan tiga endpoint REST untuk rute & jadwal. Sejak Tugas 3, autentikasinya tidak lagi memakai API Key statis, melainkan JWT Bearer token yang diterbitkan SSO pusat. Setiap pembuatan jadwal baru — yang merupakan transaksi kritis — otomatis memicu tiga hal sekaligus: verifikasi identitas lewat SSO, pencatatan ke audit SOAP, dan penyiaran event ke RabbitMQ.

## Fitur

- Autentikasi JWT (SSO). Setiap request diverifikasi terhadap kunci publik (JWKS) SSO pusat memakai RS256, lalu identitas pengguna dipetakan ke tabel lokal `sso_users`.
- Audit SOAP. Saat jadwal dibuat, datanya diubah menjadi SOAP Envelope (XML) dan dikirim ke layanan audit pusat; nomor resi yang dikembalikan disimpan di tabel `audit_logs`.
- Publish RabbitMQ. Event `schedule.created` disiarkan ke exchange `iae.central.exchange` dengan routing key `schedule.created`, agar bisa dikonsumsi service lain.
- REST API dengan format respons standar (200, 201, 404).
- Dokumentasi Swagger (L5-Swagger) dengan skema keamanan `ApiKeyAuth`.
- GraphiQL Playground untuk kueri data.

## Endpoint

Semua endpoint membutuhkan header `X-IAE-KEY: 102022430022`.

- `GET /api/v1/schedules` — mengambil semua rute & jadwal
- `GET /api/v1/schedules/{id}` — mengambil detail satu jadwal
- `POST /api/v1/schedules` — membuat jadwal baru (transaksi kritis: memicu SSO + SOAP + RabbitMQ)

## Cara Menjalankan

### Opsi A — Docker (disarankan, sesuai kriteria penilaian)

Cukup satu perintah. Image akan build sendiri, menjalankan `composer install`, migrasi, dan seed data contoh secara otomatis:
```
   docker compose up --build
```
- Service: http://localhost:8000 (Swagger: `/api/documentation`, GraphiQL: `/graphiql`)
- MySQL berjalan di container `db`, diekspos ke host pada port `3307` agar tidak bentrok dengan MySQL Laragon.
- Menghentikan: `Ctrl+C` lalu `docker compose down` (tambahkan `-v` untuk menghapus data DB).

### Opsi B — Native (Laragon)

1. Pastikan MySQL Laragon aktif, lalu buat database `transportasi_2`.
2. Salin `.env.example` menjadi `.env`, sesuaikan `DB_*` dan `IAE_*`.
3. Install dependency dan siapkan database:
```
   composer install
   php artisan key:generate
   php artisan migrate --seed
```
4. Jalankan server:
```
   php artisan serve
```
   Service berjalan di http://127.0.0.1:8000

## Mengakses API

Untuk mengakses API, masukkan API Key Anda (`102022430022`) pada header `X-IAE-KEY`.

Contoh pemanggilan API:
```
GET http://127.0.0.1:8000/api/v1/schedules
X-IAE-KEY: 102022430022
```

Dokumentasi interaktif tersedia di Swagger (`/api/documentation`) dan GraphiQL (`/graphiql`).

## Alur Integrasi Pusat (Tugas 3)

Saat `POST /api/v1/schedules` dipanggil dengan token yang sah, urutannya:

1. Middleware `VerifyIaeJwt` memverifikasi JWT dan memetakan user ke tabel `sso_users`.
2. Jadwal disimpan ke database.
3. `IaeAuditClient` mengirim audit SOAP ke `/soap/v1/audit` dan menyimpan `ReceiptNumber` ke `audit_logs`.
4. `IaePublisher` menyiarkan event `schedule.created` ke RabbitMQ (`iae.central.exchange`).

Event yang terbit membawa `legacy_receipt_number` (dari SOAP) sekaligus `approved_by.sso_subject` (dari SSO), sehingga satu transaksi tercatat lengkap dan dapat ditelusuri.
