# KoperasiApp — Deployment Guide

Panduan deploy KoperasiApp ke production server (VPS / dedicated).

---

## Requirements

| Item | Minimum | Recommended |
|------|---------|-------------|
| PHP | 8.2+ | 8.3+ |
| MySQL | 8.0+ | 8.0+ / MariaDB 11+ |
| Node.js | 18+ | 20+ |
| Composer | 2+ | 2+ |
| Redis | 7.x (optional) | 7.x |
| OS | Ubuntu 22.04 | Ubuntu 24.04 |
| RAM | 2 GB | 4 GB |
| Storage | 20 GB SSD | 40 GB SSD NVMe |

---

## Production Setup (Quick Start)

### 1. Clone & Install Dependencies

```bash
cd /var/www
git clone <repo-url> koperasi
sudo chown -R www-data:www-data koperasi
cd koperasi

sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data npm install
```

### 2. Configure .env

```bash
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate
sudo -u www-data nano .env
```

**Key settings:**
```
APP_NAME="KoperasiApp"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koperasi
DB_USERNAME=koperasi
DB_PASSWORD=<strong-password>

QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=<smtp-host>
MAIL_PORT=587
MAIL_USERNAME=<username>
MAIL_PASSWORD=<password>
MAIL_FROM_ADDRESS="noreply@your-domain.com"

LICENSE_DEV_BYPASS=false

# WhatsApp Notification (Fonnte)
WHATSAPP_DRIVER=fonnte
FONNTE_API_KEY=<your-fonnte-api-key>
FONNTE_SENDER=6281296052010
```

### 3. Create Database

```sql
mysql -u root -p

CREATE DATABASE koperasi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'koperasi'@'localhost' IDENTIFIED BY '<strong-password>';
GRANT ALL PRIVILEGES ON koperasi.* TO 'koperasi'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Migrate & Seed

```bash
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force
```

### 5. Build Frontend Assets

```bash
sudo -u www-data npm run build
```

### 6. Set Permissions

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 775 public/build
```

### 7. Link Storage

```bash
sudo -u www-data php artisan storage:link
```

### 8. Optimize

```bash
sudo -u www-data php artisan optimize
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan event:cache
```

---

## Nginx Configuration

Copy `deploy/nginx.conf` to `/etc/nginx/sites-available/koperasi`:

```bash
sudo cp deploy/nginx.conf /etc/nginx/sites-available/koperasi
sudo nano /etc/nginx/sites-available/koperasi  # edit server_name
sudo ln -s /etc/nginx/sites-available/koperasi /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## Supervisor Configuration

For queue workers and scheduler:

```bash
sudo cp deploy/supervisor.conf /etc/supervisor/conf.d/koperasi.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start koperasi-worker:*
sudo supervisorctl start koperasi-scheduler:*
```

---

## SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
sudo certbot renew --dry-run
```

---

## Scheduled Tasks (Cron)

Add to crontab (`crontab -e`):

```
* * * * * cd /var/www/koperasi && php artisan schedule:run >> /dev/null 2>&1
```

Scheduled tasks include:
- `koperasi:update-kolektabilitas` — daily 01:00 (5-level OJK kolektabilitas)
- `koperasi:generate-denda` — daily 01:30 (late fee auto-generation)
- `koperasi:penyusutan-aset` — monthly 1st (asset depreciation)
- `backup:run --only-db` — daily 03:00
- `backup:run` — weekly Sunday 04:00
- `backup:clean` — daily 05:00
- `backup:monitor` — daily 06:00
- `koperasi:reminder-angsuran --days=3` — daily 08:00
- `koperasi:reminder-angsuran --days=1` — daily 08:30

---

## Default Admin Credentials

After running `db:seed`, login at `/admin`:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@koperasi.local | admin123 |

---

## Post-Deployment Checklist

- [ ] `.env` configured with production values
- [ ] `APP_DEBUG=false`
- [ ] `LICENSE_DEV_BYPASS=false`
- [ ] Database migrated and seeded
- [ ] Nginx configured with SSL
- [ ] Supervisor running queue workers
- [ ] Cron job active for scheduler
- [ ] Storage symlinked
- [ ] Firewall: only ports 80, 443 open
- [ ] Backup strategy active
- [ ] License activated via `/activation`

---

## Troubleshooting

**500 Error after deploy:**
```bash
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan optimize
sudo systemctl reload nginx
```

**Queue jobs not processing:**
```bash
sudo supervisorctl status koperasi-worker:*
sudo supervisorctl restart koperasi-worker:*
```

**Login redirect loop:**
```bash
sudo -u www-data php artisan session:table  # if using database sessions
sudo -u www-data php artisan migrate
```

**License pair wizard not working:**
Make sure `marketplace.public.pem` exists in `public/` directory and the domain is configured in the marketplace dashboard at whitelabel.co.id.
