# SIPOS — Sistem Informasi Posyandu Berbasis Web

## Deskripsi
Sistem Informasi Posyandu (SIPOS) berbasis web yang dikembangkan menggunakan Laravel 11, Inertia.js, Vue 3, dan shadcn-vue. Dokumen acuan: `docs/SRS.md` dan `docs/TASK.md`.

## Prerequisite
1. Docker & Docker Compose
2. Git

## Quick Start
1. Clone repository ini.
2. Setup environment variables: `cp .env.example .env` (sesuaikan konfigurasi untuk db, dsb).
3. Jalankan development environment dengan Docker:
   ```bash
   docker compose -f docker-compose.dev.yml up -d --build
   ```
4. Install dependencies backend dan frontend:
   ```bash
   docker exec -it sipos_app_dev composer install
   docker exec -it sipos_app_dev npm install
   ```
5. Generate application key:
   ```bash
   docker exec -it sipos_app_dev php artisan key:generate
   ```
6. Jalankan migrasi dan seeder:
   ```bash
   docker exec -it sipos_app_dev php artisan migrate:fresh --seed
   ```
7. Jalankan vite dev server:
   ```bash
   docker exec -it sipos_app_dev npm run dev
   ```

## Akun Default
- Admin: admin / password
- Bidan: bidan / password
- Kader: kader / password
- Peserta: peserta / password

Silakan lihat kontribusi di `CONTRIBUTING.md`.
