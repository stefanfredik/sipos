# Deployment Guide - SIPOS

## Prerequisites

- Server dengan Ubuntu 22.04 LTS atau higher
- Minimum 2GB RAM, 2 CPU cores
- 20GB storage
- Domain name (optional but recommended)
- SSL certificate (Let's Encrypt)

---

## 1. Server Setup

### 1.1 Install Required Packages

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3 and extensions
sudo add-apt-repository ppa:ondrej/php -y
sudo apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring \
    php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-intl \
    php8.3-soap unzip git curl nginx redis-server supervisor

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js 20
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 1.2 Configure PHP-FPM

Edit `/etc/php/8.3/fpm/php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
memory_limit = 256M
max_execution_time = 300
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.3-fpm
```

---

## 2. Database Setup

```bash
# Install MariaDB
sudo apt install -y mariadb-server mariadb-client

# Secure installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE sipos_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sipos_prod'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON sipos_production.* TO 'sipos_prod'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 3. Application Deployment

### 3.1 Clone Repository

```bash
sudo mkdir -p /var/www
cd /var/www
sudo git clone https://github.com/your-org/sipos.git
sudo chown -R www-data:www-data sipos
cd sipos
```

### 3.2 Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies
npm ci

# Build frontend assets
npm run build
```

### 3.3 Environment Configuration

```bash
# Copy production environment file
cp .env.production.example .env

# Generate application key
php artisan key:generate

# Edit .env with actual values
nano .env
```

### 3.4 Run Migrations

```bash
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder
```

### 3.5 Storage Setup

```bash
# Create storage link
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 3.6 Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 4. Nginx Configuration

Create `/etc/nginx/sites-available/sipos`:

```nginx
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name sipos.belumbang.id;
    root /var/www/sipos/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/sipos.belumbang.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/sipos.belumbang.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name sipos.belumbang.id;
    return 301 https://$server_name$request_uri;
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/sipos /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 5. SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d sipos.belumbang.id

# Auto-renewal is configured automatically
# Test renewal
sudo certbot renew --dry-run
```

---

## 6. Queue Worker Setup

Create `/etc/supervisor/conf.d/sipos-worker.conf`:

```ini
[program:sipos-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sipos/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/sipos/storage/logs/worker.log
stopwaitsecs=3600
```

Start worker:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start sipos-worker:*
```

---

## 7. Scheduler Setup

Edit crontab:
```bash
sudo crontab -e
```

Add:
```cron
* * * * * cd /var/www/sipos && php artisan schedule:run >> /dev/null 2>&1
```

---

## 8. Backup Configuration

Create backup script `/usr/local/bin/sipos-backup.sh`:

```bash
#!/bin/bash

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/sipos"
DB_NAME="sipos_production"
DB_USER="sipos_prod"
DB_PASS="your_password"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/sipos/storage/app

# Keep only last 30 days
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completed: $DATE"
```

Make executable and add to cron:
```bash
sudo chmod +x /usr/local/bin/sipos-backup.sh
sudo crontab -e
```

Add daily backup at 2 AM:
```cron
0 2 * * * /usr/local/bin/sipos-backup.sh >> /var/log/sipos-backup.log 2>&1
```

---

## 9. Monitoring Setup

### 9.1 Log Rotation

Create `/etc/logrotate.d/sipos`:

```
/var/www/sipos/storage/logs/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    create 0664 www-data www-data
    sharedscripts
    postrotate
        systemctl reload php8.3-fpm
    endscript
}
```

### 9.2 Health Check Endpoint

Create route in `routes/web.php`:
```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
    ]);
})->middleware('throttle:10,1');
```

---

## 10. Post-Deployment Checklist

- [ ] Verify database migrations ran successfully
- [ ] Test login with all user roles
- [ ] Verify file upload functionality
- [ ] Test queue worker (send test notification)
- [ ] Verify scheduler is running (check logs)
- [ ] Test SSL certificate (https://www.ssllabs.com/ssltest/)
- [ ] Configure monitoring/alerting
- [ ] Setup error tracking (Sentry, etc.)
- [ ] Document credentials in secure vault
- [ ] Test backup and restore procedure

---

## 11. Troubleshooting

### Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/sipos
sudo chmod -R 775 /var/www/sipos/storage /var/www/sipos/bootstrap/cache
```

### Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

### Check Logs
```bash
tail -f /var/www/sipos/storage/logs/laravel.log
journalctl -u php8.3-fpm -f
journalctl -u nginx -f
```

### Queue Issues
```bash
# Check queue status
sudo supervisorctl status sipos-worker

# Restart workers
sudo supervisorctl restart sipos-worker:*

# Check failed jobs
php artisan queue:failed
```

---

## 12. Rollback Procedure

```bash
cd /var/www/sipos

# Get previous commit
git log --oneline -5

# Checkout to previous version
git checkout <previous-commit-hash>

# Restore dependencies
composer install --optimize-autoloader --no-dev
npm run build

# Clear caches
php artisan optimize:clear

# Restart services
sudo supervisorctl restart sipos-worker:*
sudo systemctl reload nginx
```

---

**Last Updated:** March 2026
**Version:** 1.0.0
