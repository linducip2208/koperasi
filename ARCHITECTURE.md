# Architecture & Tech Stack

> Catat keputusan teknis penting di sini supaya tidak lupa kenapa memilih sesuatu.

---

## 1. Tech Stack

| Layer | Pilihan | Versi | Alasan |
|---|---|---|---|
| Framework | **Laravel** | 13 (target) / 12 (fallback) | Ekosistem Indonesia kuat, Filament siap |
| PHP | **PHP** | 8.3+ | Required Laravel 12/13 |
| Admin Panel | **Filament** | 3.x atau 4.x kompatibel | Cepat, modern, RBAC siap |
| Frontend non-admin | **Livewire** | 3.x | Konsisten dengan Filament |
| Styling | **Tailwind CSS** | 3.x | Standard Filament & Livewire |
| Database | **MySQL** | 8.x | Familiar, skala 10k anggota cukup |
| Cache & Queue | **Redis** | 7.x | Untuk queue & cache lisensi |
| Search (opt) | **Meilisearch** | latest | Search anggota & transaksi |
| Mobile (P1.5) | **Flutter** | 3.x | Cross-platform Android+iOS |

### Packages Wajib

```
spatie/laravel-permission       # RBAC
spatie/laravel-activitylog      # Audit trail
spatie/laravel-medialibrary     # File/foto management
spatie/laravel-backup           # Backup otomatis
stancl/tenancy                  # Multi-tenancy (single-tenant mode dulu)
filament/filament               # Admin panel
filament/spatie-laravel-media-library-plugin
filament/spatie-laravel-translatable-plugin (opt)
maatwebsite/excel               # Import/export Excel
barryvdh/laravel-dompdf         # PDF generation
laravel/sanctum                 # API auth (mobile, P1.5)
livewire/livewire               # Interactive UI
pestphp/pest                    # Testing
larastan/larastan               # Static analysis
laravel/pint                    # Code style
```

---

## 2. Strategi Multi-tenancy (Upgradeable)

**Prinsip:** Tulis kode seolah multi-tenant sejak hari pertama, tapi jalankan single-tenant dulu.

### Implementasi
1. Install `stancl/tenancy` dengan **single-database, single-tenant mode**
2. Tambah kolom `tenant_id` di setiap tabel transaksional (default `1`)
3. Buat trait `BelongsToTenant`:
   ```php
   trait BelongsToTenant {
       protected static function bootBelongsToTenant() {
           static::creating(fn($m) => $m->tenant_id ??= app('current_tenant_id'));
           static::addGlobalScope('tenant', fn($q) => $q->where('tenant_id', app('current_tenant_id')));
       }
   }
   ```
4. Service container binding `current_tenant_id` → default `1` di Fase 1
5. Storage path:
   ```php
   storage_path("app/tenants/{$tenantId}/uploads/...")
   ```
6. Cache & queue prefix per tenant
7. Setting per-tenant via tabel `settings` (key, value, tenant_id)

### Saat Upgrade ke Fase 2 SaaS
- Aktifkan `stancl/tenancy` multi-tenant mode
- Switch driver ke multi-database atau row-level
- Tenant resolver dari subdomain/domain
- Migrasi data existing → tenant pertama (id=1)
- **Tidak perlu rewrite model/service**, karena scope sudah jalan

---

## 3. Strategi Dual-Mode Konvensional + Syariah

### Setting
- Per koperasi: `operation_mode` = `konvensional` | `syariah` | `dual`
- Per produk simpanan/pinjaman: `akad_type` (untuk dual mode)

### Strategy Pattern
```
app/Domain/Calculation/
├── InterestCalculator.php        (interface)
├── Konvensional/
│   ├── FlatCalculator.php
│   ├── EffectiveCalculator.php
│   └── AnuityCalculator.php
└── Syariah/
    ├── MurabahahCalculator.php
    ├── MudharabahCalculator.php
    ├── MusyarakahCalculator.php
    ├── IjarahCalculator.php
    └── QardhCalculator.php
```

### Factory
```php
$calc = CalculatorFactory::for($product->akad_type);
$schedule = $calc->generateSchedule($principal, $rate, $tenor);
```

### UI Filament
- Trait `MorphsToAkad` di resource → tampilkan field sesuai `akad_type`
- Konvensional: "Bunga (%)" / Syariah: "Margin (%)" atau "Nisbah (%)"
- Konvensional: "Cicilan" / Syariah: "Angsuran"

### COA
- Mode konvensional: COA standar koperasi
- Mode syariah: ada akun tambahan (Dana Kebajikan, Dana ZIS, Pendapatan Margin, dll)
- Mode dual: gabungan keduanya

---

## 4. Struktur Folder

```
app/
├── Console/
├── Domain/                      # Domain logic per modul
│   ├── Anggota/
│   ├── Simpanan/
│   ├── Pinjaman/
│   │   ├── Konvensional/
│   │   └── Syariah/
│   ├── Akuntansi/
│   ├── Kas/
│   ├── SHU/
│   ├── POS/
│   ├── Produsen/
│   ├── Jasa/
│   ├── Tagihan/
│   ├── Asuransi/
│   ├── HR/
│   ├── Asset/
│   └── License/
├── Filament/
│   ├── Resources/               # CRUD per modul
│   ├── Pages/                   # Custom pages
│   ├── Widgets/                 # Dashboard widgets
│   └── Clusters/                # Group resources per modul
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   │   └── EnsureLicenseValid.php
│   └── Requests/
├── Models/                      # Eloquent models
├── Policies/
├── Providers/
├── Services/
│   └── LicenseChecker.php
└── Support/
    └── Tenant/                  # BelongsToTenant trait, dll

config/
├── license.php                  # Konfigurasi LicenseChecker
├── tenancy.php
└── filament.php

database/
├── migrations/
│   └── 2026_xx_xx_create_tenants_table.php
└── seeders/
    ├── DefaultTenantSeeder.php
    ├── ChartOfAccountsSeeder.php
    └── RolesSeeder.php

resources/
├── views/
│   ├── filament/
│   ├── activation/              # Halaman aktivasi lisensi
│   └── portal/                  # Portal anggota (P1.5)
└── lang/
    ├── id/                      # Bahasa Indonesia (default)
    └── en/

routes/
├── web.php
├── api.php                      # Untuk mobile (P1.5)
└── activation.php               # Routes aktivasi/lisensi

tests/
├── Feature/
└── Unit/
```

