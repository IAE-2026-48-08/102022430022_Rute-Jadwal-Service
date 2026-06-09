# AI Usage & Prompt Engineering Report
**Mata Kuliah:** Integrasi Aplikasi Enterprise (Tugas 2)
**Nama:** Alvin Hibatullah
**NIM:** 102022430022
**Service:** Rute & Jadwal
**Tanggal Sesi:** 3 Juni 2026 (Fase Lanjutan)

---

## 1. Ringkasan Umum Percakapan

* **Tujuan utama penggunaan AI:** Melakukan migrasi *environment* pengembangan dari *local server* (Laragon) ke ekosistem *Container* (Docker) menggunakan Laravel Sail, melakukan otomatisasi data (*Seeding*), serta melakukan *debugging* pada respons REST API.
* **Topik besar yang dibahas:** *Containerization*, Database Seeding, Tipe Data MySQL, HTTP CORS (Cross-Origin Resource Sharing), dan Keamanan *Mass Assignment* pada Laravel.
* **Jenis proyek:** Pengembangan *Backend Microservice* untuk **Rute & Jadwal Service**.

---

## 2. Daftar Seluruh Prompt yang Pernah Digunakan

| No | Intensi / Isi Prompt Singkat | Kategori | Teknologi/Tools | Output yang Diminta | Bahasa |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **1** | Bertanya apakah Laragon harus dimatikan saat ingin memakai Docker. | `DevOps` <br> `Research` | Docker, Laragon | Pemahaman arsitektur *port* dan *container*. | ID |
| **2** | Menanyakan cara melihat *password* database di Docker. | `Security` <br> `DevOps` | Docker Compose | Lokasi kredensial pada `docker-compose.yml`. | ID |
| **3** | Melaporkan *error* `There are no commands defined in the "sail" namespace`. | `Debugging` | Laravel Sail, Composer | Solusi instalasi *dependency*. | ID |
| **4** | Melaporkan *error* `/bin/bash` saat menjalankan Sail up di terminal Windows. | `Debugging` | Windows PowerShell, Docker | Alternatif *command* yang kompatibel dengan Windows. | ID |
| **5** | Meminta konfirmasi URL untuk akses Swagger dan GraphiQL di Docker. | `Research` | Swagger UI, GraphiQL | Jalur akses *port* 80 standar. | ID |
| **6** | Bertanya apakah *project* ini sebaiknya menggunakan *Seed* atau tidak. | `Coding` | Laravel Seeder | Pembuatan *script* `DatabaseSeeder.php` untuk *dummy data*. | ID |
| **7** | Melaporkan *error* SQL `Incorrect datetime value: '2008-00-00'`. | `Debugging` | MySQL, PHP | Identifikasi *root cause* tipe data dan solusi perbaikannya. | ID |
| **8** | Mengunggah tangkapan layar *error* `Failed to fetch` & `CORS` di Swagger UI. | `Debugging` | Swagger UI, L5-Swagger | Analisis perbaikan URL server yang *hardcoded*. | ID |
| **9** | Mengunggah *script* `Controller.php` untuk diinvestigasi terkait *bug* URL. | `Debugging` | PHP Attributes | Perbaikan *Base URL* agar sesuai dengan Docker. | ID |
| **10** | Mengunggah tangkapan layar *error* `Internal Server Error 500` saat melakukan POST. | `Debugging` | Laravel Eloquent | Pemahaman *Mass Assignment* dan penambahan `$fillable`. | ID |
| **11** | Meminta pembuatan dokumentasi laporan dengan format analitik *advanced*. | `Dokumentasi` | Prompt Engineering | Laporan audit percakapan AI (*Report*). | ID |

---

## 3. Rekap Teknologi dan Stack yang Digunakan

Berdasarkan log percakapan, ekosistem teknologi yang digunakan sangat mencerminkan standar industri modern:

* **Bahasa Pemrograman:** PHP 8.2+
* **Framework:** Laravel 11/13
* **DevOps / Environment:** Docker, Docker Compose, Laravel Sail, Windows PowerShell.
* **Database:** MySQL (berjalan di dalam *container*).
* **API & Dokumentasi:** RESTful API, OpenAPI (L5-Swagger), GraphQL (GraphiQL).
* **Keamanan:** Laravel Mass Assignment Protection (`$fillable`).

---

## 4. Identifikasi Aktivitas User

Aktivitas yang terekam berfokus pada stabilitas sistem dan integrasi infrastruktur:

