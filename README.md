# SIPOS — Sistem Informasi Posyandu Berbasis Web

<div align="center">

[![CI Pipeline](https://github.com/servinr/sipos/actions/workflows/ci.yml/badge.svg)](https://github.com/servinr/sipos/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**Sistem Informasi Posyandu untuk Desa Belumbang, Kecamatan Kerambitan, Kabupaten Tabanan, Bali**

[Fitur](#-fitur) • [Teknologi](#-teknologi-stack) • [Quick Start](#-quick-start) • [Dokumentasi](#-dokumentasi) • [Kontribusi](#-kontribusi)

</div>

---

## 📋 Deskripsi

SIPOS adalah sistem informasi berbasis web yang dirancang khusus untuk mengelola data dan kegiatan Posyandu di Desa Belumbang. Sistem ini menggantikan proses pencatatan manual dengan solusi digital yang terintegrasi, mencakup:

- **Data Master**: Ibu Hamil, Balita, Lansia, Kader, Bidan, Posyandu
- **Pemeriksaan Kesehatan**: Pencatatan hasil pemeriksaan dengan deteksi dini KEK dan stunting
- **Jadwal Posyandu**: Pengelolaan jadwal kegiatan dengan sistem validasi
- **Laporan**: Generate laporan PDF/Excel untuk pelaporan ke Puskesmas
- **KMS Digital**: Kartu Menuju Sehat digital untuk peserta
- **Notifikasi**: Sistem notifikasi untuk jadwal dan pengingat

## ✨ Fitur Utama

### 👥 Multi-Role Access
- **Admin**: Full access management
- **Bidan**: Input pemeriksaan, validasi jadwal, cetak laporan
- **Kader**: Pendaftaran peserta, input data, pengelolaan jadwal
- **Peserta**: Lihat KMS digital, riwayat pemeriksaan, jadwal

### 📊 Health Monitoring
- Deteksi dini **KEK** (Kurang Energi Kronis) untuk ibu hamil
- Deteksi **Stunting** untuk balita berdasarkan BB/TB
- Monitoring **Hipertensi** untuk lansia
- Grafik pertumbuhan dan perkembangan kesehatan

### 📱 Modern UI/UX
- Responsive design (mobile, tablet, desktop)
- Dark mode support (coming soon)
- Accessibility features
- Real-time notifications

## 🛠️ Teknologi Stack

### Backend
| Technology | Version | Description |
|------------|---------|-------------|
| PHP | 8.3+ | Server-side runtime |
| Laravel | 11.x | Web application framework |
| Laravel Breeze | Latest | Authentication scaffolding |
| Inertia.js | 2.x | SSR adapter |
| MariaDB | 11.x | Primary database |
| Redis | 7.x | Cache & queue driver |
| Pest PHP | 3.x | Testing framework |

### Frontend
| Technology | Version | Description |
|------------|---------|-------------|
| Vue.js | 3.x | JavaScript framework (Composition API) |
| Vite | 6.x | Build tool |
| shadcn-vue | Latest | UI components |
| Tailwind CSS | 4.x | Utility-first CSS |
| Pinia | 3.x | State management |
| Chart.js | Latest | Data visualization |
| Lucide Icons | Latest | Icon library |

### Infrastructure
| Technology | Description |
|------------|-------------|
| Docker & Docker Compose | Containerization |
| Nginx | Web server (production) |
| Supervisor | Process manager for queue workers |
| Let's Encrypt | SSL certificate |

## 🚀 Quick Start

### Prerequisites
- Docker & Docker Compose installed
- Git installed
- Node.js 20+ (for local development without Docker)
- PHP 8.3+ (for local development without Docker)

### 1. Clone Repository
```bash
git clone https://github.com/servinr/sipos.git
cd sipos
```

### 2. Setup Environment
```bash
cp .env.example .env
# Edit .env sesuai kebutuhan (database credentials, dll)
```

### 3. Development dengan Docker
```bash
# Build dan jalankan containers
docker compose -f docker-compose.dev.yml up -d --build

# Install dependencies
docker exec -it sipos_app_dev composer install
docker exec -it sipos_app_dev npm install

# Generate application key
docker exec -it sipos_app_dev php artisan key:generate

# Jalankan migrasi dan seeder
docker exec -it sipos_app_dev php artisan migrate:fresh --seed

# Jalankan vite dev server
docker exec -it sipos_app_dev npm run dev
```

Akses aplikasi di: **http://localhost:8080**

### 4. Development tanpa Docker
```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install

# Generate application key
php artisan key:generate

# Setup database
php artisan migrate:fresh --seed

# Jalankan development server
php artisan serve

# Jalankan vite di terminal lain
npm run dev
```

### 5. Login Credentials

| Role | Username | Password | Redirect |
|------|----------|----------|----------|
| Admin | `admin` | `password` | /dashboard |
| Bidan | `bidan` | `password` | /dashboard |
| Kader | `kader` | `password` | /dashboard |
| Peserta | `peserta` | `password` | /portal |

## 📚 Dokumentasi

Dokumentasi lengkap tersedia di direktori [`docs/`](docs/):

| Dokumen | Deskripsi |
|---------|-----------|
| [SRS.md](docs/SRS.md) | Software Requirements Specification |
| [TASK.md](docs/TASK.md) | Implementation Task Checklist |
| [DEPLOYMENT.md](docs/DEPLOYMENT.md) | Production Deployment Guide |
| [CONTRIBUTING.md](CONTRIBUTING.md) | Contribution Guidelines |

### Additional Documentation
- [API Documentation](docs/API.md) - API endpoints reference
- [Database Schema](docs/DATABASE.md) - ERD and schema details
- [User Guide](docs/USER_GUIDE.md) - End user manual

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run unit tests only
php artisan test --testsuite=Unit

# Run feature tests only
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/Auth/AuthenticationTest.php
```

## 📦 Available Commands

```bash
# Code quality
composer lint          # Run Pint (code style fixer)
composer lint:check    # Check code style
composer phpstan       # Run static analysis

# Frontend
npm run dev           # Development server
npm run build         # Build for production
npm run lint          # Run ESLint
npm run format        # Format with Prettier

# Database
php artisan migrate           # Run migrations
php artisan migrate:fresh     # Fresh migration
php artisan migrate:seed      # Seed database
php artisan db:seed           # Run seeders

# Cache
php artisan optimize          # Cache everything
php artisan optimize:clear    # Clear all caches

# Queue
php artisan queue:work        # Start queue worker
php artisan queue:failed      # List failed jobs
php artisan queue:retry all   # Retry all failed jobs
```

## 🏗️ Project Structure

```
sipos/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Request handlers
│   │   ├── Requests/         # Form requests
│   │   ├── Resources/        # API resources
│   │   └── Middleware/       # HTTP middleware
│   ├── Models/               # Eloquent models
│   ├── Repositories/         # Data access layer
│   ├── Services/             # Business logic
│   ├── DTOs/                 # Data transfer objects
│   ├── Enums/                # Type-safe enums
│   ├── Policies/             # Authorization policies
│   ├── Observers/            # Model observers
│   └── Providers/            # Service providers
├── resources/
│   ├── js/
│   │   ├── Pages/            # Inertia pages
│   │   ├── Components/       # Vue components
│   │   ├── Layouts/          # Layout components
│   │   ├── Composables/      # Vue composables
│   │   └── stores/           # Pinia stores
│   └── views/                # Blade templates
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/              # Database seeders
│   └── factories/            # Model factories
├── tests/
│   ├── Unit/                 # Unit tests
│   └── Feature/              # Feature tests
├── docs/                     # Documentation
└── docker/                   # Docker configuration
```

## 🔒 Security

SIPOS implements industry-standard security practices:

- Password hashing with bcrypt
- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS prevention via auto-escaping
- Rate limiting on authentication endpoints
- Role-based access control (RBAC)
- Audit logging for sensitive operations
- HTTPS enforcement in production

## 🤝 Kontribusi

Kami sangat terbuka untuk kontribusi! Silakan baca [CONTRIBUTING.md](CONTRIBUTING.md) untuk panduan lengkap.

### Quick Contribution Guide
1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/amazing-feature`)
3. Commit perubahan (`git commit -m 'feat: add amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

### Commit Convention
Kami menggunakan [Conventional Commits](https://www.conventionalcommits.org/):
- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation
- `style:` - Code style
- `refactor:` - Refactoring
- `test:` - Tests
- `chore:` - Maintenance

## 📄 License

SIPOS is open-sourced software licensed under the [MIT License](LICENSE).

## 👨‍💻 Developer

**Servin Rudianto**  
NIM: 2201010565  
Institut Bisnis dan Teknologi Indonesia (INSTIKI)  
Program Studi: Informatika

## 📞 Contact & Support

- Email: admin@sipos.belumbang.id
- Project Link: https://github.com/servinr/sipos

---

<div align="center">

**SIPOS v1.0.0** | Maret 2026

Made with ❤️ for Posyandu Desa Belumbang

</div>