---

## 5. Penamaan & Konvensi

- **Bahasa**: kode pakai English (model, method), UI pakai Bahasa Indonesia
- **Migration**: snake_case Indonesia jika nama domain Indonesia (`anggota`, `simpanan`, `pinjaman`)
- **Mata uang**: simpan sebagai integer (rupiah, tanpa desimal) atau bigint
- **Tanggal**: pakai `Carbon`, format display Indonesia (`d M Y`)
- **Format angka**: `Rp 1.000.000` (titik ribuan, koma desimal)

---

## 6. Performance untuk Skala 10.000 Anggota

- Index pada: `nomor_anggota`, `tenant_id`, `created_at`, foreign keys
- Eager loading di Filament (`->with()`)
- Pagination wajib (Filament default 50)
- Cache: laporan, dashboard, master data jarang berubah
- Queue: notifikasi, generate laporan PDF, import Excel
- Soft delete dengan index pada `deleted_at`
- Database connection pooling
- Untuk laporan akhir tahun (SHU 10k anggota): pakai job batched

---

## 7. License Integration (sesuai LICENSE_API.md)

```
Boot Flow:
1. AppServiceProvider::boot()
2. Skip jika local/testing
3. LicenseChecker::validate()
   ├─ Cache hit (6 jam)? → return cached
   ├─ Call API /validate
   │   ├─ Success → cache + return true
   │   └─ Fail → fallback ke checksum offline
   └─ Both fail → abort(403, halaman aktivasi)
```

### Halaman Aktivasi
- Route: `/activation` (tidak di-lock middleware)
- Form: input `activation_key`
- Submit → call `/api/license/activate`
- Sukses → simpan checksum di `storage/license.sig`, redirect ke admin

### Update Checker
- Cron harian: call `/api/version/check`
- Tampil banner di dashboard admin jika ada update
- Download URL → manual update (Fase 2: auto-update wizard)

---

## 8. Database — Skema Inti

Semua tabel transaksional punya:
```sql
id, tenant_id, created_at, updated_at, deleted_at, created_by, updated_by
```

Tabel inti:
```
tenants                  (id=1 default Fase 1)
users                    (admin & staff)
roles, permissions       (spatie)
anggota                  (data anggota)
anggota_status_log
produk_simpanan          (jenis simpanan + akad_type)
simpanan                 (rekening simpanan per anggota)
simpanan_transaksi
produk_pinjaman          (jenis pinjaman + akad_type)
pinjaman                 (akad pinjaman per anggota)
pinjaman_jadwal          (schedule angsuran)
pinjaman_pembayaran
pinjaman_jaminan
coa                      (chart of accounts)
jurnal
jurnal_detail
kas
kas_transaksi
toko_barang
toko_stok
toko_transaksi
shu_perhitungan
shu_distribusi
karyawan
gaji
asset
... (banyak lagi)
settings                 (key-value per tenant)
activity_log             (spatie)
```

Detail ERD akan dibuat saat sprint masing-masing.

---

## 9. Testing Strategy

- **Pest** untuk unit + feature test
- Wajib test untuk:
  - Perhitungan bunga/margin (semua method)
  - Perhitungan SHU
  - Validasi lisensi (mock HTTP)
  - Workflow approval pinjaman
  - Auto-jurnal
- Coverage target: 70% untuk domain logic
- E2E manual untuk POS (sulit otomatisasi)

---

## 10. Deployment

**Target Fase 1**: VPS sederhana (4GB RAM, 2 vCPU) — cukup untuk 10k anggota.

```
Nginx → PHP-FPM 8.3 → Laravel
        ├─ MySQL 8 (lokal)
        ├─ Redis (queue + cache)
        └─ Supervisor (queue worker, schedule)
```

Backup harian: `spatie/laravel-backup` ke S3/GDrive.

---

## 11. Catatan Versi Laravel 13

Per April 2026, Laravel 13 baru rilis Q1 2026. **Risiko**:
- Filament mungkin belum semua ekosistem siap
- Beberapa package community mungkin masih support Laravel 12

**Strategi**:
1. Init coba Laravel 13 dulu
2. Cek `composer require filament/filament`
3. Jika ada konflik mayor → fallback ke Laravel 12
4. Update keputusan di sini setelah cek

---

## 12. Keputusan Arsitektur (Decision Log)

| Tanggal | Keputusan | Alasan |
|---|---|---|
| 2026-04-27 | Pakai Filament untuk admin | Hemat 60% waktu vs custom Blade |
| 2026-04-27 | Multi-tenant ready sejak Fase 1 | Hindari rewrite saat upgrade SaaS |
| 2026-04-27 | Dual-mode syariah/konvensional | Target pasar lebih luas, KSU sering campuran |
| 2026-04-27 | Mobile = Flutter | Cross-platform, ekosistem matang |
| 2026-04-27 | Mata uang = integer rupiah | Hindari floating point error |
