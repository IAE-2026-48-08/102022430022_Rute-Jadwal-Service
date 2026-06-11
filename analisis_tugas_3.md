# Analisis Tugas 3 — Rute & Jadwal Service

**Mata Kuliah:** BBK2HAB3 - Integrasi Aplikasi Enterprise
**Nama:** Alvin Hibatullah
**NIM:** 102022430022
**Service:** Rute & Jadwal (`102022430022_Rute-Jadwal-Service`)
**Team ID:** TEAM-12
**Resource utama:** `schedules`

---

## 1. Transaksi Kritis yang Dipilih

Transaksi kritis pada service ini adalah **pembuatan jadwal baru**:

```
POST /api/v1/schedules
```

### Justifikasi (mengapa kritis & state-changing)

Dari tiga endpoint yang dimiliki service ini, hanya `POST /schedules` yang bersifat **state-changing** — ia menulis baris baru ke database dan mengubah keadaan sistem. Dua endpoint lain (`GET /schedules` dan `GET /schedules/{id}`) hanya membaca data (read-only), sehingga tidak tergolong kritis.

Transaksi ini kritis karena:

1. **Mengubah state bersama.** Jadwal yang dibuat menjadi acuan service lain dalam ekosistem kelompok — khususnya service Tiket & Pembayaran yang menjual tiket berdasarkan jadwal tersedia. Jadwal yang salah berdampak langsung ke transaksi keuangan di hilir.
2. **Wajib diaudit.** Karena memengaruhi data operasional/keuangan, setiap pembuatan jadwal dicatat ke sistem audit legacy (SOAP) sebagai jejak pertanggungjawaban (siapa, kapan, apa).
3. **Wajib disebarkan.** Departemen lain perlu tahu secara real-time saat ada jadwal baru, sehingga event-nya disiarkan ke message broker (RabbitMQ) agar dapat dikonsumsi service mana pun tanpa kopling langsung.

Karena itu `POST /schedules` adalah satu-satunya transaksi yang memicu **rantai integrasi penuh**: verifikasi identitas (SSO) → audit (SOAP) → broadcast event (RabbitMQ).

---

## 2. Skema Role Lokal

Identitas tidak lagi memakai API Key statis (`X-IAE-KEY`), melainkan **JWT** terbitan SSO pusat.

**Alur pemetaan:**

1. Setiap request membawa header `Authorization: Bearer <JWT>`.
2. Middleware `VerifyIaeJwt` mengambil kunci publik dari JWKS (`/api/v1/auth/jwks`) dan memverifikasi tanda tangan token (RS256).
3. Klaim diambil: `sso_subject` dan `roles`.
4. Identitas dipetakan ke **tabel role lokal** `sso_users` (`sso_subject`, `roles`, `last_login_at`) sebagai cerminan lokal identitas SSO.

**Skema kapabilitas berbasis role:**

| Role (dari JWT)                        | Kapabilitas lokal                          |
| -------------------------------------- | ------------------------------------------ |
| memiliki role operasional (mis. `schedule_admin`) | boleh membuat jadwal (`POST /schedules`) |
| tanpa role khusus                      | hanya membaca (`GET /schedules`)           |

Identitas pembuat (`sso_subject`) ikut dibawa ke audit SOAP dan event RabbitMQ (field `approved_by`), sehingga setiap transaksi kritis dapat dilacak pelakunya.

> Catatan: saat pengujian, `warga17@ktp.iae.id` tidak memiliki role khusus (`roles: []`) sehingga diperlakukan sebagai pengguna terautentikasi biasa. Mekanisme pemetaannya sendiri sudah berjalan.

---

## 3. Sequence Diagram — Alur Interaksi dengan Layanan Pusat

// lagi proses buat

## 4. Ringkasan Implementasi

| Modul          | Mekanisme                                                              | Bukti                                                                 |
| -------------- | --------------------------------------------------------------------- | -------------------------------------------------------------------- |
| Federated SSO  | Verifikasi JWT (RS256/JWKS) + pemetaan ke tabel `sso_users`            | Tanpa token → 401, dengan token → 200; baris di `sso_users`          |
| SOAP XML Client| Transformasi JSON → SOAP Envelope, kirim ke `/soap/v1/audit`, simpan resi | Baris di `audit_logs` dengan `receipt_number` (mis. `IAE-LOG-2026-54B6C14B`) |
| AMQP Publisher | Publish event JSON ke `iae.central.exchange`                          | Event `schedule.created` tampil di `/board` dari TEAM-12             |

Setiap event yang diterbitkan membawa `legacy_receipt_number` (dari SOAP) dan `approved_by.sso_subject` (dari SSO), membuktikan ketiga integrasi terangkai dalam satu transaksi.