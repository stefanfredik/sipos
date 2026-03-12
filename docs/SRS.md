# Software Requirements Specification (SRS)

# SIPOS — Sistem Informasi Posyandu Berbasis Web

## Desa Belumbang, Kecamatan Kerambitan, Kabupaten Tabanan, Bali

---

**Versi:** 1.0.0  
**Tanggal:** Maret 2026  
**Penulis:** Servin Rudianto (NIM. 2201010565)  
**Institusi:** Institut Bisnis dan Teknologi Indonesia (INSTIKI)  
**Program Studi:** Informatika — Fakultas Teknologi dan Informatika

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Gambaran Umum Sistem](#2-gambaran-umum-sistem)
3. [Stack Teknologi](#3-stack-teknologi)
4. [Arsitektur Proyek](#4-arsitektur-proyek)
5. [Kebutuhan Fungsional](#5-kebutuhan-fungsional)
6. [Kebutuhan Non-Fungsional](#6-kebutuhan-non-fungsional)
7. [Desain Database](#7-desain-database)
8. [API Endpoint](#8-api-endpoint)
9. [Autentikasi & Otorisasi](#9-autentikasi--otorisasi)
10. [Docker Configuration](#10-docker-configuration)
11. [Unit Testing Strategy](#11-unit-testing-strategy)
12. [Best Practices & Coding Standards](#12-best-practices--coding-standards)
13. [Struktur Direktori Proyek](#13-struktur-direktori-proyek)
14. [Deployment & CI/CD](#14-deployment--cicd)
15. [Glossary](#15-glossary)

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen ini merupakan Spesifikasi Kebutuhan Perangkat Lunak (SRS) untuk **SIPOS (Sistem Informasi Posyandu)** berbasis web di Desa Belumbang. Dokumen ini menjadi acuan pengembangan sistem yang mencakup desain arsitektur, kebutuhan fungsional, kebutuhan non-fungsional, skema database, konfigurasi infrastruktur, dan strategi pengujian.

### 1.2 Latar Belakang

Posyandu di Desa Belumbang — yang menggunakan model **Integrasi Layanan Primer (ILP) Holistik Integrasi dan Unggul (HIU)** — saat ini mengelola data kesehatan secara manual menggunakan buku tulis. Sistem manual ini menyebabkan:

- Kehilangan dan kerusakan data kesehatan peserta
- Proses pelaporan ke Puskesmas yang lambat dan tidak akurat
- Kesulitan pemantauan riwayat kesehatan ibu hamil, balita, dan lansia
- Ketidakmampuan memantau perkembangan kesehatan secara real-time

**Skala Posyandu Desa Belumbang:**

- 2 bidan
- 8 posyandu (masing-masing 1 per dusun)
- 7 kader per posyandu
- Peserta aktif: 7 ibu hamil, 100 balita, 74 lansia

### 1.3 Ruang Lingkup

SIPOS dibangun sebagai aplikasi web full-stack yang memungkinkan:

1. Pengelolaan data master peserta (ibu hamil, balita, lansia)
2. Pencatatan dan pemantauan data pemeriksaan kesehatan
3. Notifikasi jadwal kegiatan posyandu
4. Pembuatan laporan kesehatan terstruktur
5. Manajemen akun pengguna dengan role-based access control

### 1.4 Definisi, Akronim, dan Singkatan

| Istilah | Keterangan                          |
| ------- | ----------------------------------- |
| SIPOS   | Sistem Informasi Posyandu           |
| ILP     | Integrasi Layanan Primer            |
| HIU     | Holistik Integrasi dan Unggul       |
| KMS     | Kartu Menuju Sehat                  |
| LILA    | Lingkar Lengan Atas                 |
| SRS     | Software Requirements Specification |
| RBAC    | Role-Based Access Control           |
| API     | Application Programming Interface   |
| DFD     | Data Flow Diagram                   |
| ERD     | Entity Relationship Diagram         |

---

## 2. Gambaran Umum Sistem

### 2.1 Perspektif Produk

SIPOS adalah sistem informasi berbasis web yang dapat diakses melalui browser dari perangkat apapun (komputer, laptop, tablet, smartphone). Sistem ini menggantikan seluruh proses pencatatan manual di Posyandu Desa Belumbang.

### 2.2 Aktor Sistem

| Aktor       | Deskripsi                                    | Hak Akses                                                     |
| ----------- | -------------------------------------------- | ------------------------------------------------------------- |
| **Admin**   | Administrator sistem                         | Full access — kelola user, master data, laporan global        |
| **Bidan**   | Tenaga kesehatan desa                        | Input pemeriksaan, validasi jadwal, cetak laporan             |
| **Kader**   | Sukarelawan posyandu                         | Input pendaftaran peserta, input pemeriksaan, kelola jadwal   |
| **Peserta** | Ibu hamil, orang tua balita, keluarga lansia | Lihat data kesehatan pribadi, lihat jadwal, terima notifikasi |

### 2.3 Alur Kerja Utama

```
Peserta Datang → Kader Cek (Baru/Lama) → Input/Cari Data Peserta
     ↓
Kader Input Data Pemeriksaan (BB, TB, Tensi, dll.)
     ↓
Bidan Input Hasil Pemeriksaan Medis (Resep, Edukasi, dll.)
     ↓
Sistem Generate Laporan & Notifikasi Jadwal Berikutnya
     ↓
Peserta Terima KMS Digital & Notifikasi
```

---

## 3. Stack Teknologi

### 3.1 Backend

| Komponen       | Teknologi                             | Versi  |
| -------------- | ------------------------------------- | ------ |
| Runtime        | PHP                                   | 8.3+   |
| Framework      | Laravel                               | 11.x   |
| Starter Kit    | Laravel Breeze (Inertia stack)        | Latest |
| SSR Adapter    | Inertia.js                            | 2.x    |
| Database       | MariaDB                               | 11.x   |
| Cache/Queue    | Redis                                 | 7.x    |
| Search         | Laravel Scout (basic)                 | —      |
| File Storage   | Laravel Storage (local/S3-compatible) | —      |
| PDF Generation | DomPDF / Spatie Laravel PDF           | Latest |
| Testing        | Pest PHP                              | 3.x    |

### 3.2 Frontend

| Komponen      | Teknologi              | Versi                 |
| ------------- | ---------------------- | --------------------- |
| JS Framework  | Vue.js                 | 3.x (Composition API) |
| Build Tool    | Vite                   | 6.x                   |
| UI Components | shadcn-vue             | Latest                |
| Styling       | Tailwind CSS           | 4.x                   |
| State Mgmt    | Pinia                  | 3.x                   |
| Form Handling | VeeValidate + Zod      | Latest                |
| Charts        | Chart.js + vue-chartjs | Latest                |
| Icons         | Lucide Vue Next        | Latest                |
| HTTP Client   | Inertia.js (built-in)  | —                     |

### 3.3 Infrastructure

| Komponen        | Teknologi                          |
| --------------- | ---------------------------------- |
| Container       | Docker + Docker Compose            |
| Web Server      | Nginx (prod) / Artisan Serve (dev) |
| Process Manager | Supervisor (queue worker)          |
| SSL             | Let's Encrypt (prod)               |
| Env Management  | `.env` + Docker secrets            |

---

## 4. Arsitektur Proyek

### 4.1 Pendekatan Arsitektur

Sistem menggunakan **Layered Architecture** dengan pola **Repository Pattern** + **Service Layer** untuk memastikan separation of concerns, testability, dan kemudahan pengembangan di masa depan.

```
┌─────────────────────────────────────────────┐
│                  Frontend                    │
│         Vue 3 + Inertia.js + shadcn-vue      │
└─────────────────┬───────────────────────────┘
                  │ Inertia Protocol (HTTP)
┌─────────────────▼───────────────────────────┐
│              Controller Layer                │
│        (HTTP Request → Response)             │
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│               Service Layer                  │
│         (Business Logic & Orchestration)     │
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│             Repository Layer                 │
│         (Data Access Abstraction)            │
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│               Model Layer                    │
│         (Eloquent ORM + Relations)           │
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│              Database Layer                  │
│                 MariaDB                      │
└─────────────────────────────────────────────┘
```

### 4.2 Pola Desain yang Digunakan

- **Repository Pattern** — abstraksi akses data, mudah di-mock untuk testing
- **Service Layer Pattern** — logika bisnis terisolasi dari controller
- **DTO (Data Transfer Object)** — transfer data antar layer dengan type-safety
- **Observer Pattern** — event-driven untuk notifikasi dan audit log
- **Strategy Pattern** — untuk format laporan yang berbeda (PDF, Excel)
- **Policy Pattern** — RBAC menggunakan Laravel Gates & Policies

---

## 5. Kebutuhan Fungsional

### 5.1 Modul Autentikasi (AUTH)

#### AUTH-01: Login

- **Deskripsi:** Pengguna dapat masuk ke sistem menggunakan username dan password
- **Aktor:** Admin, Bidan, Kader, Peserta
- **Precondition:** Akun sudah terdaftar di sistem
- **Flow Utama:**
  1. Pengguna membuka halaman login
  2. Pengguna memasukkan username dan password
  3. Sistem memvalidasi kredensial
  4. Jika valid, sistem membuat session dan redirect ke dashboard
  5. Jika tidak valid, sistem menampilkan pesan error
- **Postcondition:** Pengguna berhasil masuk ke dashboard sesuai role

#### AUTH-02: Logout

- **Deskripsi:** Pengguna dapat keluar dari sistem
- **Flow Utama:** Pengguna klik logout → sistem menghapus session → redirect ke halaman login

#### AUTH-03: Ubah Password

- **Deskripsi:** Pengguna dapat mengubah password sendiri
- **Validasi:** Password minimal 8 karakter, konfirmasi password harus sama

---

### 5.2 Modul Master Data (MASTER)

#### MASTER-01: Kelola Data Ibu Hamil

- **Deskripsi:** Kader/Admin dapat mengelola data ibu hamil
- **Aktor:** Kader, Admin
- **Operasi:** Create, Read, Update, Delete (Soft Delete)
- **Field Data:**

| Field              | Tipe         | Keterangan                         |
| ------------------ | ------------ | ---------------------------------- |
| nik_ibu_hamil      | VARCHAR(16)  | NIK (Primary Key)                  |
| nama_ibu_hamil     | VARCHAR(100) | Nama lengkap                       |
| foto_ibu_hamil     | VARCHAR(255) | Path foto                          |
| tgl_lahir          | DATE         | Tanggal lahir                      |
| kehamilan_keberapa | TINYINT      | Kehamilan ke-n                     |
| umur_ibu_hamil     | TINYINT      | Umur saat ini                      |
| jarak_anak         | TINYINT      | Jarak dari anak sebelumnya (bulan) |
| usia_kehamilan     | TINYINT      | Usia kehamilan (minggu)            |
| no_telp            | VARCHAR(20)  | Nomor telepon                      |
| alamat             | TEXT         | Alamat lengkap                     |
| keterangan         | TEXT         | Catatan tambahan                   |

- **Validasi:**
  - NIK wajib, unik, 16 digit
  - Nama wajib, minimal 3 karakter
  - Tanggal lahir wajib, tidak boleh masa depan
  - No. Telepon minimal 10 digit

#### MASTER-02: Kelola Data Balita

- **Deskripsi:** Kader/Admin dapat mengelola data balita
- **Aktor:** Kader, Admin
- **Operasi:** Create, Read, Update, Delete (Soft Delete)
- **Field Data:**

| Field          | Tipe          | Keterangan            |
| -------------- | ------------- | --------------------- |
| nik_balita     | VARCHAR(16)   | NIK (Primary Key)     |
| nama_balita    | VARCHAR(100)  | Nama balita           |
| foto_balita    | VARCHAR(255)  | Path foto             |
| nama_orang_tua | VARCHAR(100)  | Nama ayah/ibu         |
| tgl_lahir      | DATE          | Tanggal lahir         |
| jk_balita      | ENUM('L','P') | Jenis kelamin         |
| umur_balita    | TINYINT       | Umur (bulan)          |
| no_telp        | VARCHAR(20)   | No. telepon orang tua |
| alamat         | TEXT          | Alamat                |
| keterangan     | TEXT          | Catatan               |

#### MASTER-03: Kelola Data Lansia

- **Deskripsi:** Kader/Admin dapat mengelola data lansia
- **Aktor:** Kader, Admin
- **Field Data:**

| Field       | Tipe          | Keterangan           |
| ----------- | ------------- | -------------------- |
| nik_lansia  | VARCHAR(16)   | NIK (Primary Key)    |
| nama_lansia | VARCHAR(100)  | Nama lengkap         |
| foto_lansia | VARCHAR(255)  | Path foto            |
| tgl_lahir   | DATE          | Tanggal lahir        |
| jk_lansia   | ENUM('L','P') | Jenis kelamin        |
| umur_lansia | TINYINT       | Umur                 |
| no_telp     | VARCHAR(20)   | No. telepon/keluarga |
| alamat      | TEXT          | Alamat               |
| keterangan  | TEXT          | Catatan              |

#### MASTER-04: Kelola Data Posyandu

- **Deskripsi:** Admin/Kader dapat mengelola data posyandu (jadwal & lokasi)
- **Field Data:**

| Field           | Tipe         | Keterangan               |
| --------------- | ------------ | ------------------------ |
| id_posyandu     | VARCHAR(20)  | ID unik (Primary Key)    |
| id_kader        | VARCHAR(20)  | FK → kader               |
| nama_posyandu   | VARCHAR(100) | Nama posyandu            |
| lokasi_posyandu | VARCHAR(100) | Lokasi/dusun             |
| tgl_posyandu    | DATETIME     | Tanggal & waktu kegiatan |

#### MASTER-05: Kelola Data Kader

- **Deskripsi:** Admin dapat mengelola data kader
- **Field Data:** id_kader, nama_kader, foto_kader, alamat, no_telp, jenis_kelamin, id_user

#### MASTER-06: Kelola Data Bidan

- **Deskripsi:** Admin dapat mengelola data bidan
- **Field Data:** id_bidan, nama_bidan, foto_bidan, alamat, no_telp, jenis_kelamin, id_user

---

### 5.3 Modul Pemeriksaan (PERIKSA)

#### PERIKSA-01: Input Pemeriksaan Ibu Hamil

- **Deskripsi:** Bidan/Kader dapat mencatat hasil pemeriksaan ibu hamil
- **Aktor:** Bidan, Kader
- **Field Data:**

| Field           | Tipe         | Keterangan                 |
| --------------- | ------------ | -------------------------- |
| id_pemeriksaan  | VARCHAR(20)  | ID unik                    |
| nik_ibu_hamil   | VARCHAR(16)  | FK → ibu_hamil             |
| id_posyandu     | VARCHAR(20)  | FK → posyandu              |
| tgl_pemeriksaan | DATE         | Tanggal periksa            |
| usia_kehamilan  | TINYINT      | Usia kehamilan (minggu)    |
| berat_badan     | DECIMAL(5,2) | Berat badan (kg)           |
| lila            | DECIMAL(5,2) | Lingkar lengan atas (cm)   |
| sistole         | SMALLINT     | Tekanan darah sistole      |
| diastole        | SMALLINT     | Tekanan darah diastole     |
| edukasi         | TEXT         | Catatan edukasi dari bidan |
| keterangan      | TEXT         | Catatan tambahan           |

#### PERIKSA-02: Input Pemeriksaan Balita

- **Field Data:** id_pemeriksaan, nik_balita, id_posyandu, tgl_pemeriksaan, berat_badan, tinggi_badan, lingkar_kepala, lila, sistole, diastole, obat_cacing (BOOLEAN), vitamin (VARCHAR), keterangan

#### PERIKSA-03: Input Pemeriksaan Lansia

- **Field Data:** id_pemeriksaan, nik_lansia, id_posyandu, tgl_pemeriksaan, berat_badan, tinggi_badan, lingkar_perut, sistole, diastole, jenis_keluhan, obat_vitamin, keterangan

#### PERIKSA-04: Riwayat Pemeriksaan

- **Deskripsi:** Sistem menampilkan riwayat pemeriksaan per peserta dalam bentuk tabel dan grafik
- **Grafik:** Berat badan, tekanan darah, LILA (time-series chart)

#### PERIKSA-05: Cetak Laporan Pemeriksaan

- **Deskripsi:** Sistem dapat mengekspor laporan pemeriksaan ke PDF
- **Format Laporan:**
  - Header: Logo, nama posyandu, tanggal laporan
  - Tabel: Data pemeriksaan sesuai kategori (ibu hamil/balita/lansia)
  - Footer: Tanda tangan bidan

---

### 5.4 Modul Jadwal & Notifikasi (JADWAL)

#### JADWAL-01: Tambah Jadwal Posyandu

- **Deskripsi:** Kader dapat menambahkan rencana jadwal posyandu
- **Aktor:** Kader
- **Field:** nama_posyandu, lokasi, tanggal, waktu, kader_bertugas

#### JADWAL-02: Validasi Jadwal

- **Deskripsi:** Bidan dapat memvalidasi/menolak jadwal yang diusulkan kader
- **Status Jadwal:** `draft`, `validated`, `rejected`, `completed`

#### JADWAL-03: Notifikasi Jadwal

- **Deskripsi:** Sistem mengirimkan notifikasi jadwal kepada peserta yang terdaftar
- **Channel:** In-app notification (database), opsional WhatsApp (via API eksternal)
- **Trigger:** H-3 sebelum jadwal posyandu

#### JADWAL-04: Kalender Jadwal

- **Deskripsi:** Tampilan kalender interaktif untuk semua jadwal posyandu

---

### 5.5 Modul Laporan (LAPORAN)

#### LAPORAN-01: Laporan Data Ibu Hamil

- **Format:** Tabel rekap + grafik statistik bulanan
- **Filter:** Periode (bulan/tahun), posyandu, kader

#### LAPORAN-02: Laporan Data Balita

- **Format:** Tabel rekap + grafik pertumbuhan (BB, TB)
- **Indikator:** Deteksi stunting berdasarkan BB/TB vs usia

#### LAPORAN-03: Laporan Data Lansia

- **Format:** Tabel rekap + grafik tekanan darah

#### LAPORAN-04: Laporan Bulanan Posyandu

- **Deskripsi:** Rekap kegiatan bulanan per posyandu untuk dilaporkan ke Puskesmas
- **Export:** PDF, Excel (XLSX)

#### LAPORAN-05: Dashboard Statistik

- **Deskripsi:** Dashboard dengan widget statistik real-time
- **Widget:** Total peserta aktif, jumlah kegiatan bulan ini, trend pertumbuhan, alert kesehatan

---

### 5.6 Modul Manajemen User (USER)

#### USER-01: Kelola User

- **Aktor:** Admin
- **Operasi:** Create, Read, Update, Delete (Soft Delete)
- **Field:** id_user, nama_user, username, email, password, role

#### USER-02: Assign Role

- **Role tersedia:** `admin`, `bidan`, `kader`, `peserta`
- **Setiap role memiliki permission set yang berbeda**

---

### 5.7 Modul KMS Digital (KMS)

#### KMS-01: Lihat KMS Digital

- **Deskripsi:** Peserta/orang tua dapat melihat Kartu Menuju Sehat digital
- **Konten:** Grafik pertumbuhan, riwayat kunjungan, jadwal berikutnya
- **Aktor:** Peserta (read-only)

---

## 6. Kebutuhan Non-Fungsional

### 6.1 Keamanan

| Kode   | Kebutuhan                                                                     |
| ------ | ----------------------------------------------------------------------------- |
| SEC-01 | Autentikasi menggunakan session-based authentication (Laravel Sanctum/Breeze) |
| SEC-02 | Password di-hash menggunakan bcrypt                                           |
| SEC-03 | CSRF protection pada semua form                                               |
| SEC-04 | Rate limiting pada endpoint login (5 percobaan / menit)                       |
| SEC-05 | Role-Based Access Control (RBAC) menggunakan Laravel Policies                 |
| SEC-06 | Semua input divalidasi di server-side menggunakan Laravel Form Request        |
| SEC-07 | File upload: validasi tipe MIME, ukuran maks 2MB, nama file di-random         |
| SEC-08 | SQL Injection prevention via Eloquent ORM (parameterized queries)             |
| SEC-09 | XSS prevention via Inertia.js auto-escaping                                   |
| SEC-10 | HTTPS wajib di environment production                                         |
| SEC-11 | Sensitive data (NIK) tidak di-log                                             |
| SEC-12 | Audit log untuk setiap operasi CRUD pada data sensitif                        |

### 6.2 Performa

| Kode    | Kebutuhan                                                           |
| ------- | ------------------------------------------------------------------- |
| PERF-01 | Halaman utama loading < 3 detik pada koneksi 4G                     |
| PERF-02 | API response time < 500ms untuk operasi CRUD standar                |
| PERF-03 | Database query menggunakan eager loading untuk mencegah N+1 problem |
| PERF-04 | Pagination pada semua list data (15 item per halaman default)       |
| PERF-05 | Cache laporan yang berat menggunakan Redis (TTL: 5 menit)           |
| PERF-06 | Queue untuk proses berat (generate laporan PDF, kirim notifikasi)   |
| PERF-07 | Optimasi gambar sebelum upload (max 800x800px)                      |

### 6.3 Ketersediaan

| Kode     | Kebutuhan                                             |
| -------- | ----------------------------------------------------- |
| AVAIL-01 | Sistem tersedia 24/7 kecuali maintenance terjadwal    |
| AVAIL-02 | Maintenance window: Minggu 00:00–04:00 WITA           |
| AVAIL-03 | Backup database otomatis setiap hari pukul 02:00 WITA |
| AVAIL-04 | Retensi backup: 30 hari                               |

### 6.4 Usabilitas

| Kode   | Kebutuhan                                                |
| ------ | -------------------------------------------------------- |
| USE-01 | Antarmuka responsif (mobile-first design)                |
| USE-02 | Mendukung browser modern (Chrome, Firefox, Edge, Safari) |
| USE-03 | Bahasa antarmuka: Bahasa Indonesia                       |
| USE-04 | Pesan error yang informatif dan mudah dipahami           |
| USE-05 | Konfirmasi sebelum operasi hapus data                    |
| USE-06 | Loading indicator pada setiap operasi async              |

### 6.5 Skalabilitas

| Kode     | Kebutuhan                                                          |
| -------- | ------------------------------------------------------------------ |
| SCALE-01 | Arsitektur mendukung penambahan posyandu baru tanpa perubahan kode |
| SCALE-02 | Repository pattern memungkinkan penggantian database engine        |
| SCALE-03 | Queue worker dapat di-scale horizontal                             |

---

## 7. Desain Database

### 7.1 Entity Relationship Diagram (Deskripsi)

Sistem memiliki 8 entitas utama dengan relasi berikut:

- `users` ← memiliki → `kader` (1:1)
- `users` ← memiliki → `bidan` (1:1)
- `users` ← memiliki → `ibu_hamil` (1:1, opsional)
- `users` ← memiliki → `balita` (1:1, opsional)
- `users` ← memiliki → `lansia` (1:1, opsional)
- `posyandu` ← bertugas → `kader` (M:N via `posyandu_kader`)
- `bidan` ← bertugas di → `posyandu` (M:N)
- `pemeriksaan` ← mencatat → `ibu_hamil` / `balita` / `lansia` (polimorfik)
- `jadwal_posyandu` ← milik → `posyandu` (M:1)
- `notifications` ← dikirim ke → `users`

### 7.2 Skema Tabel Lengkap

#### Tabel: `users`

```sql
CREATE TABLE users (
    id          CHAR(26) PRIMARY KEY COMMENT 'ULID',
    nama_user   VARCHAR(100) NOT NULL,
    username    VARCHAR(50) NOT NULL UNIQUE,
    email       VARCHAR(150) UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('admin', 'bidan', 'kader', 'peserta') NOT NULL DEFAULT 'peserta',
    is_active   BOOLEAN NOT NULL DEFAULT TRUE,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  TIMESTAMP NULL COMMENT 'Soft delete'
);
```

#### Tabel: `kader`

```sql
CREATE TABLE kader (
    id              CHAR(26) PRIMARY KEY COMMENT 'ULID',
    user_id         CHAR(26) NOT NULL UNIQUE,
    nama_kader      VARCHAR(100) NOT NULL,
    foto_kader      VARCHAR(255) NULL,
    alamat          TEXT NOT NULL,
    no_telp         VARCHAR(20) NOT NULL,
    jenis_kelamin   ENUM('L', 'P') NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Tabel: `bidan`

```sql
CREATE TABLE bidan (
    id              CHAR(26) PRIMARY KEY COMMENT 'ULID',
    user_id         CHAR(26) NOT NULL UNIQUE,
    nama_bidan      VARCHAR(100) NOT NULL,
    foto_bidan      VARCHAR(255) NULL,
    alamat          TEXT NOT NULL,
    no_telp         VARCHAR(20) NOT NULL,
    jenis_kelamin   ENUM('L', 'P') NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Tabel: `ibu_hamil`

```sql
CREATE TABLE ibu_hamil (
    id                  CHAR(26) PRIMARY KEY COMMENT 'ULID',
    user_id             CHAR(26) NULL COMMENT 'Akun peserta (optional)',
    nik                 VARCHAR(16) NOT NULL UNIQUE,
    nama                VARCHAR(100) NOT NULL,
    foto                VARCHAR(255) NULL,
    tgl_lahir           DATE NOT NULL,
    kehamilan_keberapa  TINYINT UNSIGNED NOT NULL DEFAULT 1,
    jarak_anak          TINYINT UNSIGNED NULL COMMENT 'Bulan',
    usia_kehamilan      TINYINT UNSIGNED NOT NULL COMMENT 'Minggu',
    no_telp             VARCHAR(20) NOT NULL,
    alamat              TEXT NOT NULL,
    keterangan          TEXT NULL,
    is_active           BOOLEAN NOT NULL DEFAULT TRUE,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at          TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_nik (nik),
    INDEX idx_is_active (is_active)
);
```

#### Tabel: `balita`

```sql
CREATE TABLE balita (
    id              CHAR(26) PRIMARY KEY COMMENT 'ULID',
    user_id         CHAR(26) NULL COMMENT 'Akun orang tua (optional)',
    nik             VARCHAR(16) NOT NULL UNIQUE,
    nama            VARCHAR(100) NOT NULL,
    foto            VARCHAR(255) NULL,
    nama_orang_tua  VARCHAR(100) NOT NULL,
    tgl_lahir       DATE NOT NULL,
    jenis_kelamin   ENUM('L', 'P') NOT NULL,
    no_telp         VARCHAR(20) NOT NULL,
    alamat          TEXT NOT NULL,
    keterangan      TEXT NULL,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_nik (nik),
    INDEX idx_tgl_lahir (tgl_lahir)
);
```

#### Tabel: `lansia`

```sql
CREATE TABLE lansia (
    id              CHAR(26) PRIMARY KEY COMMENT 'ULID',
    user_id         CHAR(26) NULL COMMENT 'Akun keluarga (optional)',
    nik             VARCHAR(16) NOT NULL UNIQUE,
    nama            VARCHAR(100) NOT NULL,
    foto            VARCHAR(255) NULL,
    tgl_lahir       DATE NOT NULL,
    jenis_kelamin   ENUM('L', 'P') NOT NULL,
    no_telp         VARCHAR(20) NULL,
    alamat          TEXT NOT NULL,
    keterangan      TEXT NULL,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_nik (nik)
);
```

#### Tabel: `posyandu`

```sql
CREATE TABLE posyandu (
    id              CHAR(26) PRIMARY KEY COMMENT 'ULID',
    nama_posyandu   VARCHAR(100) NOT NULL,
    lokasi          VARCHAR(100) NOT NULL COMMENT 'Nama dusun',
    deskripsi       TEXT NULL,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP NULL
);
```

#### Tabel: `jadwal_posyandu`

```sql
CREATE TABLE jadwal_posyandu (
    id              CHAR(26) PRIMARY KEY COMMENT 'ULID',
    posyandu_id     CHAR(26) NOT NULL,
    kader_id        CHAR(26) NOT NULL COMMENT 'Kader yang mengusulkan',
    bidan_id        CHAR(26) NULL COMMENT 'Bidan yang memvalidasi',
    tanggal         DATE NOT NULL,
    waktu_mulai     TIME NOT NULL,
    waktu_selesai   TIME NULL,
    status          ENUM('draft','validated','rejected','completed') NOT NULL DEFAULT 'draft',
    catatan_bidan   TEXT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP NULL,
    FOREIGN KEY (posyandu_id) REFERENCES posyandu(id),
    FOREIGN KEY (kader_id) REFERENCES kader(id),
    FOREIGN KEY (bidan_id) REFERENCES bidan(id),
    INDEX idx_tanggal (tanggal),
    INDEX idx_status (status)
);
```

#### Tabel: `pemeriksaan` (Polymorphic)

```sql
CREATE TABLE pemeriksaan (
    id                  CHAR(26) PRIMARY KEY COMMENT 'ULID',
    jadwal_posyandu_id  CHAR(26) NOT NULL,
    kader_id            CHAR(26) NULL COMMENT 'Kader yang menginput',
    bidan_id            CHAR(26) NULL COMMENT 'Bidan yang memeriksa',
    peserta_type        ENUM('ibu_hamil', 'balita', 'lansia') NOT NULL,
    peserta_id          CHAR(26) NOT NULL,
    tgl_pemeriksaan     DATE NOT NULL,

    -- Vital Signs (semua kategori)
    berat_badan         DECIMAL(5,2) NULL COMMENT 'kg',
    tinggi_badan        DECIMAL(5,2) NULL COMMENT 'cm',
    sistole             SMALLINT UNSIGNED NULL COMMENT 'mmHg',
    diastole            SMALLINT UNSIGNED NULL COMMENT 'mmHg',

    -- Khusus Ibu Hamil
    usia_kehamilan      TINYINT UNSIGNED NULL COMMENT 'Minggu',
    lila                DECIMAL(5,2) NULL COMMENT 'Lingkar Lengan Atas (cm)',
    kek_status          BOOLEAN NULL COMMENT 'Status KEK',
    mt_bumil            BOOLEAN NULL COMMENT 'Pemberian MT Bumil',
    ttd_status          BOOLEAN NULL COMMENT 'Konsumsi TTD',
    kelas_bumil         BOOLEAN NULL COMMENT 'Mengikuti kelas bumil',

    -- Khusus Balita
    lingkar_kepala      DECIMAL(5,2) NULL COMMENT 'cm',
    lingkar_lengan      DECIMAL(5,2) NULL COMMENT 'cm',
    obat_cacing         BOOLEAN NULL,
    vitamin             VARCHAR(100) NULL,

    -- Khusus Lansia
    lingkar_perut       DECIMAL(5,2) NULL COMMENT 'cm',
    jenis_keluhan       VARCHAR(255) NULL,
    obat_vitamin        VARCHAR(255) NULL,

    -- Edukasi & Catatan
    edukasi             TEXT NULL COMMENT 'Catatan edukasi dari bidan',
    keterangan          TEXT NULL,
    hadir               BOOLEAN NOT NULL DEFAULT TRUE,

    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at          TIMESTAMP NULL,

    FOREIGN KEY (jadwal_posyandu_id) REFERENCES jadwal_posyandu(id),
    FOREIGN KEY (kader_id) REFERENCES kader(id),
    FOREIGN KEY (bidan_id) REFERENCES bidan(id),
    INDEX idx_peserta (peserta_type, peserta_id),
    INDEX idx_tgl (tgl_pemeriksaan),
    INDEX idx_jadwal (jadwal_posyandu_id)
);
```

#### Tabel: `notifications`

```sql
CREATE TABLE notifications (
    id          CHAR(36) PRIMARY KEY COMMENT 'UUID (Laravel default)',
    type        VARCHAR(255) NOT NULL,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id   CHAR(26) NOT NULL,
    data        JSON NOT NULL,
    read_at     TIMESTAMP NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_notifiable (notifiable_type, notifiable_id, read_at)
);
```

#### Tabel: `audit_logs`

```sql
CREATE TABLE audit_logs (
    id              CHAR(26) PRIMARY KEY COMMENT 'ULID',
    user_id         CHAR(26) NULL,
    event           VARCHAR(50) NOT NULL COMMENT 'created, updated, deleted',
    auditable_type  VARCHAR(255) NOT NULL,
    auditable_id    CHAR(26) NOT NULL,
    old_values      JSON NULL,
    new_values      JSON NULL,
    ip_address      VARCHAR(45) NULL,
    user_agent      TEXT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_auditable (auditable_type, auditable_id),
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
);
```

---

## 8. API Endpoint

Semua route menggunakan Inertia.js (bukan REST API murni), namun untuk keperluan export/print, beberapa endpoint mengembalikan response langsung.

### 8.1 Autentikasi

```
POST   /login                    → Auth: Login
POST   /logout                   → Auth: Logout
GET    /forgot-password          → Auth: Halaman forgot password
POST   /forgot-password          → Auth: Kirim reset link
POST   /reset-password           → Auth: Reset password
```

### 8.2 Dashboard

```
GET    /dashboard                → Dashboard: Halaman utama
```

### 8.3 Master Data — Ibu Hamil

```
GET    /ibu-hamil                → List ibu hamil (paginated, searchable)
GET    /ibu-hamil/create         → Form tambah
POST   /ibu-hamil                → Simpan data baru
GET    /ibu-hamil/{id}           → Detail + riwayat pemeriksaan + grafik
GET    /ibu-hamil/{id}/edit      → Form edit
PUT    /ibu-hamil/{id}           → Update data
DELETE /ibu-hamil/{id}           → Soft delete
GET    /ibu-hamil/{id}/print     → Cetak KMS (PDF)
```

### 8.4 Master Data — Balita

```
GET    /balita                   → List balita
GET    /balita/create            → Form tambah
POST   /balita                   → Simpan
GET    /balita/{id}              → Detail + grafik pertumbuhan
GET    /balita/{id}/edit         → Form edit
PUT    /balita/{id}              → Update
DELETE /balita/{id}              → Soft delete
GET    /balita/{id}/print        → Cetak KMS (PDF)
```

### 8.5 Master Data — Lansia

```
GET    /lansia                   → List lansia
GET    /lansia/create            → Form tambah
POST   /lansia                   → Simpan
GET    /lansia/{id}              → Detail + riwayat
GET    /lansia/{id}/edit         → Form edit
PUT    /lansia/{id}              → Update
DELETE /lansia/{id}              → Soft delete
```

### 8.6 Pemeriksaan

```
GET    /pemeriksaan              → List semua pemeriksaan (filter: tipe, tanggal)
GET    /pemeriksaan/create       → Form pemeriksaan baru
POST   /pemeriksaan              → Simpan pemeriksaan
GET    /pemeriksaan/{id}         → Detail pemeriksaan
GET    /pemeriksaan/{id}/edit    → Form edit
PUT    /pemeriksaan/{id}         → Update
DELETE /pemeriksaan/{id}         → Soft delete
```

### 8.7 Jadwal Posyandu

```
GET    /jadwal                   → Kalender jadwal
GET    /jadwal/create            → Form jadwal baru
POST   /jadwal                   → Simpan jadwal
GET    /jadwal/{id}              → Detail jadwal
GET    /jadwal/{id}/edit         → Form edit
PUT    /jadwal/{id}              → Update
DELETE /jadwal/{id}              → Soft delete
POST   /jadwal/{id}/validate     → Bidan: Validasi jadwal
POST   /jadwal/{id}/reject       → Bidan: Tolak jadwal
POST   /jadwal/{id}/complete     → Tandai selesai
```

### 8.8 Laporan

```
GET    /laporan                  → Halaman pilihan laporan
GET    /laporan/ibu-hamil        → Laporan ibu hamil (filter periode)
GET    /laporan/balita           → Laporan balita
GET    /laporan/lansia           → Laporan lansia
GET    /laporan/bulanan          → Laporan bulanan posyandu
POST   /laporan/export/pdf       → Export laporan ke PDF
POST   /laporan/export/excel     → Export laporan ke Excel
```

### 8.9 Manajemen User (Admin)

```
GET    /users                    → List users
GET    /users/create             → Form user baru
POST   /users                    → Simpan user
GET    /users/{id}/edit          → Form edit
PUT    /users/{id}               → Update
DELETE /users/{id}               → Soft delete
```

### 8.10 Notifikasi

```
GET    /notifications            → List notifikasi user
POST   /notifications/{id}/read  → Tandai sudah dibaca
POST   /notifications/read-all   → Tandai semua dibaca
```

---

## 9. Autentikasi & Otorisasi

### 9.1 Permission Matrix

| Fitur              | Admin | Bidan | Kader | Peserta |
| ------------------ | ----- | ----- | ----- | ------- |
| Kelola User        | ✅    | ❌    | ❌    | ❌      |
| Kelola Kader       | ✅    | ❌    | ❌    | ❌      |
| Kelola Bidan       | ✅    | ❌    | ❌    | ❌      |
| Kelola Posyandu    | ✅    | ❌    | ❌    | ❌      |
| CRUD Ibu Hamil     | ✅    | ✅    | ✅    | ❌      |
| CRUD Balita        | ✅    | ✅    | ✅    | ❌      |
| CRUD Lansia        | ✅    | ✅    | ✅    | ❌      |
| Input Pemeriksaan  | ✅    | ✅    | ✅    | ❌      |
| Validasi Jadwal    | ✅    | ✅    | ❌    | ❌      |
| Buat Jadwal        | ✅    | ✅    | ✅    | ❌      |
| Lihat Laporan      | ✅    | ✅    | ✅    | ❌      |
| Cetak Laporan      | ✅    | ✅    | ✅    | ❌      |
| Lihat Data Sendiri | ✅    | ✅    | ✅    | ✅      |
| Lihat KMS Digital  | ✅    | ✅    | ✅    | ✅      |
| Audit Log          | ✅    | ❌    | ❌    | ❌      |

### 9.2 Implementasi Laravel Policies

Setiap resource memiliki Policy class:

- `IbuHamilPolicy`
- `BalitaPolicy`
- `LansiaPolicy`
- `PemeriksaanPolicy`
- `JadwalPosyanduPolicy`
- `UserPolicy`
- `LaporanPolicy`

---

## 10. Docker Configuration

### 10.1 Service Architecture

```
┌─────────────────────────────────────────┐
│           Docker Compose                 │
│                                         │
│  ┌──────────┐  ┌──────────┐            │
│  │  nginx   │  │   app    │            │
│  │ :80/:443 │──│ php-fpm  │            │
│  └──────────┘  └────┬─────┘            │
│                     │                   │
│  ┌──────────┐  ┌────▼─────┐            │
│  │  redis   │  │ mariadb  │            │
│  │  :6379   │  │  :3306   │            │
│  └──────────┘  └──────────┘            │
│                                         │
│  ┌──────────┐  ┌──────────┐            │
│  │ worker   │  │scheduler │            │
│  │ (queue)  │  │ (cron)   │            │
│  └──────────┘  └──────────┘            │
└─────────────────────────────────────────┘
```

### 10.2 Environment: Development (`docker-compose.dev.yml`)

```yaml
name: sipos-dev

services:
  app:
    build:
      context: .
      dockerfile: docker/dev/Dockerfile
    container_name: sipos_app_dev
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
      - ./docker/dev/php.ini:/usr/local/etc/php/conf.d/custom.ini
    environment:
      - APP_ENV=local
      - APP_DEBUG=true
      - XDEBUG_MODE=develop,debug
    networks:
      - sipos_network
    depends_on:
      - mariadb
      - redis

  nginx:
    image: nginx:1.25-alpine
    container_name: sipos_nginx_dev
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
      - ./docker/dev/nginx.conf:/etc/nginx/conf.d/default.conf
    networks:
      - sipos_network
    depends_on:
      - app

  mariadb:
    image: mariadb:11
    container_name: sipos_db_dev
    restart: unless-stopped
    environment:
      MARIADB_ROOT_PASSWORD: secret
      MARIADB_DATABASE: sipos_dev
      MARIADB_USER: sipos
      MARIADB_PASSWORD: sipos_password
    volumes:
      - sipos_db_dev_data:/var/lib/mysql
      - ./docker/mariadb/init.sql:/docker-entrypoint-initdb.d/init.sql
    ports:
      - "3307:3306" # Expose untuk akses via DB client
    networks:
      - sipos_network

  redis:
    image: redis:7-alpine
    container_name: sipos_redis_dev
    restart: unless-stopped
    ports:
      - "6380:6379" # Expose untuk debugging
    networks:
      - sipos_network

  worker:
    build:
      context: .
      dockerfile: docker/dev/Dockerfile
    container_name: sipos_worker_dev
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
    command: php artisan queue:work redis --verbose --tries=3 --timeout=90
    networks:
      - sipos_network
    depends_on:
      - mariadb
      - redis

  # Mailpit untuk development email testing
  mailpit:
    image: axllent/mailpit:latest
    container_name: sipos_mailpit_dev
    restart: unless-stopped
    ports:
      - "8025:8025" # Web UI
      - "1025:1025" # SMTP
    networks:
      - sipos_network

volumes:
  sipos_db_dev_data:

networks:
  sipos_network:
    driver: bridge
```

### 10.3 Environment: Production (`docker-compose.prod.yml`)

```yaml
name: sipos-prod

services:
  app:
    build:
      context: .
      dockerfile: docker/prod/Dockerfile
    container_name: sipos_app
    restart: always
    working_dir: /var/www/html
    volumes:
      - sipos_storage:/var/www/html/storage
      - ./docker/prod/php.ini:/usr/local/etc/php/conf.d/custom.ini
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    env_file:
      - .env.production
    networks:
      - sipos_network
    depends_on:
      - mariadb
      - redis

  nginx:
    image: nginx:1.25-alpine
    container_name: sipos_nginx
    restart: always
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www/html:ro
      - sipos_storage:/var/www/html/storage:ro
      - ./docker/prod/nginx.conf:/etc/nginx/conf.d/default.conf
      - ./docker/prod/ssl:/etc/nginx/ssl:ro
    networks:
      - sipos_network
    depends_on:
      - app

  mariadb:
    image: mariadb:11
    container_name: sipos_db
    restart: always
    environment:
      MARIADB_ROOT_PASSWORD_FILE: /run/secrets/db_root_password
      MARIADB_DATABASE: sipos_prod
      MARIADB_USER: sipos
      MARIADB_PASSWORD_FILE: /run/secrets/db_password
    secrets:
      - db_root_password
      - db_password
    volumes:
      - sipos_db_data:/var/lib/mysql
    networks:
      - sipos_network
    # TIDAK expose port ke host di production

  redis:
    image: redis:7-alpine
    container_name: sipos_redis
    restart: always
    command: redis-server --requirepass ${REDIS_PASSWORD}
    volumes:
      - sipos_redis_data:/data
    networks:
      - sipos_network

  worker:
    build:
      context: .
      dockerfile: docker/prod/Dockerfile
    container_name: sipos_worker
    restart: always
    working_dir: /var/www/html
    command: php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
    env_file:
      - .env.production
    networks:
      - sipos_network
    depends_on:
      - mariadb
      - redis

  scheduler:
    build:
      context: .
      dockerfile: docker/prod/Dockerfile
    container_name: sipos_scheduler
    restart: always
    working_dir: /var/www/html
    command: sh -c "while true; do php artisan schedule:run --verbose --no-interaction; sleep 60; done"
    env_file:
      - .env.production
    networks:
      - sipos_network
    depends_on:
      - mariadb
      - redis

secrets:
  db_root_password:
    file: ./docker/secrets/db_root_password.txt
  db_password:
    file: ./docker/secrets/db_password.txt

volumes:
  sipos_db_data:
  sipos_storage:
  sipos_redis_data:

networks:
  sipos_network:
    driver: bridge
```

### 10.4 Dockerfile Development

```dockerfile
# docker/dev/Dockerfile
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache

# Install Redis extension
RUN pecl install redis xdebug && \
    docker-php-ext-enable redis xdebug

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Xdebug config
COPY docker/dev/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

EXPOSE 9000
```

### 10.5 Dockerfile Production

```dockerfile
# docker/prod/Dockerfile
FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache

RUN pecl install redis && docker-php-ext-enable redis

# Production OPcache settings
COPY docker/prod/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Build stage untuk assets
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Final production stage
FROM base AS production
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
COPY --from=node-builder /app/public/build ./public/build

RUN composer install --optimize-autoloader --no-dev --no-interaction

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

RUN chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

---

## 11. Unit Testing Strategy

### 11.1 Framework Testing

- **Pest PHP 3.x** — test runner utama (lebih expressive dari PHPUnit)
- **Laravel HTTP Tests** — testing controller via Inertia assertions
- **Laravel Database Testing** — RefreshDatabase trait, factories, seeders
- **Mockery** — mocking dependencies (repositories, services)

### 11.2 Kategori Test

#### A. Unit Tests (`tests/Unit/`)

Test terisolasi pada class tunggal tanpa database/HTTP.

```
tests/Unit/
├── Services/
│   ├── PemeriksaanServiceTest.php
│   ├── LaporanServiceTest.php
│   └── NotifikasiServiceTest.php
├── DTOs/
│   ├── CreateIbuHamilDTOTest.php
│   └── CreatePemeriksaanDTOTest.php
└── Helpers/
    └── UmurHelperTest.php
```

**Contoh test service:**

```php
// tests/Unit/Services/PemeriksaanServiceTest.php
it('can calculate umur balita in months', function () {
    $tglLahir = now()->subMonths(18)->toDateString();
    $umur = UmurHelper::hitungUmurBalita($tglLahir);
    expect($umur)->toBe(18);
});

it('can detect KEK status from LILA measurement', function () {
    $service = new PemeriksaanService(mock(PemeriksaanRepository::class));
    expect($service->isKEK(22.5))->toBeTrue();
    expect($service->isKEK(24.0))->toBeFalse();
});
```

#### B. Feature Tests (`tests/Feature/`)

Test end-to-end melibatkan HTTP, database, dan autentikasi.

```
tests/Feature/
├── Auth/
│   ├── LoginTest.php
│   └── LogoutTest.php
├── IbuHamil/
│   ├── CreateIbuHamilTest.php
│   ├── UpdateIbuHamilTest.php
│   ├── DeleteIbuHamilTest.php
│   └── ListIbuHamilTest.php
├── Balita/
│   ├── CreateBalitaTest.php
│   └── ...
├── Lansia/
│   └── ...
├── Pemeriksaan/
│   ├── CreatePemeriksaanTest.php
│   └── LaporanPemeriksaanTest.php
├── Jadwal/
│   ├── CreateJadwalTest.php
│   └── ValidasiJadwalTest.php
└── Laporan/
    └── ExportLaporanTest.php
```

**Contoh feature test:**

```php
// tests/Feature/IbuHamil/CreateIbuHamilTest.php
it('kader can create ibu hamil', function () {
    $kader = User::factory()->kader()->create();

    $response = $this->actingAs($kader)
        ->post('/ibu-hamil', [
            'nik' => '5101234567890001',
            'nama' => 'Ni Wayan Sari',
            'tgl_lahir' => '1995-06-15',
            'kehamilan_keberapa' => 2,
            'usia_kehamilan' => 20,
            'no_telp' => '08123456789',
            'alamat' => 'Dusun Belumbang',
        ]);

    $response->assertRedirect('/ibu-hamil');
    $this->assertDatabaseHas('ibu_hamil', ['nik' => '5101234567890001']);
});

it('peserta cannot create ibu hamil', function () {
    $peserta = User::factory()->peserta()->create();

    $this->actingAs($peserta)
        ->post('/ibu-hamil', [...])
        ->assertForbidden();
});

it('validates NIK must be 16 digits', function () {
    $kader = User::factory()->kader()->create();

    $this->actingAs($kader)
        ->post('/ibu-hamil', ['nik' => '123'])
        ->assertSessionHasErrors('nik');
});
```

### 11.3 Coverage Target

| Kategori         | Target Coverage |
| ---------------- | --------------- |
| Service Layer    | ≥ 90%           |
| Repository Layer | ≥ 85%           |
| Controller Layer | ≥ 80%           |
| Model Layer      | ≥ 75%           |
| Overall          | ≥ 80%           |

### 11.4 Test Commands

```bash
# Jalankan semua test
php artisan test

# Jalankan dengan coverage (Pest)
php artisan test --coverage --min=80

# Jalankan test spesifik
php artisan test tests/Feature/IbuHamil/

# Jalankan unit test saja
php artisan test --testsuite=Unit

# Parallel testing (lebih cepat)
php artisan test --parallel
```

### 11.5 Database Factories

Setiap model memiliki factory untuk kebutuhan testing:

- `UserFactory` (dengan state: `->kader()`, `->bidan()`, `->peserta()`, `->admin()`)
- `IbuHamilFactory`
- `BalitaFactory`
- `LansiaFactory`
- `PosyanduFactory`
- `JadwalPosyanduFactory`
- `PemeriksaanFactory`

---

## 12. Best Practices & Coding Standards

### 12.1 Laravel Best Practices

#### Repository Pattern

```php
// app/Repositories/Interfaces/IbuHamilRepositoryInterface.php
interface IbuHamilRepositoryInterface
{
    public function all(array $filters = []): LengthAwarePaginator;
    public function findById(string $id): IbuHamil;
    public function findByNik(string $nik): ?IbuHamil;
    public function create(CreateIbuHamilDTO $dto): IbuHamil;
    public function update(string $id, UpdateIbuHamilDTO $dto): IbuHamil;
    public function delete(string $id): bool;
}

// app/Repositories/Eloquent/IbuHamilRepository.php
class IbuHamilRepository implements IbuHamilRepositoryInterface
{
    public function __construct(
        private readonly IbuHamil $model
    ) {}

    public function all(array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->when($filters['search'] ?? null, fn ($q, $s) =>
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%")
            )
            ->when($filters['is_active'] ?? null, fn ($q, $a) =>
                $q->where('is_active', $a)
            )
            ->orderByDesc('created_at')
            ->paginate(15);
    }
}
```

#### Service Layer

```php
// app/Services/IbuHamilService.php
class IbuHamilService
{
    public function __construct(
        private readonly IbuHamilRepositoryInterface $repository,
        private readonly FileUploadService $fileUpload,
    ) {}

    public function create(CreateIbuHamilDTO $dto): IbuHamil
    {
        // Business logic here
        $ibuHamil = $this->repository->create($dto);

        event(new IbuHamilCreated($ibuHamil));

        return $ibuHamil;
    }
}
```

#### DTO (Data Transfer Object)

```php
// app/DTOs/CreateIbuHamilDTO.php
readonly class CreateIbuHamilDTO
{
    public function __construct(
        public string $nik,
        public string $nama,
        public string $tglLahir,
        public int $kehamilanKeberapa,
        public int $usiaKehamilan,
        public string $noTelp,
        public string $alamat,
        public ?int $jarakAnak = null,
        public ?string $keterangan = null,
        public ?UploadedFile $foto = null,
    ) {}

    public static function fromRequest(StoreIbuHamilRequest $request): self
    {
        return new self(
            nik: $request->validated('nik'),
            nama: $request->validated('nama'),
            tglLahir: $request->validated('tgl_lahir'),
            kehamilanKeberapa: $request->validated('kehamilan_keberapa'),
            usiaKehamilan: $request->validated('usia_kehamilan'),
            noTelp: $request->validated('no_telp'),
            alamat: $request->validated('alamat'),
            jarakAnak: $request->validated('jarak_anak'),
            keterangan: $request->validated('keterangan'),
            foto: $request->file('foto'),
        );
    }
}
```

#### Form Request Validation

```php
// app/Http/Requests/StoreIbuHamilRequest.php
class StoreIbuHamilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', IbuHamil::class);
    }

    public function rules(): array
    {
        return [
            'nik'                    => ['required', 'string', 'digits:16', 'unique:ibu_hamil,nik'],
            'nama'                   => ['required', 'string', 'min:3', 'max:100'],
            'tgl_lahir'              => ['required', 'date', 'before:today'],
            'kehamilan_keberapa'     => ['required', 'integer', 'min:1', 'max:20'],
            'usia_kehamilan'         => ['required', 'integer', 'min:1', 'max:42'],
            'jarak_anak'             => ['nullable', 'integer', 'min:0'],
            'no_telp'                => ['required', 'string', 'min:10', 'max:20'],
            'alamat'                 => ['required', 'string'],
            'keterangan'             => ['nullable', 'string'],
            'foto'                   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
```

#### Inertia Controller

```php
// app/Http/Controllers/IbuHamilController.php
class IbuHamilController extends Controller
{
    public function __construct(
        private readonly IbuHamilService $service,
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', IbuHamil::class);

        $ibuHamil = $this->service->paginate($request->only(['search', 'per_page']));

        return Inertia::render('IbuHamil/Index', [
            'ibuHamil' => IbuHamilResource::collection($ibuHamil),
            'filters'  => $request->only(['search']),
        ]);
    }

    public function store(StoreIbuHamilRequest $request): RedirectResponse
    {
        $dto = CreateIbuHamilDTO::fromRequest($request);
        $this->service->create($dto);

        return redirect()
            ->route('ibu-hamil.index')
            ->with('success', 'Data ibu hamil berhasil disimpan.');
    }
}
```

### 12.2 Vue.js Best Practices (Composition API)

```vue
<!-- resources/js/Pages/IbuHamil/Index.vue -->
<script setup lang="ts">
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { useDebounceFn } from "@vueuse/core";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import DataTable from "@/Components/DataTable.vue";
import type { IbuHamil, PaginatedResponse } from "@/types";

interface Props {
  ibuHamil: PaginatedResponse<IbuHamil>;
  filters: { search?: string };
}

const props = defineProps<Props>();

const search = ref(props.filters.search ?? "");

const debouncedSearch = useDebounceFn(() => {
  router.get(
    route("ibu-hamil.index"),
    { search: search.value },
    {
      preserveState: true,
      replace: true,
    },
  );
}, 300);

watch(search, debouncedSearch);
</script>

<template>
  <AppLayout>
    <Head title="Data Ibu Hamil" />
    <!-- Template content -->
  </AppLayout>
</template>
```

### 12.3 Naming Conventions

| Konteks             | Convention                    | Contoh             |
| ------------------- | ----------------------------- | ------------------ |
| PHP Class           | PascalCase                    | `IbuHamilService`  |
| PHP Method/Variable | camelCase                     | `createIbuHamil()` |
| Database Table      | snake_case plural             | `ibu_hamil`        |
| Database Column     | snake_case                    | `tgl_lahir`        |
| Laravel Route       | kebab-case                    | `/ibu-hamil`       |
| Vue Component       | PascalCase                    | `DataTable.vue`    |
| Vue Composable      | camelCase + `use` prefix      | `useIbuHamil.ts`   |
| TypeScript Type     | PascalCase                    | `IbuHamil`         |
| CSS Class           | kebab-case (Tailwind utility) | `text-gray-900`    |

### 12.4 Commit Convention (Conventional Commits)

```
feat(ibu-hamil): add create form with validation
fix(auth): resolve session timeout issue
docs(srs): update database schema
test(pemeriksaan): add service unit tests
refactor(repo): extract base repository class
chore(docker): update mariadb to v11
```

---

## 13. Struktur Direktori Proyek

```
sipos/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SendJadwalNotification.php
│   ├── DTOs/
│   │   ├── CreateIbuHamilDTO.php
│   │   ├── UpdateIbuHamilDTO.php
│   │   ├── CreateBalitaDTO.php
│   │   ├── CreateLansiaDTO.php
│   │   └── CreatePemeriksaanDTO.php
│   ├── Enums/
│   │   ├── UserRole.php
│   │   ├── PesertaType.php
│   │   └── JadwalStatus.php
│   ├── Events/
│   │   ├── IbuHamilCreated.php
│   │   ├── PemeriksaanRecorded.php
│   │   └── JadwalValidated.php
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── IbuHamilController.php
│   │   │   ├── BalitaController.php
│   │   │   ├── LansiaController.php
│   │   │   ├── PemeriksaanController.php
│   │   │   ├── JadwalPosyanduController.php
│   │   │   ├── LaporanController.php
│   │   │   ├── NotifikasiController.php
│   │   │   └── UserController.php
│   │   ├── Middleware/
│   │   │   └── EnsureUserIsActive.php
│   │   ├── Requests/
│   │   │   ├── StoreIbuHamilRequest.php
│   │   │   ├── UpdateIbuHamilRequest.php
│   │   │   ├── StorePemeriksaanRequest.php
│   │   │   └── ...
│   │   └── Resources/
│   │       ├── IbuHamilResource.php
│   │       ├── BalitaResource.php
│   │       └── PemeriksaanResource.php
│   ├── Listeners/
│   │   ├── SendJadwalNotification.php
│   │   └── LogAuditTrail.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Kader.php
│   │   ├── Bidan.php
│   │   ├── IbuHamil.php
│   │   ├── Balita.php
│   │   ├── Lansia.php
│   │   ├── Posyandu.php
│   │   ├── JadwalPosyandu.php
│   │   ├── Pemeriksaan.php
│   │   ├── Notification.php
│   │   └── AuditLog.php
│   ├── Notifications/
│   │   └── JadwalPosyanduNotification.php
│   ├── Observers/
│   │   └── AuditObserver.php
│   ├── Policies/
│   │   ├── IbuHamilPolicy.php
│   │   ├── BalitaPolicy.php
│   │   ├── LansiaPolicy.php
│   │   ├── PemeriksaanPolicy.php
│   │   └── JadwalPosyanduPolicy.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── RepositoryServiceProvider.php
│   ├── Repositories/
│   │   ├── Interfaces/
│   │   │   ├── IbuHamilRepositoryInterface.php
│   │   │   ├── BalitaRepositoryInterface.php
│   │   │   └── ...
│   │   └── Eloquent/
│   │       ├── BaseRepository.php
│   │       ├── IbuHamilRepository.php
│   │       ├── BalitaRepository.php
│   │       └── ...
│   └── Services/
│       ├── IbuHamilService.php
│       ├── BalitaService.php
│       ├── LansiaService.php
│       ├── PemeriksaanService.php
│       ├── JadwalService.php
│       ├── LaporanService.php
│       ├── NotifikasiService.php
│       └── FileUploadService.php
│
├── bootstrap/
│
├── config/
│   └── sipos.php          # Config aplikasi spesifik
│
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── IbuHamilFactory.php
│   │   └── ...
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_kader_table.php
│   │   └── ...
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── PosyanduSeeder.php
│       └── DemoDataSeeder.php
│
├── docker/
│   ├── dev/
│   │   ├── Dockerfile
│   │   ├── nginx.conf
│   │   ├── php.ini
│   │   └── xdebug.ini
│   ├── prod/
│   │   ├── Dockerfile
│   │   ├── nginx.conf
│   │   ├── php.ini
│   │   └── opcache.ini
│   ├── mariadb/
│   │   └── init.sql
│   └── secrets/              # Gitignored
│       ├── db_root_password.txt
│       └── db_password.txt
│
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   ├── ui/            # shadcn-vue components
│   │   │   ├── DataTable.vue
│   │   │   ├── Charts/
│   │   │   │   ├── LineChart.vue
│   │   │   │   └── BarChart.vue
│   │   │   └── Shared/
│   │   ├── Composables/
│   │   │   ├── useConfirm.ts
│   │   │   └── useToast.ts
│   │   ├── Layouts/
│   │   │   ├── AppLayout.vue
│   │   │   └── AuthLayout.vue
│   │   ├── Pages/
│   │   │   ├── Auth/
│   │   │   │   └── Login.vue
│   │   │   ├── Dashboard.vue
│   │   │   ├── IbuHamil/
│   │   │   │   ├── Index.vue
│   │   │   │   ├── Create.vue
│   │   │   │   ├── Edit.vue
│   │   │   │   └── Show.vue
│   │   │   ├── Balita/
│   │   │   ├── Lansia/
│   │   │   ├── Pemeriksaan/
│   │   │   ├── Jadwal/
│   │   │   ├── Laporan/
│   │   │   └── Users/
│   │   ├── stores/            # Pinia stores
│   │   │   └── useNotificationStore.ts
│   │   └── types/
│   │       └── index.d.ts
│   └── views/
│       └── app.blade.php
│
├── routes/
│   ├── web.php
│   └── auth.php
│
├── storage/
│
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   ├── IbuHamil/
│   │   ├── Balita/
│   │   ├── Lansia/
│   │   ├── Pemeriksaan/
│   │   ├── Jadwal/
│   │   └── Laporan/
│   └── Unit/
│       ├── Services/
│       ├── DTOs/
│       └── Helpers/
│
├── docker-compose.dev.yml
├── docker-compose.prod.yml
├── .env.example
├── .env.testing
├── phpunit.xml
├── pint.json              # Laravel Pint (PHP CS Fixer)
├── phpstan.neon           # PHPStan static analysis
└── vite.config.ts
```

---

## 14. Deployment & CI/CD

### 14.1 Environment Variables (.env.example)

```dotenv
APP_NAME="SIPOS - Sistem Informasi Posyandu"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080
APP_LOCALE=id
APP_TIMEZONE=Asia/Makassar

# Database
DB_CONNECTION=mariadb
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=sipos_dev
DB_USERNAME=sipos
DB_PASSWORD=

# Cache & Queue
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail (Dev: Mailpit)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS=noreply@sipos.desa-belumbang.id
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=local
# Untuk production: FILESYSTEM_DISK=s3

# Vite (Frontend)
VITE_APP_NAME="${APP_NAME}"
```

### 14.2 Makefile — Developer Commands

```makefile
# Makefile
.PHONY: dev prod test

## Development
dev-up:
	docker compose -f docker-compose.dev.yml up -d

dev-down:
	docker compose -f docker-compose.dev.yml down

dev-build:
	docker compose -f docker-compose.dev.yml build

dev-shell:
	docker exec -it sipos_app_dev sh

dev-logs:
	docker compose -f docker-compose.dev.yml logs -f

## Artisan Shortcuts
migrate:
	docker exec sipos_app_dev php artisan migrate

migrate-fresh:
	docker exec sipos_app_dev php artisan migrate:fresh --seed

tinker:
	docker exec -it sipos_app_dev php artisan tinker

## Frontend
npm-dev:
	docker exec sipos_app_dev npm run dev

npm-build:
	docker exec sipos_app_dev npm run build

## Testing
test:
	docker exec sipos_app_dev php artisan test

test-coverage:
	docker exec sipos_app_dev php artisan test --coverage --min=80

test-unit:
	docker exec sipos_app_dev php artisan test --testsuite=Unit

## Code Quality
lint:
	docker exec sipos_app_dev ./vendor/bin/pint

analyse:
	docker exec sipos_app_dev ./vendor/bin/phpstan analyse

## Production
prod-up:
	docker compose -f docker-compose.prod.yml up -d

prod-deploy:
	docker compose -f docker-compose.prod.yml pull
	docker compose -f docker-compose.prod.yml up -d --build
	docker exec sipos_app php artisan migrate --force
	docker exec sipos_app php artisan optimize
```

### 14.3 CI/CD Pipeline (GitHub Actions)

```yaml
# .github/workflows/ci.yml
name: CI

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      mariadb:
        image: mariadb:11
        env:
          MARIADB_ROOT_PASSWORD: secret
          MARIADB_DATABASE: sipos_test
        options: --health-cmd="mariadb-admin ping" --health-interval=10s

      redis:
        image: redis:7-alpine

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: "8.3"
          extensions: pdo, pdo_mysql, redis, gd, mbstring, bcmath
          coverage: xdebug

      - name: Install PHP dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: "20"
          cache: "npm"

      - name: Install Node dependencies
        run: npm ci

      - name: Build assets
        run: npm run build

      - name: Copy env file
        run: cp .env.testing .env

      - name: Generate app key
        run: php artisan key:generate

      - name: Run migrations
        run: php artisan migrate --force

      - name: Run tests with coverage
        run: php artisan test --coverage --min=80

      - name: Run static analysis
        run: ./vendor/bin/phpstan analyse --no-progress

      - name: Run code style check
        run: ./vendor/bin/pint --test
```

---

## 15. Glossary

| Istilah        | Definisi                                                                |
| -------------- | ----------------------------------------------------------------------- |
| **Posyandu**   | Pos Pelayanan Terpadu — layanan kesehatan masyarakat berbasis komunitas |
| **ILP**        | Integrasi Layanan Primer — model posyandu holistik                      |
| **HIU**        | Holistik Integrasi dan Unggul — varian ILP di Desa Belumbang            |
| **Kader**      | Sukarelawan masyarakat yang terlatih mengelola posyandu                 |
| **Bidan**      | Tenaga kesehatan profesional yang mengawasi posyandu                    |
| **KMS**        | Kartu Menuju Sehat — kartu rekam pertumbuhan peserta                    |
| **KEK**        | Kekurangan Energi Kronik — kondisi LILA < 23.5 cm                       |
| **TTD**        | Tablet Tambah Darah — suplemen zat besi untuk ibu hamil                 |
| **LILA**       | Lingkar Lengan Atas — indikator status gizi ibu hamil                   |
| **Stunting**   | Kondisi gagal tumbuh pada balita akibat kekurangan gizi kronis          |
| **NIK**        | Nomor Induk Kependudukan — ID nasional 16 digit                         |
| **ULID**       | Universally Unique Lexicographically Sortable Identifier                |
| **DTO**        | Data Transfer Object — objek pembawa data antar layer                   |
| **RBAC**       | Role-Based Access Control — kontrol akses berbasis peran                |
| **Inertia.js** | Protokol yang memungkinkan SPA tanpa API terpisah                       |
| **SSR**        | Server-Side Rendering                                                   |
| **shadcn-vue** | Library komponen Vue UI berbasis Radix                                  |

---

## Lampiran: Referensi Teknis

### Dependencies Utama

**PHP (composer.json)**

```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^11.0",
    "laravel/breeze": "^2.3",
    "inertiajs/inertia-laravel": "^2.0",
    "spatie/laravel-permission": "^6.0",
    "spatie/laravel-query-builder": "^6.0",
    "spatie/laravel-activitylog": "^4.0",
    "barryvdh/laravel-dompdf": "^3.0",
    "maatwebsite/excel": "^3.1",
    "league/flysystem-aws-s3-v3": "^3.0"
  },
  "require-dev": {
    "pestphp/pest": "^3.0",
    "pestphp/pest-plugin-laravel": "^3.0",
    "laravel/pint": "^1.0",
    "phpstan/phpstan": "^1.0",
    "nunomaduro/larastan": "^2.0",
    "fakerphp/faker": "^1.23"
  }
}
```

**Node.js (package.json)**

```json
{
  "dependencies": {
    "@inertiajs/vue3": "^2.0",
    "vue": "^3.5",
    "pinia": "^3.0",
    "vee-validate": "^4.0",
    "zod": "^3.0",
    "chart.js": "^4.0",
    "vue-chartjs": "^5.0",
    "lucide-vue-next": "^0.460",
    "@vueuse/core": "^12.0"
  },
  "devDependencies": {
    "@vitejs/plugin-vue": "^5.0",
    "vite": "^6.0",
    "tailwindcss": "^4.0",
    "typescript": "^5.0",
    "vue-tsc": "^2.0",
    "shadcn-vue": "latest"
  }
}
```

---

_Dokumen ini dibuat sebagai panduan pengembangan SIPOS. Setiap perubahan signifikan pada arsitektur atau kebutuhan harus direfleksikan di dokumen ini._

**Revisi Terakhir:** Maret 2026  
**Status:** Draft — Ready for Development
