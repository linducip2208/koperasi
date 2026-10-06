# Backup, Restore & Update

## Backup

Otomatis (scheduler): DB harian 03:00, full mingguan, clean + monitor. Manual aman: `php artisan app:backup` (DB) / `app:backup --full`. **Wajib sebelum update & operasi destruktif.** Retensi + kap 5 GB di `config/backup.php`; simpan salinan offsite (S3/GDrive) untuk production.

## Restore

Tidak ada tombol restore web (berbahaya). Prosedur aman:

```bash
php artisan down
php artisan app:backup              # amankan kondisi saat ini
# ekstrak arsip spatie ke database (mysql/pgsql client) + storage/app
php artisan migrate --force
php artisan app:health && php artisan test
php artisan up
```

## Update

Admin → Update Center (versi terpasang vs terbaru via `app:update-check`, release notes) atau CLI. Alur wajib: `app:backup` → `down` → update files → `migrate --force` → `app:health` + `test` → `up`. Jangan auto-update tanpa backup.
