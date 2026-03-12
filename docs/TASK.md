# ✅ Checklist Implementasi SIPOS

## Sistem Informasi Posyandu — Desa Belumbang

> **Referensi:** SRS SIPOS v1.0.0  
> **Stack:** PHP 8.3 · Laravel 11 · Inertia.js 2 · Vue 3 · shadcn-vue · MariaDB 11 · Redis · Docker  
> **Legend:** 🔴 Belum Mulai · 🟡 In Progress · 🟢 Selesai · ⚠️ Blocked

---

## Daftar Fase

- [FASE 0 — Setup Infrastruktur & Proyek](#fase-0--setup-infrastruktur--proyek)
- [x] **FASE 1 — Fondasi Aplikasi**
- [x] **FASE 2 — Autentikasi & Otorisasi**
- [x] **FASE 3 — Module Master Data**
- [FASE 4 — Module Pemeriksaan](#fase-4--module-pemeriksaan)
- [FASE 5 — Module Jadwal & Notifikasi](#fase-5--module-jadwal--notifikasi)
- [FASE 6 — Module Laporan & Dashboard](#fase-6--module-laporan--dashboard)
- [FASE 7 — Frontend & UI/UX](#fase-7--frontend--uiux)
- [FASE 8 — Testing](#fase-8--testing)
- [FASE 9 — Kualitas Kode & Keamanan](#fase-9--kualitas-kode--keamanan)
- [FASE 10 — Deployment & CI/CD](#fase-10--deployment--cicd)
- [FASE 11 — Finalisasi & Dokumentasi](#fase-11--finalisasi--dokumentasi)

---

## FASE 0 — Setup Infrastruktur & Proyek

> **Tujuan:** Menyiapkan seluruh environment dan tooling sebelum menulis kode bisnis apapun.

### 0.1 Persiapan Repository & Version Control

- [x] **0.1.1** Buat repository Git (GitHub/GitLab) dengan nama `sipos`
- [x] **0.1.2** Setup branch strategy:
  - `main` → production-ready
  - `develop` → integration branch
  - `feature/*` → fitur baru
  - `fix/*` → bug fix
  - `release/*` → release candidate
- [x] **0.1.3** Buat `.gitignore` (Laravel default + tambahan: `.env`, `docker/secrets/`, `*.log`)
- [x] **0.1.4** Buat `README.md` dengan instruksi setup awal
- [x] **0.1.5** Setup branch protection rules di `main` (require PR + review)
- [x] **0.1.6** Buat `CONTRIBUTING.md` dengan commit convention (Conventional Commits)

### 0.2 Docker — Development Environment

- [x] **0.2.1** Buat struktur direktori `docker/dev/` dan `docker/prod/`
- [x] **0.2.2** Buat `docker/dev/Dockerfile` (PHP 8.3-fpm-alpine + Xdebug + Node.js)
- [x] **0.2.3** Buat `docker/dev/nginx.conf` (server block untuk Laravel)
- [x] **0.2.4** Buat `docker/dev/php.ini` (upload_max_filesize=10M, memory_limit=256M)
- [x] **0.2.5** Buat `docker/dev/xdebug.ini` (xdebug.mode=develop,debug)
- [x] **0.2.6** Buat `docker-compose.dev.yml` dengan service:
  - `app` (php-fpm)
  - `nginx` (port 8080)
  - `mariadb` (port 3307)
  - `redis` (port 6380)
  - `worker` (queue)
  - `mailpit` (port 8025 + 1025)
- [x] **0.2.7** Buat `docker/mariadb/init.sql` (create database + charset utf8mb4)
- [x] **0.2.8** Test `docker compose -f docker-compose.dev.yml up -d` berhasil
- [x] **0.2.9** Verifikasi semua container running dengan `docker ps`

### 0.3 Docker — Production Environment

- [x] **0.3.1** Buat `docker/prod/Dockerfile` (multi-stage: node-builder → php-fpm)
- [x] **0.3.2** Buat `docker/prod/nginx.conf` (dengan SSL, gzip, security headers)
- [x] **0.3.3** Buat `docker/prod/php.ini` (opcache enabled, display_errors=Off)
- [x] **0.3.4** Buat `docker/prod/opcache.ini` (opcache.enable=1, validate_timestamps=0)
- [x] **0.3.5** Buat `docker/prod/supervisor.conf` (untuk queue worker)
- [x] **0.3.6** Buat `docker-compose.prod.yml` dengan service:
  - `app`, `nginx`, `mariadb`, `redis`, `worker`, `scheduler`
- [x] **0.3.7** Setup Docker secrets untuk `db_root_password` dan `db_password`
- [x] **0.3.8** Buat `docker/secrets/` directory (tambahkan ke `.gitignore`)
- [ ] **0.3.9** Test production build: `docker compose -f docker-compose.prod.yml build`

### 0.4 Laravel Project Setup

- [x] **0.4.1** Install Laravel 11: `composer create-project laravel/laravel sipos "^11.0"`
- [x] **0.4.2** Install Laravel Breeze dengan Inertia + Vue stack:
  ```bash
  composer require laravel/breeze --dev
  php artisan breeze:install vue --inertia
  ```
- [x] **0.4.3** Install shadcn-vue: `npx shadcn-vue@latest init`
- [x] **0.4.4** Verifikasi `npm run dev` berjalan tanpa error
- [x] **0.4.5** Copy `.env.example` ke `.env` dan set `APP_KEY`
- [x] **0.4.6** Konfigurasi `.env` untuk Docker (DB_HOST=mariadb, REDIS_HOST=redis)
- [x] **0.4.7** Buat `.env.testing` (DB terpisah untuk test)
- [x] **0.4.8** Install dependencies PHP tambahan:
  ```bash
  composer require spatie/laravel-permission
  composer require spatie/laravel-query-builder
  composer require spatie/laravel-activitylog
  composer require barryvdh/laravel-dompdf
  composer require maatwebsite/excel
  ```
- [x] **0.4.9** Install dev dependencies:
  ```bash
  composer require pestphp/pest pestphp/pest-plugin-laravel --dev
  composer require laravel/pint phpstan/phpstan nunomaduro/larastan --dev
  php artisan pest:install
  ```
- [x] **0.4.10** Install frontend dependencies:
  ```bash
  npm install pinia @vueuse/core vee-validate zod
  npm install chart.js vue-chartjs lucide-vue-next
  npm install -D typescript vue-tsc
  ```

### 0.5 Tooling & Code Quality Setup

- [x] **0.5.1** Konfigurasi `pint.json` (Laravel Pint, PSR-12 based)
- [x] **0.5.2** Konfigurasi `phpstan.neon` (level 8, Larastan)
- [x] **0.5.3** Konfigurasi `phpunit.xml` / `pest.config.php`
- [x] **0.5.4** Konfigurasi `tsconfig.json` (strict mode)
- [x] **0.5.5** Buat `Makefile` dengan semua developer shortcuts
- [x] **0.5.6** Setup EditorConfig (`.editorconfig`) untuk konsistensi indentasi
- [x] **0.5.7** Setup Prettier untuk Vue/TypeScript files
- [x] **0.5.8** Verifikasi `./vendor/bin/pint --test` berjalan
- [x] **0.5.9** Verifikasi `./vendor/bin/phpstan analyse` berjalan

---

## FASE 1 — Fondasi Aplikasi

> **Tujuan:** Membangun kerangka arsitektur (struktur direktori, base classes, providers) sebelum fitur bisnis.

### 1.1 Konfigurasi Aplikasi

- [x] **1.1.1** Set `config/app.php`:
  - `timezone` → `Asia/Makassar`
  - `locale` → `id`
  - `faker_locale` → `id_ID`
- [x] **1.1.2** Buat `config/sipos.php` untuk konfigurasi aplikasi spesifik:
  - `per_page` (default pagination: 15)
  - `upload_path` (path storage)
  - `max_upload_size` (2048 KB)
  - `posyandu_name` ('Posyandu Desa Belumbang')
- [x] **1.1.3** Konfigurasi `config/database.php` untuk MariaDB (strict mode, charset utf8mb4)
- [x] **1.1.4** Konfigurasi `config/cache.php` → default driver: Redis
- [x] **1.1.5** Konfigurasi `config/queue.php` → default driver: Redis
- [x] **1.1.6** Konfigurasi `config/session.php` → driver: Redis, lifetime: 120

### 1.2 Enum Classes

- [x] **1.2.1** Buat `app/Enums/UserRole.php` (Admin, Bidan, Kader, Peserta)
- [x] **1.2.2** Buat `app/Enums/PesertaType.php` (IbuHamil, Balita, Lansia)
- [x] **1.2.3** Buat `app/Enums/JadwalStatus.php` (Draft, Validated, Rejected, Completed)
- [x] **1.2.4** Buat `app/Enums/JenisKelamin.php` (L, P)
- [x] **1.2.5** Tambahkan method `label()` di setiap Enum untuk tampilan UI

### 1.3 Base Classes

- [x] **1.3.1** Buat `app/Repositories/Interfaces/BaseRepositoryInterface.php` dengan contract:
  - `all()`, `findById()`, `create()`, `update()`, `delete()`
- [x] **1.3.2** Buat `app/Repositories/Eloquent/BaseRepository.php` (abstract class)
- [x] **1.3.3** Buat `app/Services/BaseService.php` (abstract class)
- [x] **1.3.4** Buat `app/Http/Controllers/BaseController.php` dengan helper methods
- [x] **1.3.5** Buat `app/Traits/HasUlid.php` (auto-generate ULID saat creating)
- [x] **1.3.6** Buat `app/Traits/HasAuditLog.php` (boot observer pada model)
- [x] **1.3.7** Buat `app/Helpers/UmurHelper.php` (hitungUmurBalita, hitungUmurLansia)
- [x] **1.3.8** Buat `app/Helpers/NikHelper.php` (validasi, format NIK)

### 1.4 Service Provider

- [x] **1.4.1** Buat `app/Providers/RepositoryServiceProvider.php` (binding interface ke implementation)
- [x] **1.4.2** Daftarkan di `bootstrap/providers.php` di `bootstrap/providers.php`
- [x] **1.4.3** Update `AppServiceProvider` untuk:
  - Register Inertia shared props (flash messages, auth user, permissions)
  - Register macro untuk pagination response
- [x] **1.4.4** Buat `app/Providers/EventServiceProvider.php` (daftarkan Events & Listeners)

### 1.5 Inertia & Frontend Foundation

- [x] **1.5.1** Konfigurasi `HandleInertiaRequests` middleware:
  - Share `auth.user` + `auth.permissions`
  - Share `flash` messages
  - Share `ziggy` routes
- [x] **1.5.2** Install `ziggy` untuk route helper di frontend: `composer require tightenco/ziggy`
- [x] **1.5.3** Buat `resources/js/types/index.d.ts` (TypeScript interfaces untuk semua model)
- [x] **1.5.4** Buat `resources/js/Layouts/AppLayout.vue` (sidebar, navbar, breadcrumb)
- [x] **1.5.5** Buat `resources/js/Layouts/AuthLayout.vue` (centered card layout)
- [x] **1.5.6** Buat `resources/js/Layouts/GuestLayout.vue` (untuk halaman publik peserta)
- [x] **1.5.7** Setup Pinia store: `resources/js/stores/useAuthStore.ts`
- [x] **1.5.8** Setup Pinia store: `resources/js/stores/useNotificationStore.ts`
- [x] **1.5.9** Buat `resources/js/Composables/useConfirm.ts` (dialog konfirmasi hapus)
- [x] **1.5.10** Buat `resources/js/Composables/useToast.ts` (wrapper shadcn toast)
- [x] **1.5.11** Buat `resources/js/Composables/usePermission.ts` (cek permission di frontend)

### 1.6 Shared UI Components

- [x] **1.6.1** Install shadcn-vue components yang dibutuhkan:
  - Button, Input, Label, Select, Checkbox, RadioGroup
  - Table, Pagination
  - Dialog, AlertDialog, Sheet
  - Card, Badge, Separator
  - Form, FormField, FormItem, FormMessage
  - Toast (Sonner)
  - Calendar, DatePicker
  - DropdownMenu, NavigationMenu
  - Skeleton (loading state)
  - Avatar
- [x] **1.6.2** Buat `resources/js/Components/DataTable.vue` (reusable table + sort + search)
- [x] **1.6.3** Buat `resources/js/Components/Pagination.vue` (wrapper Inertia links)
- [x] **1.6.4** Buat `resources/js/Components/SearchInput.vue` (debounced search)
- [x] **1.6.5** Buat `resources/js/Components/ConfirmDialog.vue`
- [x] **1.6.6** Buat `resources/js/Components/FileUpload.vue` (preview foto)
- [x] **1.6.7** Buat `resources/js/Components/StatusBadge.vue`
- [x] **1.6.8** Buat `resources/js/Components/Charts/LineChart.vue`
- [x] **1.6.9** Buat `resources/js/Components/Charts/BarChart.vue`
- [x] **1.6.10** Buat `resources/js/Components/Charts/DoughnutChart.vue`
- [x] **1.6.11** Buat `resources/js/Components/EmptyState.vue`
- [x] **1.6.12** Buat `resources/js/Components/PageHeader.vue` (judul + breadcrumb + action button)
- [x] **1.6.13** Buat `resources/js/Components/StatCard.vue` (widget statistik dashboard)

---

## FASE 2 — Autentikasi & Otorisasi

> **Tujuan:** Login, session, RBAC, dan policies untuk semua role.

### 2.1 Database — Users & Roles

- [x] **2.1.1** Modifikasi migration `create_users_table`:
  - Ubah primary key ke CHAR(26) ULID
  - Tambah kolom: `username`, `role` (enum), `is_active`
  - Hapus kolom `name` → ganti `nama_user`
- [x] **2.1.2** Buat migration `create_personal_access_tokens_table` (jika belum ada)
- [x] **2.1.3** Jalankan migration: `php artisan migrate`

### 2.2 Model User

- [x] **2.2.1** Update `app/Models/User.php`:
  - Gunakan trait `HasUlid`
  - Tambah `SoftDeletes`
  - Definisikan cast untuk `role` → `UserRole` enum
  - Tambah relasi: `kader()`, `bidan()`, `ibuHamil()`, `balita()`, `lansia()`
  - Tambah method helper: `isAdmin()`, `isBidan()`, `isKader()`, `isPeserta()`
- [x] **2.2.2** Buat `UserFactory` dengan states: `->admin()`, `->bidan()`, `->kader()`, `->peserta()`

### 2.3 Autentikasi (Laravel Breeze Inertia)

- [x] **2.3.1** Buat/update `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- [x] **2.3.2** Kustomisasi `LoginRequest` → validasi menggunakan `username` (bukan email)
- [x] **2.3.3** Kustomisasi halaman `resources/js/Pages/Auth/Login.vue` (branding SIPOS)
- [x] **2.3.4** Implementasi rate limiting di login (5 attempt/menit per IP)
- [x] **2.3.5** Setup redirect after login berdasarkan role:
  - Admin, Bidan, Kader → `/dashboard`
  - Peserta → `/portal`
- [x] **2.3.6** Implementasi "Remember Me" functionality
- [x] **2.3.7** Buat halaman `resources/js/Pages/Auth/ConfirmPassword.vue`
- [x] **2.3.8** Buat halaman `resources/js/Pages/Profile/Edit.vue` (ubah password, update profil)

### 2.4 Role-Based Access Control (Policies)

- [x] **2.4.1** Buat `app/Policies/IbuHamilPolicy.php` (viewAny, view, create, update, delete)
- [x] **2.4.2** Buat `app/Policies/BalitaPolicy.php`
- [x] **2.4.3** Buat `app/Policies/LansiaPolicy.php`
- [x] **2.4.4** Buat `app/Policies/PemeriksaanPolicy.php`
- [x] **2.4.5** Buat `app/Policies/JadwalPosyanduPolicy.php` (tambah: `validate`, `reject`)
- [x] **2.4.6** Buat `app/Policies/LaporanPolicy.php`
- [x] **2.4.7** Buat `app/Policies/UserPolicy.php`
- [x] **2.4.8** Register semua policies di `AuthServiceProvider` / `AppServiceProvider`
- [x] **2.4.9** Implementasi `can()` gate checks di semua controller
- [x] **2.4.10** Share permissions ke Inertia frontend via `HandleInertiaRequests`

### 2.5 Middleware

- [x] **2.5.1** Buat `app/Http/Middleware/EnsureUserIsActive.php` (cek `is_active` = true)
- [x] **2.5.2** Register middleware di `bootstrap/app.php`
- [x] **2.5.3** Buat `app/Http/Middleware/RoleMiddleware.php` (cek role untuk route group)
- [x] **2.5.4** Definisikan route group berdasarkan role di `routes/web.php`

### 2.6 Seeder — Data Awal

- [x] **2.6.1** Buat `database/seeders/UserSeeder.php`:
  - 1 admin (admin@sipos.id / password)
  - 2 bidan
  - 8 kader (1 per posyandu)
- [x] **2.6.2** Buat `database/seeders/DatabaseSeeder.php` yang memanggil semua seeder
- [x] **2.6.3** Test `php artisan db:seed` berhasil
- [x] **2.6.4** Verifikasi login dengan semua role berhasil

---

## FASE 3 — Module Master Data

> **Tujuan:** CRUD lengkap untuk Kader, Bidan, Posyandu, Ibu Hamil, Balita, Lansia.

### 3.1 Migration — Semua Tabel Master

- [x] **3.1.1** Buat migration `create_kader_table` (sesuai SRS schema)
- [x] **3.1.2** Buat migration `create_bidan_table`
- [x] **3.1.3** Buat migration `create_posyandu_table`
- [x] **3.1.4** Buat migration `create_ibu_hamil_table` (semua kolom + indexes)
- [x] **3.1.5** Buat migration `create_balita_table`
- [x] **3.1.6** Buat migration `create_lansia_table`
- [x] **3.1.7** Buat migration `create_audit_logs_table`
- [x] **3.1.8** Jalankan semua migration, pastikan tidak ada error
- [x] **3.1.9** Verifikasi indexes terbuat (`SHOW INDEX FROM ibu_hamil`)

### 3.2 Models — Master Data

- [x] **3.2.1** Buat `app/Models/Kader.php` (HasUlid, SoftDeletes, relasi user, posyandu)
- [x] **3.2.2** Buat `app/Models/Bidan.php` (HasUlid, SoftDeletes, relasi user)
- [x] **3.2.3** Buat `app/Models/Posyandu.php` (HasUlid, SoftDeletes, relasi kader, jadwal)
- [x] **3.2.4** Buat `app/Models/IbuHamil.php` (HasUlid, SoftDeletes, relasi user, pemeriksaan)
- [x] **3.2.5** Buat `app/Models/Balita.php` (HasUlid, SoftDeletes, relasi user, pemeriksaan)
- [x] **3.2.6** Buat `app/Models/Lansia.php` (HasUlid, SoftDeletes, relasi user, pemeriksaan)
- [x] **3.2.7** Tambah computed property `umur` di model IbuHamil, Balita, Lansia
- [x] **3.2.8** Tambah accessor `foto_url` (return full URL dari storage) di semua model dengan foto

### 3.3 Factories & Seeders — Master Data

- [x] **3.3.1** Buat `KaderFactory.php` (dengan data Bali/Indonesia menggunakan Faker id_ID)
- [x] **3.3.2** Buat `BidanFactory.php`
- [x] **3.3.3** Buat `PosyanduFactory.php` (8 posyandu, lokasi dusun-dusun Belumbang)
- [x] **3.3.4** Buat `IbuHamilFactory.php` (minimal 7 record)
- [x] **3.3.5** Buat `BalitaFactory.php` (minimal 20 record untuk demo)
- [x] **3.3.6** Buat `LansiaFactory.php` (minimal 20 record untuk demo)
- [x] **3.3.7** Buat `PosyanduSeeder.php` (seed 8 posyandu + assign kader)
- [x] **3.3.8** Buat `DemoDataSeeder.php` (seed data untuk development/demo)

### 3.4 Repository & Service — Ibu Hamil

- [x] **3.4.1** Buat `app/Repositories/Interfaces/IbuHamilRepositoryInterface.php`
- [x] **3.4.2** Buat `app/Repositories/Eloquent/IbuHamilRepository.php`:
  - `all(filters)` — dengan eager loading user, pagination, filter search/status
  - `findById(id)` — with pemeriksaan count
  - `findByNik(nik)`
  - `create(dto)`
  - `update(id, dto)`
  - `delete(id)` — soft delete
  - `getWithPemeriksaan(id)` — with latest pemeriksaan
- [x] **3.4.3** Buat `app/DTOs/IbuHamil/CreateIbuHamilDTO.php`
- [x] **3.4.4** Buat `app/DTOs/IbuHamil/UpdateIbuHamilDTO.php`
- [x] **3.4.5** Buat `app/Services/IbuHamilService.php`:
  - `paginate(filters)`
  - `create(dto)` — handle foto upload + fire event
  - `update(id, dto)` — handle foto update
  - `delete(id)`
  - `getDetail(id)` — with riwayat pemeriksaan
- [x] **3.4.6** Register binding di `RepositoryServiceProvider`

### 3.5 HTTP Layer — Ibu Hamil

- [x] **3.5.1** Buat `app/Http/Requests/IbuHamil/StoreIbuHamilRequest.php` (semua validasi)
- [x] **3.5.2** Buat `app/Http/Requests/IbuHamil/UpdateIbuHamilRequest.php` (unique ignore self)
- [x] **3.5.3** Buat `app/Http/Resources/IbuHamilResource.php`
- [x] **3.5.4** Buat `app/Http/Controllers/IbuHamilController.php` (full CRUD dengan policy checks)
- [x] **3.5.5** Daftarkan route resource `ibu-hamil` di `routes/web.php`

### 3.6 Frontend — Ibu Hamil

- [x] **3.6.1** Buat `resources/js/Pages/IbuHamil/Index.vue`
- [x] **3.6.2** Buat `resources/js/Pages/IbuHamil/Create.vue`
- [x] **3.6.3** Buat `resources/js/Pages/IbuHamil/Edit.vue`
- [x] **3.6.4** Buat `resources/js/Pages/IbuHamil/Show.vue`
- [x] **3.6.5** Implementasi soft confirm sebelum delete
- [x] **3.6.6** Implementasi preview foto sebelum upload

### 3.7 Repository, Service, HTTP, Frontend — Balita

- [x] **3.7.1** Backend logic (Repository, Service, Request, Resource, Controller)
- [x] **3.7.2** Buat `resources/js/Pages/Balita/Index.vue`
- [x] **3.7.3** Buat `resources/js/Pages/Balita/Create.vue`
- [x] **3.7.4** Buat `resources/js/Pages/Balita/Edit.vue`
- [x] **3.7.5** Buat `resources/js/Pages/Balita/Show.vue`
- [x] **3.7.6** Test CRUD Lengkap — 🟢 Berhasil

### 3.8 Repository, Service, HTTP, Frontend — Lansia

- [x] **3.8.1** Backend logic (Repository, Service, Request, Resource, Controller)
- [x] **3.8.2** Buat `resources/js/Pages/Lansia/Index.vue`
- [x] **3.8.3** Buat `resources/js/Pages/Lansia/Create.vue`
- [x] **3.8.4** Buat `resources/js/Pages/Lansia/Edit.vue`
- [x] **3.8.5** Buat `resources/js/Pages/Lansia/Show.vue`
- [x] **3.8.6** Test CRUD Lengkap — 🟢 Berhasil

### 3.9 Repository, Service, HTTP, Frontend — Posyandu

- [x] **3.9.1** Backend logic (Repository, Service, Request, Resource, Controller)
- [x] **3.9.2** Buat `resources/js/Pages/Posyandu/Index.vue`
- [x] **3.9.3** Buat `resources/js/Pages/Posyandu/Create.vue`
- [x] **3.9.4** Buat `resources/js/Pages/Posyandu/Edit.vue`
- [x] **3.9.5** Buat `resources/js/Pages/Posyandu/Show.vue`
- [x] **3.9.6** Test CRUD Lengkap — 🟢 Berhasil

### 3.10 Repository, Service, HTTP, Frontend — Kader & Bidan

- [x] **3.10.1** Backend logic (Repository, Service, Request, Resource, Controller)
- [x] **3.10.2** Buat `resources/js/Pages/Kader/Index.vue`
- [x] **3.10.3** Buat `resources/js/Pages/Kader/Create.vue`
- [x] **3.10.4** Buat `resources/js/Pages/Kader/Edit.vue`
- [x] **3.10.5** Buat `resources/js/Pages/Kader/Show.vue`
- [x] **3.10.6** Buat `resources/js/Pages/Bidan/Index.vue`
- [x] **3.10.7** Buat `resources/js/Pages/Bidan/Create.vue`
- [x] **3.10.8** Buat `resources/js/Pages/Bidan/Edit.vue`
- [x] **3.10.9** Buat `resources/js/Pages/Bidan/Show.vue`
- [x] **3.10.10** Test CRUD Lengkap — 🟢 Berhasil (grafik tensi, BB)

### 3.11 File Upload Service

- [x] **3.11.1** Buat `app/Services/FileUploadService.php`:
  - `upload(file, directory)` → simpan ke storage, return path
  - `delete(path)` → hapus file lama
  - `getUrl(path)` → return public URL
- [x] **3.11.2** Konfigurasi `config/filesystems.php` untuk local disk (symlink public)
- [x] **3.11.3** Jalankan `php artisan storage:link`
- [x] **3.11.4** Test upload dan akses file berhasil

---

## FASE 4 — Module Pemeriksaan

> **Tujuan:** Sistem pencatatan pemeriksaan kesehatan untuk semua kategori peserta.

### 4.1 Migration & Model — Pemeriksaan

- [x] **4.1.1** Buat migration `create_pemeriksaan_table` (polymorphic)
- [x] **4.1.2** Buat migration `create_jadwal_posyandu_table`
- [x] **4.1.3** Buat model `Pemeriksaan.php` dengan relasi `morphTo`
- [x] **4.1.4** Buat model `JadwalPosyandu.php`
- [x] **4.1.5** Update model `IbuHamil`, `Balita`, `Lansia` dengan relasi `morphMany`
- [x] **4.1.6** Jalankan migration (🟢 Berhasil)

### 4.2 Repository & Service — Pemeriksaan

- [x] **4.2.1** Buat `PemeriksaanRepositoryInterface.php`
- [x] **4.2.2** Buat `PemeriksaanRepository.php`
- [x] **4.2.3** Buat `app/DTOs/Pemeriksaan/CreatePemeriksaanDTO.php`
- [x] **4.2.4** Buat `PemeriksaanService.php`
- [x] **4.2.6** Buat `app/Http/Resources/PemeriksaanResource.php`
- [x] **4.2.7** Buat `PemeriksaanController.php`
- [x] **4.2.8** Daftarkan route resource `pemeriksaan` di `web.php`
- [x] **4.2.9** Buat `JadwalPosyanduController.php` & routes (drafting phase)

### 4.3 Repository & Service — Pemeriksaan

- [x] **4.3.1** Buat `PemeriksaanRepositoryInterface.php`
- [x] **4.3.2** Buat `PemeriksaanRepository.php`:
  - `findByPeserta(type, id, filters)`
  - `findByJadwal(jadwalId)`
  - `getStatistik(type, pesertaId)` — untuk grafik
  - `create(dto)`
  - `update(id, dto)`
  - `delete(id)`
- [x] **4.3.3** Buat `app/DTOs/Pemeriksaan/CreatePemeriksaanDTO.php` (semua field)
- [x] **4.3.4** Buat `app/DTOs/Pemeriksaan/UpdatePemeriksaanDTO.php`
- [x] **4.3.5** Buat `app/Services/PemeriksaanService.php`:
  - `createForIbuHamil(jadwalId, dto)`
  - `createForBalita(jadwalId, dto)`
  - `createForLansia(jadwalId, dto)`
  - `getStatistikGrafik(type, pesertaId)` — return data untuk chart
  - `isKEK(lila)` — cek LILA < 23.5 cm
  - `detectStunting(bb, tb, usia)` — deteksi stunting balita

### 4.4 HTTP Layer — Pemeriksaan

- [x] **4.4.1** Buat `StorePemeriksaanRequest.php` (validasi berdasarkan `peserta_type`)
- [x] **4.4.2** Buat `UpdatePemeriksaanRequest.php`
- [x] **4.4.3** Buat `PemeriksaanResource.php`
- [x] **4.4.4** Buat `PemeriksaanController.php`:
  - `index()` — list semua (filter: type, tanggal, posyandu)
  - `create()` — form (pre-filled jika ada `peserta_id` query param)
  - `store()`
  - `show()`
  - `edit()`
  - `update()`
  - `destroy()`
- [x] **4.4.5** Daftarkan route resource `pemeriksaan` di `routes/web.php`

### 4.5 Frontend — Pemeriksaan

- [x] **4.5.1** Buat `Pages/Pemeriksaan/Index.vue` (tabel, filter type/tanggal/posyandu)
- [x] **4.5.2** Buat `Pages/Pemeriksaan/Create.vue`:
  - Step 1: Pilih kategori (Ibu Hamil / Balita / Lansia)
  - Step 2: Cari/pilih peserta
  - Step 3: Input data pemeriksaan sesuai kategori
  - Tampilkan form field yang berbeda berdasarkan kategori (conditional rendering)
- [x] **4.5.3** Buat `Pages/Pemeriksaan/Edit.vue`
- [x] **4.5.4** Buat `Pages/Pemeriksaan/Show.vue` (detail lengkap pemeriksaan)
- [x] **4.5.5** Buat komponen `Components/Pemeriksaan/FormIbuHamil.vue`
- [x] **4.5.6** Buat komponen `Components/Pemeriksaan/FormBalita.vue`
- [x] **4.5.7** Buat komponen `Components/Pemeriksaan/FormLansia.vue`
- [x] **4.5.8** Buat komponen `Components/Pemeriksaan/RiwayatTable.vue`
- [x] **4.5.9** Implementasi alert KEK (tampil warning jika LILA < 23.5)
- [x] **4.5.10** Implementasi alert stunting (tampil warning jika terindikasi)

---

## FASE 5 — Module Jadwal & Notifikasi

> **Tujuan:** Pengelolaan jadwal posyandu dan sistem notifikasi peserta.

### 5.1 Migration — Jadwal & Notifikasi

- [x] **5.1.1** Buat migration `create_jadwal_posyandu_table` (sesuai SRS schema)
- [x] **5.1.2** Buat migration `create_notifications_table` (Laravel default notification table)
- [x] **5.1.3** Jalankan migration

### 5.2 Model Jadwal Posyandu

- [x] **5.2.1** Buat `app/Models/JadwalPosyandu.php`:
  - Trait `HasUlid`, `SoftDeletes`
  - Cast `status` → `JadwalStatus` enum
  - Relasi: `posyandu()`, `kader()`, `bidan()`, `pemeriksaan()`
  - Scopes: `scopeValidated()`, `scopeUpcoming()`, `scopeThisMonth()`
- [x] **5.2.2** Buat `JadwalPosyanduFactory.php`

### 5.3 Repository & Service — Jadwal

- [x] **5.3.1** Buat `JadwalRepositoryInterface.php`
- [x] **5.3.2** Buat `JadwalRepository.php`:
  - `getUpcoming(days)` — jadwal N hari ke depan
  - `getByMonth(year, month)`
  - `getByPosyandu(posyanduId)`
  - Semua CRUD standard
- [x] **5.3.3** Buat `CreateJadwalDTO.php` & `UpdateJadwalDTO.php`
- [x] **5.3.4** Buat `app/Services/JadwalService.php`

### 5.4 HTTP Layer — Jadwal

- [x] **5.4.1** Buat `StoreJadwalRequest.php` & `UpdateJadwalRequest.php`
- [x] **5.4.2** Buat `JadwalResource.php`
- [x] **5.4.3** Buat `JadwalPosyanduController.php`:
  - Standard CRUD actions
  - `validate(jadwal)` — POST /jadwal/{id}/validate
  - `reject(jadwal)` — POST /jadwal/{id}/reject
  - `complete(jadwal)` — POST /jadwal/{id}/complete
- [x] **5.4.4** Daftarkan routes di `routes/web.php` (resource + custom actions)

### 5.5 Frontend — Jadwal

- [x] **5.5.1** Buat `Pages/Jadwal/Index.vue` (tampilan list + filter)
- [x] **5.5.2** Buat `Pages/Jadwal/Create.vue` (form jadwal baru)
- [x] **5.5.3** Buat `Pages/Jadwal/Edit.vue`
- [x] **5.5.4** Buat `Pages/Jadwal/Show.vue`:
  - Detail jadwal
  - Tombol validasi/tolak (untuk bidan)
  - Tombol selesai (untuk bidan)
  - Status badge
- [x] **5.5.5** Buat `Components/Jadwal/CalendarView.vue` (kalender interaktif)
- [x] **5.5.6** Buat `Components/Jadwal/JadwalCard.vue`
- [x] **5.5.7** Implementasi tampilan status berbeda per role:
  - Kader: lihat status validasi
  - Bidan: tombol validate/reject muncul jika status = draft

### 5.6 Sistem Notifikasi

- [x] **5.6.1** Buat `app/Notifications/JadwalPosyanduNotification.php` (via database channel)
- [x] **5.6.2** Buat `app/Events/JadwalValidated.php`
- [x] **5.6.3** Buat `app/Events/JadwalRejected.php`
- [x] **5.6.4** Buat `app/Listeners/SendJadwalNotificationToKader.php`
- [x] **5.6.5** Buat `app/Listeners/SendJadwalNotificationToPeserta.php`
- [x] **5.6.6** Buat `app/Console/Commands/SendUpcomingJadwalReminder.php` (kirim H-3)
- [x] **5.6.7** Register command di `routes/console.php` (daily schedule)
- [x] **5.6.8** Buat `NotifikasiController.php` (index, markAsRead, markAllAsRead)
- [x] **5.6.9** Daftarkan routes `/notifications`
- [x] **5.6.10** Notification bell di sidebar layout (badge count)
- [x] **5.6.11** Buat `Pages/Notifications/Index.vue` (list semua notifikasi)

---

## FASE 6 — Module Laporan & Dashboard

> **Tujuan:** Dashboard statistik dan export laporan PDF/Excel.

### 6.1 Service — Laporan

- [x] **6.1.1** Buat `app/Services/LaporanService.php`:
  - `getStatistikDashboard()` → total peserta, jadwal bulan ini, dll
  - `laporanIbuHamil(filters)` → data rekap ibu hamil
  - `laporanBalita(filters)` → data rekap balita + deteksi stunting
  - `laporanLansia(filters)` → data rekap lansia
  - `laporanBulanan(posyanduId, bulan, tahun)` → laporan kegiatan bulanan
  - `getGrafikPertumbuhan(balitaId)` → data BB/TB time-series
  - `getGrafikKesehatan(pesertaId, type)` → data tensi, dll

### 6.2 PDF Export

- [x] **6.2.1** Setup DomPDF: `composer require barryvdh/laravel-dompdf`
- [x] **6.2.2** Konfigurasi `config/dompdf.php` (font, paper size A4, orientation)
- [x] **6.2.3** Buat template Blade `resources/views/laporan/ibu-hamil.blade.php`
- [x] **6.2.4** Buat template Blade `resources/views/laporan/balita.blade.php`
- [x] **6.2.5** Buat template Blade `resources/views/laporan/lansia.blade.php`
- [x] **6.2.6** Buat template Blade `resources/views/laporan/bulanan.blade.php`
- [x] **6.2.7** Buat template Blade `resources/views/laporan/kms-ibu-hamil.blade.php`
- [x] **6.2.8** Buat template Blade `resources/views/laporan/kms-balita.blade.php`
- [x] **6.2.9** Semua template menggunakan header: logo SIPOS, nama posyandu, tanggal cetak
- [x] **6.2.10** Buat `app/Services/PdfExportService.php` (generate dan stream PDF)

### 6.3 Excel Export

- [x] **6.3.1** Setup Laravel Excel: `composer require maatwebsite/excel`
- [x] **6.3.2** Buat `app/Exports/IbuHamilExport.php`
- [x] **6.3.3** Buat `app/Exports/BalitaExport.php`
- [x] **6.3.4** Buat `app/Exports/LansiaExport.php`
- [x] **6.3.5** Buat `app/Exports/LaporanBulananExport.php`
- [x] **6.3.6** Implementasi WithHeadings, WithStyles, WithColumnWidths interface

### 6.4 HTTP Layer — Laporan

- [x] **6.4.1** Buat `LaporanController.php`:
  - `index()` — halaman pilihan laporan
  - `ibuHamil(request)` — filter + tampil
  - `balita(request)`
  - `lansia(request)`
  - `bulanan(request)`
  - `exportPdf(request)` — stream PDF download
  - `exportExcel(request)` — stream Excel download
- [x] **6.4.2** Daftarkan routes laporan
- [ ] **6.4.3** Gunakan job Queue untuk generate laporan besar (> 100 record)

### 6.5 Frontend — Laporan

- [x] **6.5.1** Buat `Pages/Laporan/Index.vue` (pilihan jenis laporan)
- [x] **6.5.2** Buat `Pages/Laporan/IbuHamil.vue` (filter + preview tabel + tombol export)
- [x] **6.5.3** Buat `Pages/Laporan/Balita.vue`
- [x] **6.5.4** Buat `Pages/Laporan/Lansia.vue`
- [x] **6.5.5** Buat `Pages/Laporan/Bulanan.vue`
- [x] **6.5.6** Implementasi filter: periode (bulan/tahun), posyandu
- [x] **6.5.7** Implementasi tombol "Export PDF" dan "Export Excel"
- [ ] **6.5.8** Implementasi loading state saat export

### 6.6 Dashboard

- [x] **6.6.1** Buat `DashboardController.php` → kirim data statistik ke Inertia
- [ ] **6.6.2** Buat `Pages/Dashboard.vue`:
  - StatCard: Total Ibu Hamil Aktif
  - StatCard: Total Balita Aktif
  - StatCard: Total Lansia Aktif
  - StatCard: Jadwal Bulan Ini
  - Grafik: Distribusi peserta per posyandu (Bar Chart)
  - Grafik: Trend kehadiran per bulan (Line Chart)
  - Tabel: Jadwal posyandu berikutnya (5 terdekat)
  - Alert: Peserta dengan indikasi KEK / stunting
- [ ] **6.6.3** Dashboard berbeda berdasarkan role:
  - Admin: statistik global + semua posyandu
  - Bidan: statistik posyandu-nya + antrian validasi jadwal
  - Kader: statistik posyandu-nya + jadwal berikutnya
  - Peserta: portal KMS digital pribadi

### 6.7 Portal Peserta (KMS Digital)

- [ ] **6.7.1** Buat `app/Http/Controllers/PortalController.php` (untuk role peserta)
- [ ] **6.7.2** Daftarkan route `/portal` untuk peserta
- [ ] **6.7.3** Buat `Pages/Portal/Dashboard.vue`:
  - Profil peserta
  - Riwayat pemeriksaan terakhir
  - Grafik perkembangan kesehatan
  - Jadwal posyandu berikutnya
  - Notifikasi aktif

---

## FASE 7 — Frontend & UI/UX

> **Tujuan:** Memastikan semua halaman responsif, konsisten, dan user-friendly.

### 7.1 Layout & Navigation

- [ ] **7.1.1** Finalisasi `AppLayout.vue`:
  - Sidebar dengan menu per role (collapsed mode untuk mobile)
  - Topbar dengan notification bell + user avatar dropdown
  - Breadcrumb navigation
  - Flash message toast (success/error/warning)
- [ ] **7.1.2** Implementasi sidebar collapse/expand state (simpan ke localStorage)
- [ ] **7.1.3** Responsif mobile: sidebar menjadi drawer overlay
- [ ] **7.1.4** Active state menu berdasarkan current route

### 7.2 Responsiveness

- [ ] **7.2.1** Test semua halaman di breakpoint: 375px (mobile), 768px (tablet), 1280px (desktop)
- [ ] **7.2.2** DataTable scrollable horizontal di mobile
- [ ] **7.2.3** Form grid layout: 2 kolom di desktop, 1 kolom di mobile
- [ ] **7.2.4** Tombol aksi: icon-only di mobile, icon + text di desktop

### 7.3 Loading & Empty States

- [ ] **7.3.1** Skeleton loading untuk semua tabel dan list
- [ ] **7.3.2** `EmptyState` component tampil saat data kosong (dengan gambar ilustrasi)
- [ ] **7.3.3** Loading spinner pada tombol submit (disabled saat processing)
- [ ] **7.3.4** Inertia progress bar di topbar

### 7.4 Form UX

- [ ] **7.4.1** Validasi real-time (vee-validate + zod schema) sebelum submit
- [ ] **7.4.2** Error message per field (dari server + client)
- [ ] **7.4.3** Auto-format NIK (tambah spasi setiap 4 digit di display)
- [ ] **7.4.4** Phone number formatter
- [ ] **7.4.5** Date picker dengan locale ID (tampil hari/bulan dalam Bahasa Indonesia)
- [ ] **7.4.6** Select/combobox searchable untuk pilihan peserta di form pemeriksaan
- [ ] **7.4.7** Scroll to first error setelah submit gagal

### 7.5 Aksesibilitas & Kualitas UI

- [ ] **7.5.1** Semua form input memiliki `<label>` yang terhubung
- [ ] **7.5.2** Semua tombol aksi memiliki `aria-label`
- [ ] **7.5.3** Warna status menggunakan badge yang tidak bergantung hanya pada warna (include icon)
- [ ] **7.5.4** Konsistensi warna: gunakan CSS variables dari shadcn theme
- [ ] **7.5.5** Favicon dan meta title di setiap halaman

---

## FASE 8 — Testing

> **Tujuan:** Memastikan coverage ≥ 80% dan semua edge case terpenuhi.

### 8.1 Setup Testing

- [ ] **8.1.1** Konfigurasi `phpunit.xml` / `pest.config.php` untuk in-memory SQLite (untuk unit test) dan MariaDB test DB (untuk feature test)
- [ ] **8.1.2** Konfigurasi `.env.testing` (DB_DATABASE=sipos_test)
- [ ] **8.1.3** Pastikan `RefreshDatabase` trait berjalan di semua feature test
- [ ] **8.1.4** Setup `TestCase` base class dengan helper methods (e.g., `actingAsKader()`)

### 8.2 Unit Tests — Helpers & DTOs

- [ ] **8.2.1** `tests/Unit/Helpers/UmurHelperTest.php`:
  - Hitung umur balita dalam bulan
  - Hitung umur lansia dalam tahun
  - Edge case: lahir hari ini (umur 0)
- [ ] **8.2.2** `tests/Unit/Helpers/NikHelperTest.php`:
  - Validasi NIK 16 digit
  - Validasi NIK hanya angka
- [ ] **8.2.3** `tests/Unit/DTOs/CreateIbuHamilDTOTest.php`:
  - `fromRequest()` mapping semua field benar
- [ ] **8.2.4** `tests/Unit/DTOs/CreatePemeriksaanDTOTest.php`

### 8.3 Unit Tests — Services

- [ ] **8.3.1** `tests/Unit/Services/PemeriksaanServiceTest.php`:
  - `isKEK(22.5)` → true
  - `isKEK(24.0)` → false
  - `detectStunting()` → deteksi benar berdasarkan BB/TB/usia
- [ ] **8.3.2** `tests/Unit/Services/LaporanServiceTest.php`:
  - Format data statistik dashboard benar
  - Filter laporan bulanan benar
- [ ] **8.3.3** `tests/Unit/Services/JadwalServiceTest.php`:
  - Transisi status jadwal (draft → validated, draft → rejected)
  - Tidak bisa validate jika sudah completed
- [ ] **8.3.4** `tests/Unit/Enums/UserRoleTest.php`:
  - Label enum benar
  - Permission check benar

### 8.4 Feature Tests — Autentikasi

- [ ] **8.4.1** `tests/Feature/Auth/LoginTest.php`:
  - Login berhasil dengan kredensial valid
  - Login gagal dengan password salah
  - Login gagal dengan username tidak ada
  - Rate limit setelah 5 percobaan
  - User inactive tidak bisa login
  - Redirect sesuai role setelah login
- [ ] **8.4.2** `tests/Feature/Auth/LogoutTest.php`:
  - Logout berhasil, session terhapus
  - Akses protected route setelah logout → redirect login
- [ ] **8.4.3** `tests/Feature/Auth/PasswordUpdateTest.php`:
  - Update password berhasil
  - Validasi current password benar

### 8.5 Feature Tests — Module Ibu Hamil

- [ ] **8.5.1** `tests/Feature/IbuHamil/ListIbuHamilTest.php`:
  - Kader/Bidan/Admin bisa akses list
  - Peserta tidak bisa akses (403)
  - Search filter bekerja
  - Pagination bekerja
- [ ] **8.5.2** `tests/Feature/IbuHamil/CreateIbuHamilTest.php`:
  - Kader bisa create
  - Peserta tidak bisa create (403)
  - Validasi NIK 16 digit
  - Validasi NIK unik
  - Upload foto berhasil
  - Data tersimpan ke database
- [ ] **8.5.3** `tests/Feature/IbuHamil/UpdateIbuHamilTest.php`:
  - Update berhasil
  - Validasi NIK unik (ignore self)
- [ ] **8.5.4** `tests/Feature/IbuHamil/DeleteIbuHamilTest.php`:
  - Soft delete berhasil
  - Data tidak muncul di list
  - Tidak benar-benar dihapus dari DB

### 8.6 Feature Tests — Module Balita

- [ ] **8.6.1** `tests/Feature/Balita/CreateBalitaTest.php` (CRUD lengkap)
- [ ] **8.6.2** `tests/Feature/Balita/ListBalitaTest.php`
- [ ] **8.6.3** `tests/Feature/Balita/DeleteBalitaTest.php`

### 8.7 Feature Tests — Module Lansia

- [ ] **8.7.1** `tests/Feature/Lansia/CreateLansiaTest.php`
- [ ] **8.7.2** `tests/Feature/Lansia/ListLansiaTest.php`
- [ ] **8.7.3** `tests/Feature/Lansia/DeleteLansiaTest.php`

### 8.8 Feature Tests — Module Pemeriksaan

- [ ] **8.8.1** `tests/Feature/Pemeriksaan/CreatePemeriksaanTest.php`:
  - Create pemeriksaan ibu hamil
  - Create pemeriksaan balita
  - Create pemeriksaan lansia
  - Validasi field required per kategori
  - Alert KEK tercatat jika LILA < 23.5
- [ ] **8.8.2** `tests/Feature/Pemeriksaan/ListPemeriksaanTest.php`
- [ ] **8.8.3** `tests/Feature/Pemeriksaan/DeletePemeriksaanTest.php`

### 8.9 Feature Tests — Module Jadwal

- [ ] **8.9.1** `tests/Feature/Jadwal/CreateJadwalTest.php`:
  - Kader bisa buat jadwal (status: draft)
  - Peserta tidak bisa buat jadwal
- [ ] **8.9.2** `tests/Feature/Jadwal/ValidasiJadwalTest.php`:
  - Bidan bisa validate jadwal
  - Kader tidak bisa validate
  - Status berubah menjadi validated
  - Notifikasi terkirim setelah validasi
- [ ] **8.9.3** `tests/Feature/Jadwal/RejectJadwalTest.php`

### 8.10 Feature Tests — Laporan

- [ ] **8.10.1** `tests/Feature/Laporan/IbuHamilLaporanTest.php`:
  - Halaman laporan accessible oleh Bidan, Kader, Admin
  - Filter periode bekerja
  - Export PDF response benar (Content-Type: application/pdf)
  - Export Excel response benar
- [ ] **8.10.2** `tests/Feature/Laporan/BalitaLaporanTest.php`
- [ ] **8.10.3** `tests/Feature/Laporan/LansiaLaporanTest.php`

### 8.11 Feature Tests — Notifikasi

- [ ] **8.11.1** `tests/Feature/Notifikasi/NotifikasiTest.php`:
  - Notifikasi terbuat saat jadwal divalidasi
  - Mark as read berhasil
  - Mark all as read berhasil
  - Jumlah notifikasi belum dibaca benar

### 8.12 Coverage & Quality Check

- [ ] **8.12.1** Jalankan `php artisan test --coverage` dan verifikasi ≥ 80%
- [ ] **8.12.2** Identifikasi dan tulis test untuk area yang < 80%
- [ ] **8.12.3** Jalankan `./vendor/bin/phpstan analyse --level=8`
- [ ] **8.12.4** Fix semua PHPStan errors
- [ ] **8.12.5** Jalankan `./vendor/bin/pint` dan commit formatting fixes

---

## FASE 9 — Kualitas Kode & Keamanan

> **Tujuan:** Memastikan keamanan dan kualitas kode sebelum deployment.

### 9.1 Keamanan

- [ ] **9.1.1** Verifikasi CSRF token pada semua POST/PUT/DELETE forms
- [ ] **9.1.2** Implementasi rate limiting di semua endpoint sensitif
- [ ] **9.1.3** Audit semua query Eloquent — pastikan tidak ada raw SQL tanpa binding
- [ ] **9.1.4** Verifikasi semua file upload divalidasi MIME type (tidak hanya ekstensi)
- [ ] **9.1.5** Pastikan file yang diupload tidak bisa dieksekusi (storage di luar public)
- [ ] **9.1.6** Semua NIK dan data sensitif tidak muncul di URL
- [ ] **9.1.7** Implementasi `EnsureUserIsActive` middleware sudah aktif
- [ ] **9.1.8** Verifikasi tidak ada debug info yang bocor di production (`APP_DEBUG=false`)
- [ ] **9.1.9** Tambah security headers di Nginx config:
  - `X-Frame-Options: DENY`
  - `X-Content-Type-Options: nosniff`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Content-Security-Policy` (basic)
- [ ] **9.1.10** Jalankan `php artisan audit` (Laravel Pint) untuk secret detection

### 9.2 Audit Logging

- [ ] **9.2.1** Buat `app/Models/AuditLog.php`
- [ ] **9.2.2** Buat `app/Observers/AuditObserver.php`
- [ ] **9.2.3** Register observer untuk: IbuHamil, Balita, Lansia, Pemeriksaan, User
- [ ] **9.2.4** Log mencatat: user_id, event (created/updated/deleted), old_values, new_values, IP, user_agent
- [ ] **9.2.5** Buat `Pages/Admin/AuditLog/Index.vue` (hanya Admin, filterable)

### 9.3 Performa

- [ ] **9.3.1** Audit semua query Eloquent dengan `debugbar` (dev) — identifikasi N+1
- [ ] **9.3.2** Tambah `with()` eager loading di semua repository yang membutuhkan relasi
- [ ] **9.3.3** Tambah database indexes yang belum ada berdasarkan query patterns
- [ ] **9.3.4** Implementasi caching di `LaporanService` (Redis, TTL 5 menit)
- [ ] **9.3.5** Optimasi gambar sebelum simpan menggunakan `Intervention/Image`
- [ ] **9.3.6** Verifikasi Vite asset chunking (vendor chunk terpisah)
- [ ] **9.3.7** Test Lighthouse score ≥ 80 (performance, accessibility)

### 9.4 Error Handling

- [ ] **9.4.1** Kustomisasi `app/Exceptions/Handler.php`:
  - Return Inertia response untuk 404, 403, 500
- [ ] **9.4.2** Buat `Pages/Errors/404.vue`
- [ ] **9.4.3** Buat `Pages/Errors/403.vue`
- [ ] **9.4.4** Buat `Pages/Errors/500.vue`
- [ ] **9.4.5** Implementasi global error logging (ke file log atau Sentry)

---

## FASE 10 — Deployment & CI/CD

> **Tujuan:** Menyiapkan pipeline otomatis dan lingkungan production.

### 10.1 CI Pipeline (GitHub Actions)

- [ ] **10.1.1** Buat `.github/workflows/ci.yml`:
  - Trigger: push ke `main` dan `develop`, PR ke `main`
  - Jobs: setup PHP, install dependencies, migrate, run tests
- [ ] **10.1.2** Tambah job: PHPStan static analysis
- [ ] **10.1.3** Tambah job: Pint code style check
- [ ] **10.1.4** Tambah job: npm build (verifikasi frontend build tidak error)
- [ ] **10.1.5** Tambah badge CI status di `README.md`

### 10.2 CD Pipeline

- [ ] **10.2.1** Buat `.github/workflows/deploy.yml`:
  - Trigger: push ke `main` setelah CI passed
  - SSH ke server production
  - Pull latest code
  - `composer install --no-dev --optimize-autoloader`
  - `npm run build`
  - `php artisan migrate --force`
  - `php artisan optimize`
  - Restart queue worker
- [ ] **10.2.2** Setup GitHub Secrets: `SERVER_HOST`, `SERVER_USER`, `SERVER_SSH_KEY`

### 10.3 Production Setup

- [ ] **10.3.1** Buat `.env.production.example` (template tanpa nilai sensitif)
- [ ] **10.3.2** Konfigurasi SSL di `docker/prod/nginx.conf`
- [ ] **10.3.3** Test `docker compose -f docker-compose.prod.yml up -d` di staging
- [ ] **10.3.4** Verifikasi semua service healthy: `docker ps`
- [ ] **10.3.5** Jalankan `php artisan migrate --force` di production
- [ ] **10.3.6** Jalankan `php artisan db:seed --class=PosyanduSeeder`
- [ ] **10.3.7** Jalankan `php artisan optimize` (config, route, view cache)
- [ ] **10.3.8** Verifikasi queue worker aktif: `php artisan queue:work` via supervisor
- [ ] **10.3.9** Verifikasi scheduler aktif (scheduler container running)
- [ ] **10.3.10** Test notifikasi jadwal (jalankan command manual)
- [ ] **10.3.11** Setup backup otomatis database (cron job di server atau rclone ke cloud)
- [ ] **10.3.12** Verifikasi SSL certificate aktif (HTTPS)

### 10.4 Monitoring

- [ ] **10.4.1** Setup Laravel Telescope (dev only): `composer require laravel/telescope --dev`
- [ ] **10.4.2** Konfigurasi log rotation di `config/logging.php` (daily, 14 hari)
- [ ] **10.4.3** Setup alert jika queue jobs gagal (via email ke admin)
- [ ] **10.4.4** Setup health check endpoint `/health` (cek DB, Redis connectivity)

---

## FASE 11 — Finalisasi & Dokumentasi

> **Tujuan:** Memastikan sistem siap digunakan dan terdokumentasi dengan baik.

### 11.1 Seeder Production

- [ ] **11.1.1** Buat `database/seeders/ProductionSeeder.php`:
  - Data 8 posyandu Desa Belumbang
  - 1 akun Admin
  - 2 akun Bidan (Ni Wayan Yudiasih, dll.)
  - 8 akun Kader
- [ ] **11.1.2** Verifikasi semua akun bisa login
- [ ] **11.1.3** Verifikasi seluruh flow (pendaftaran peserta → pemeriksaan → laporan)

### 11.2 User Acceptance Testing (UAT)

- [ ] **11.2.1** Test flow lengkap sebagai **Admin**: kelola user, posyandu, lihat laporan global
- [ ] **11.2.2** Test flow lengkap sebagai **Bidan**: validasi jadwal, input pemeriksaan, cetak laporan
- [ ] **11.2.3** Test flow lengkap sebagai **Kader**: tambah peserta, input pemeriksaan, buat jadwal
- [ ] **11.2.4** Test flow lengkap sebagai **Peserta**: login, lihat KMS, lihat jadwal, lihat notifikasi
- [ ] **11.2.5** Test export PDF laporan ibu hamil, balita, lansia
- [ ] **11.2.6** Test export Excel laporan bulanan
- [ ] **11.2.7** Test cetak KMS digital (PDF)
- [ ] **11.2.8** Test di browser: Chrome, Firefox, Safari, Edge
- [ ] **11.2.9** Test di perangkat mobile (iPhone, Android)
- [ ] **11.2.10** Test dengan koneksi lambat (throttle di DevTools)

### 11.3 Dokumentasi

- [ ] **11.3.1** Update `README.md` dengan:
  - Deskripsi proyek
  - Prerequisite (Docker, Git)
  - Quick start (clone, docker up, migrate, seed)
  - Available commands (Makefile)
  - Akun default untuk development
- [ ] **11.3.2** Buat `docs/DEVELOPMENT.md`:
  - Panduan setup environment lengkap
  - Cara menambah fitur baru (step by step: migration → model → repo → service → controller → frontend)
  - Cara menjalankan test
  - Coding conventions
- [ ] **11.3.3** Buat `docs/DEPLOYMENT.md`:
  - Langkah deploy ke server production
  - Rollback procedure
  - Troubleshooting umum
- [ ] **11.3.4** Buat `docs/USER_GUIDE.md`:
  - Panduan penggunaan per role (dengan screenshot)
  - FAQ
- [ ] **11.3.5** Dokumentasikan environment variables di `.env.example` (komentar per variable)

### 11.4 Final Code Review

- [ ] **11.4.1** Review semua controller — pastikan ada policy check di setiap action
- [ ] **11.4.2** Review semua form request — semua input tervalidasi
- [ ] **11.4.3** Review semua model — fillable, hidden, casts sudah benar
- [ ] **11.4.4** Pastikan tidak ada `dd()`, `var_dump()`, `console.log()` yang tertinggal
- [ ] **11.4.5** Pastikan tidak ada hardcoded credentials di source code
- [ ] **11.4.6** Jalankan `php artisan test --coverage` final — verifikasi ≥ 80%
- [ ] **11.4.7** Jalankan `./vendor/bin/phpstan analyse` — verifikasi 0 errors
- [ ] **11.4.8** Jalankan `./vendor/bin/pint` — verifikasi 0 style violations

### 11.5 Go-Live Checklist

- [ ] **11.5.1** `APP_DEBUG=false` di production `.env`
- [ ] **11.5.2** `APP_ENV=production` di production `.env`
- [ ] **11.5.3** Database backup terakhir sebelum go-live
- [ ] **11.5.4** DNS pointing ke server production
- [ ] **11.5.5** SSL certificate aktif dan auto-renew
- [ ] **11.5.6** Semua cron job aktif (scheduler container)
- [ ] **11.5.7** Queue worker aktif (supervisor)
- [ ] **11.5.8** Log rotation aktif
- [ ] **11.5.9** Informasikan user (bidan, kader) tentang akun mereka
- [ ] **11.5.10** Lakukan demo / pelatihan singkat kepada petugas Posyandu

---

## Progress Tracker

| Fase                               | Total Task | Selesai | Progress  |
| ---------------------------------- | ---------- | ------- | --------- |
| Fase 0 — Setup Infrastruktur       | 39         | 38      | 🟢 97%    |
| Fase 1 — Fondasi Aplikasi          | 49         | 49      | 🟢 100%   |
| Fase 2 — Autentikasi & Otorisasi   | 30         | 30      | 🟢 100%   |
| Fase 3 — Module Master Data        | 64         | 64      | 🟢 100%   |
| Fase 4 — Module Pemeriksaan        | 26         | 26      | 🟢 100%   |
| Fase 5 — Jadwal & Notifikasi       | 31         | 31      | 🟢 100%   |
| Fase 6 — Laporan & Dashboard       | 30         | 21      | 🟡 70%    |
| Fase 7 — Frontend & UI/UX          | 27         | 0       | 🔴 0%     |
| Fase 8 — Testing                   | 53         | 0       | 🔴 0%     |
| Fase 9 — Kualitas & Keamanan       | 22         | 0       | 🔴 0%     |
| Fase 10 — Deployment & CI/CD       | 17         | 0       | 🔴 0%     |
| Fase 11 — Finalisasi & Dokumentasi | 28         | 0       | 🔴 0%     |
| **TOTAL**                          | **416**    | **259** | **🟡 62%** |

---

## Dependency Antar Fase

```
Fase 0 (Setup)
    └── Fase 1 (Fondasi)
            └── Fase 2 (Auth)
                    └── Fase 3 (Master Data)
                            ├── Fase 4 (Pemeriksaan)
                            │       └── Fase 6 (Laporan)
                            └── Fase 5 (Jadwal)
                                    └── Fase 6 (Laporan)
                                            └── Fase 7 (Frontend Polish)
                                                    └── Fase 8 (Testing)
                                                            └── Fase 9 (QA & Security)
                                                                    └── Fase 10 (Deploy)
                                                                            └── Fase 11 (Final)
```

> **Catatan:** Fase 7 (Frontend & UI/UX) bisa dikerjakan paralel dengan Fase 3-6.  
> Fase 8 (Testing) bisa ditulis bersamaan dengan setiap modul (TDD approach).

---

_Checklist ini diperbarui seiring perkembangan implementasi. Tandai setiap task dengan `[x]` saat selesai._

**Dibuat:** Maret 2026 | **Referensi:** SRS SIPOS v1.0.0