* **Migrasi Infrastruktur:** Memindahkan *service* secara total dari *local environment* ke *Containerization*.
* **Otomatisasi Data:** Menyusun *Seeder* untuk mempercepat proses *testing* (menghindari *input* manual berulang).
* **Debugging Lintas Layer:** Menyelesaikan masalah yang terjadi antara konfigurasi UI (Swagger), konfigurasi jaringan (CORS/URL Port), dan keamanan *database* (Laravel Eloquent).
* **Standardisasi Dokumentasi:** Menyusun rekam jejak pengembangan yang profesional untuk keperluan evaluasi akademis dan portofolio.

---

## 5. Timeline Aktivitas (Kronologi)

1.  **Fase 1 (Transisi DevOps):** Menghentikan *service* Laragon lama, menyelesaikan isu dependensi Composer (Laravel Sail), dan berhasil menghidupkan mesin Docker tanpa bentrok *port*.
2.  **Fase 2 (Data Seeding):** Menyiapkan 3 data jadwal bus. Mengalami *error* tipe data MySQL (`DATETIME`), yang dengan cepat dianalisis dan diperbaiki dengan melengkapi format waktu menjadi `YYYY-MM-DD HH:MM:SS`.
3.  **Fase 3 (Integrasi Jaringan):** Menemukan anomali di Swagger UI yang gagal menarik data (*Failed to fetch*). Analisis menunjukkan adanya *cache* URL yang tertinggal (port 8000 dan duplikasi `/api/v1`). Diperbaiki melalui penyesuaian atribut `#[OA\Server]`.
4.  **Fase 4 (Validasi Keamanan Endpoint):** Pengujian POST `schedules` memicu mekanisme pertahanan *default* Laravel (*Mass Assignment Exception*). Diperbaiki secara arsitektural dengan mendaftarkan kolom ke properti `$fillable` pada Model.
5.  **Fase 5 (Dokumentasi):** Pembuatan *Analytics Report*.

---

## 6. Statistik Prompting

* **Total prompt:** 11 Prompt (Sesi Lanjutan).
* **Kategori dominan:** Debugging (54%), DevOps & Research (36%).
* **Masalah teknis (Bug) yang diselesaikan:** 5 (Instalasi Sail, kompatibilitas PowerShell, *Datetime formatter*, URL CORS Swagger, *Mass Assignment*).
* **Bahasa yang dipakai:** Bahasa Indonesia.

---

## 7. Insight dan Pola Penggunaan AI

* **Workflow Kerja User:** Sangat adaptif dan observatif. Ketika menemui *error*, pengguna langsung menyertakan tangkapan layar (UI) dan terminal log (CLI) secara bersamaan. Ini mempercepat AI dalam memberikan diagnosis yang sangat akurat.
* **Tingkat Technical Depth:** Transisi dari tingkat Menengah ke *Advanced*. Keberanian mengulik Docker dan memahami bagaimana Laravel memblokir injeksi data massal menunjukkan insting seorang *Software Engineer* dan fondasi dasar *Cyber Security* yang kuat.
* **Pola Problem Solving:** *Action-oriented*. Setiap solusi yang diberikan AI langsung dieksekusi, diuji coba lewat Swagger, dan jika ada respons kode HTTP yang berbeda (dari 404 ke 500, lalu ke 200/201), pengguna langsung memvalidasi kembali ke AI.

###  High-Impact & Best Prompts

* **[Best Prompt] Prompt #10 (Error 500 pada eksekusi POST):** *(Mengunggah hasil eksekusi Swagger yang menampilkan respons "Add [route] to fillable property...")*
    * *Alasan:* Prompt ini membuktikan bahwa dokumentasi Swagger berfungsi dengan baik sebagai alat *testing*. Pengguna tidak panik melihat *error* 500, melainkan dengan tenang menelusuri pesan internal framework, yang berujung pada pemahaman fitur keamanan esensial (*Mass Assignment*).

---

## 8. Ringkasan Akhir

* **Kesimpulan Sesi:** Sesi ini menghasilkan lompatan arsitektur yang sangat signifikan. *Project* tidak lagi sekadar "bisa berjalan", tetapi sudah memenuhi standar *deployment enterprise* berkat penggunaan Docker dan data *seeder* otomatis.
* **Progress Proyek:** REST API Service Rute & Jadwal telah berjalan sempurna (100%) di lingkungan *Container*. Semua *endpoint* merespons dengan kode HTTP yang sesuai (200 OK, 201 Created, 404 Not Found).
* **Kompetensi Terlihat:** Keahlian dasar *System Administration* (Docker), pemecahan masalah jaringan dasar (URL/Porting), dan pemahaman keamanan tingkat *framework*.
* **Rekomendasi Next Step:**
    1. Melakukan *Commit & Push* ke repositori Git organisasi untuk menyimpan *milestone* arsitektur Docker ini.
    2. Mulai merancang spesifikasi GraphQL *Mutations* (untuk fungsionalitas CRUD tingkat lanjut) dengan mengadopsi pola dari kodingan proyek sejenis.
