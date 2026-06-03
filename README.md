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