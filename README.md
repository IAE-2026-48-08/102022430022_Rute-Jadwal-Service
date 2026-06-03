# Rute & Jadwal Service API 🚌

Mini-service ini dikembangkan untuk mengelola data jadwal keberangkatan dan rute transportasi publik. Proyek ini merupakan bagian dari ekosistem *Enterprise* dan ditujukan untuk memenuhi **Tugas 2 - Integrasi Aplikasi Enterprise (IAE)**.

**Informasi Pengembang:**
* **Nama:** Alvin Hibatullah
* **NIM:** 102022430022
* **Kelas:** SI4808

---

## 🚀 Fitur Utama

Layanan ini telah memenuhi Standard Integration Contract (IAE-T2) dengan mengimplementasikan berbagai protokol komunikasi modern dan standar arsitektur *deployment* industri:

1. **Containerized Environment (Docker) 🐳:**
   Berjalan sepenuhnya di dalam *container* menggunakan Laravel Sail untuk menjamin konsistensi *environment* pengembangan.
2. **REST API (JSON Wrapper):**
   Menyediakan 3 endpoint utama dengan format respons terstandarisasi (200 OK, 201 Created, 404 Not Found).
3. **Keamanan via API Key & Data Protection:**
   Seluruh endpoint diproteksi menggunakan Header Authentication dengan key `X-IAE-KEY`. Sistem juga dilindungi dari eksploitasi kerentanan *Mass Assignment*.
4. **Automated Data Seeding 🌱:**
   Database terotomatisasi dengan *dummy data* rute dan jadwal untuk kemudahan pengujian.
5. **Dokumentasi OpenAPI/Swagger:**
   Antarmuka interaktif UI (L5-Swagger) untuk menguji REST API secara langsung.
6. **GraphQL Integration:**
   Mendukung kueri data tingkat lanjut menggunakan Lighthouse dan GraphiQL Playground.

---

## 💻 Cara Menjalankan Service (Dockerized)

Karena proyek ini menggunakan lingkungan Docker, pastikan aplikasi *local server* (seperti Laragon/XAMPP) **dimatikan** agar port 80 dan 3306 tidak mengalami bentrok.

1. **Jalankan Mesin Container Docker:**
   ```bash
docker compose up -d
```
2. **Jalankan Migrasi & Seeder Database:**
   ```bash
docker compose exec laravel.test php artisan migrate:fresh --seed
```
3. **Generate Ulang Dokumentasi Swagger (Wajib):**
   ```bash
docker compose exec laravel.test php artisan l5-swagger:generate
```

---

## 🌐 Akses Layanan & Playground

Layanan berjalan di *port* standar (80). Silakan akses URL berikut melalui *browser*:

* **Swagger UI (REST API Docs):** [http://localhost/api/documentation](http://localhost/api/documentation)
* **GraphiQL (GraphQL Playground):** [http://localhost/graphiql](http://localhost/graphiql)

---

## 🛠️ Spesifikasi Endpoint (REST API)

Semua *request* wajib menyertakan header `X-IAE-KEY` yang diisi dengan NIM pengakses.

| Method | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/api/v1/schedules` | Mengambil semua daftar rute dan jadwal armada. |
| `GET` | `/api/v1/schedules/{id}` | Mengambil detail spesifik dari satu jadwal. |
| `POST` | `/api/v1/schedules` | Mendaftarkan jadwal keberangkatan armada baru. |

---

## ⚙️ Kueri GraphQL

Sistem mendukung fleksibilitas pengambilan *field* data melalui GraphiQL Playground.

**Contoh Query:**
```graphql
query {
  schedules {
    id
    route
    price
    facilities
  }
}
```

<br>

---
---

# 📊 Lampiran: AI Usage & Prompt Engineering Report
*Laporan Analitik Penggunaan AI dalam Pengembangan Modul Rute & Jadwal*

## 1. Ringkasan Umum Percakapan
* **Tujuan utama penggunaan AI:** Melakukan migrasi *environment* pengembangan dari *local server* ke ekosistem *Container* (Docker), melakukan otomatisasi data (*Seeding*), serta *debugging* pada respons REST API.
* **Topik besar yang dibahas:** *Containerization*, Database Seeding, Tipe Data MySQL, HTTP CORS (Cross-Origin Resource Sharing), dan Keamanan *Mass Assignment* pada ekosistem Laravel.
* **Jenis proyek:** Pengembangan *Backend Microservice* untuk **Rute & Jadwal Service**.

## 2. Daftar Seluruh Prompt yang Pernah Digunakan

| No | Intensi / Isi Prompt Singkat | Kategori | Teknologi/Tools | Output yang Diminta |
| :--- | :--- | :--- | :--- | :--- |
| **1** | Bertanya apakah Laragon harus dimatikan saat ingin memakai Docker. | `DevOps` / `Research` | Docker, Laragon | Pemahaman arsitektur *port* dan *container*. |
| **2** | Menanyakan cara melihat kredensial *password* database di Docker. | `Security` / `DevOps` | Docker Compose | Lokasi *credentials* pada `docker-compose.yml`. |
| **3** | Melaporkan *error* `There are no commands defined in the "sail" namespace`. | `Debugging` | Laravel Sail | Solusi instalasi *dependency*. |
| **4** | Melaporkan *error* eksekusi shell script `/bin/bash` di terminal Windows. | `Debugging` | Windows PowerShell | Alternatif *command* berbasis Windows. |
| **5** | Meminta konfirmasi URL untuk pengujian Swagger dan GraphiQL. | `Research` | Swagger UI | Jalur akses *port* standar *container*. |
| **6** | Bertanya apakah *project* ini sebaiknya menggunakan *Seed*. | `Coding` | Laravel Seeder | Pembuatan *script* data jadwal otomatis. |
| **7** | Melaporkan anomali SQL `Incorrect datetime value: '2008-00-00'`. | `Debugging` | MySQL, PHP | Identifikasi *root cause* tipe data. |
| **8** | Menyelidiki *error* integrasi jaringan `Failed to fetch` & `CORS` di Swagger. | `Debugging` | Swagger UI | Analisis perbaikan URL server (*hardcoded*). |
| **9** | Membedah *script* `Controller.php` untuk menemukan *bug* duplikasi URI. | `Debugging` | PHP Attributes | Perbaikan *Base URL* Controller. |
| **10** | Menangani eksploitasi *request payload* (Error 500 saat eksekusi POST API). | `Debugging` | Laravel Eloquent | Implementasi filter `$fillable` pada Model. |
| **11** | Meminta pembuatan dokumentasi laporan dengan format analitik *advanced*. | `Dokumentasi` | Prompt Engineering | Laporan audit terstruktur (*Report*). |

## 3. Rekap Teknologi dan Stack yang Digunakan
Ekosistem teknologi yang digunakan sangat mencerminkan standar industri modern:
* **Bahasa Pemrograman:** PHP 8.2+
* **Framework:** Laravel 11/13
* **DevOps / Environment:** Docker, Docker Compose, Laravel Sail, Windows PowerShell.
* **Database:** MySQL (berjalan secara mandiri di dalam *container*).
* **API & Dokumentasi:** RESTful API, OpenAPI (L5-Swagger), GraphQL (GraphiQL).
* **Keamanan:** Laravel Mass Assignment Protection (`$fillable`).

## 4. Identifikasi Aktivitas Pengembangan
Aktivitas yang terekam berfokus pada stabilitas sistem dan integrasi infrastruktur:
* **Migrasi Infrastruktur:** Memindahkan *service* secara total dari *local environment* ke *Containerization*.
* **Otomatisasi Data:** Menyusun *Seeder* untuk mempercepat proses *testing* dan evaluasi.
* **Debugging Lintas Layer:** Menyelesaikan masalah konektivitas antara konfigurasi UI klien (Swagger), rute jaringan (CORS/URL Port), dan keamanan lapisan data (Laravel Eloquent).
* **Standardisasi Dokumentasi:** Menyusun laporan rekam jejak pengembangan yang profesional untuk keperluan evaluasi akademis.

## 5. Timeline Aktivitas (Kronologi)
1. **Fase 1 (Transisi DevOps):** Menghentikan *service* server lokal, menyelesaikan isu dependensi Composer, dan melakukan inisialisasi mesin Docker tanpa bentrok *port* koneksi.
2. **Fase 2 (Data Seeding):** Menyiapkan kerangka *dummy data*. Mengalami limitasi *parsing* tipe data MySQL (`DATETIME`), yang dianalisis dan diperbaiki dengan melengkapi format ke resolusi presisi `YYYY-MM-DD HH:MM:SS`.
3. **Fase 3 (Integrasi Jaringan):** Menemukan anomali UI yang gagal mem- *fetch* data rute jaringan. Analisis mendalam menemukan adanya anomali *cache URL* (sisa *port* 8000 dan anomali jalur *double* `/api/v1`). Dieksekusi perbaikannya melalui modifikasi atribut `#[OA\Server]`.
4. **Fase 4 (Validasi Keamanan Endpoint):** Pengujian penetrasi menggunakan metode POST memicu *Exception Alert* dari proteksi kerentanan kerangka kerja. Masalah diselesaikan di lapisan arsitektural menggunakan pembatasan skema `$fillable` di tingkat Model.
5. **Fase 5 (Dokumentasi):** Penyusunan log aktivitas dan laporan audit teknikal ini.

## 6. Statistik Prompting
* **Total prompt:** 11 Prompt (Sesi Lanjutan).
* **Kategori dominan:** Debugging (54%), DevOps & Research (36%).
* **Masalah teknis (Bug) yang diselesaikan:** 5 Kasus.

## 7. Insight dan Pola Penggunaan AI
* **Workflow Kerja:** Sangat adaptif dan observatif. Pendekatan diagnosis proaktif—dengan menyertakan tangkapan layar antarmuka pengguna bersamaan dengan log terminal (CLI)—mempercepat resolusi identifikasi *error*.
* **Technical Depth:** Menunjukkan transisi kompetensi menuju level *Advanced*. Penggalian struktur operasional Docker serta kepekaan dalam menyikapi parameter pemblokiran injeksi data pada *framework* menunjukkan pola pikir berbasis *Software Engineering* terapan.
* **Problem Solving:** Bergerak dengan orientasi hasil (*Action-oriented*). Setiap *output code* yang diberikan segera dieksekusi dalam *container*, divalidasi respons HTTP-nya (mulai dari analisis transisi 404, ke 500, hingga 200/201), lalu ditarik kembali kesimpulannya.

### 🌟 Rekomendasi Resolusi (High-Impact Prompt)
* **Kasus Resolusi Mass Assignment (Prompt #10):** Evaluasi *Exception Request Payload* menunjukkan bahwa dokumentasi UI telah dimanfaatkan secara maksimal sebagai medium *penetration test*. Diagnosis yang cermat atas pesan penolakan *framework* memantik diskusi teknis krusial mengenai urgensi penerapan keamanan input data pada arsitektur perangkat lunak.
