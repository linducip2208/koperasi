# Project Koperasi — Vision & Scope

> **Status proyek:** Fase Perencanaan
> **Tanggal mulai:** 2026-04-27
> **Update terakhir:** 2026-04-27

---

## 1. Vision

Membangun **aplikasi Koperasi Serba Usaha (KSU) berbasis Laravel** yang:
- **Lengkap** — mencakup semua jenis unit usaha koperasi (simpan-pinjam, konsumen, produsen, jasa)
- **Dual-mode** — bisa dijalankan sebagai **konvensional** ATAU **syariah** (per koperasi atau per produk)
- **Whitelabel/lisensi** — dijual sebagai source code yang dikunci per-domain via API `whitelabel.co.id`
- **Upgradeable to SaaS** — arsitektur sejak awal tenant-aware, sehingga upgrade ke multi-tenant SaaS tidak perlu rewrite

---

## 2. Strategi Dua Fase

### Fase 1 — Standalone (sekarang)
- 1 instalasi = 1 koperasi
- Dijual sebagai source code berlisensi
- Lisensi per-domain (regular = 1 domain, extended = 3 domain)
- Validasi lisensi tiap boot, fallback offline via HMAC checksum
- Auto-update check ke API whitelabel

### Fase 1.5 — Portal Anggota & Mobile (lanjutan standalone)
- Portal web anggota (login, cek saldo, ajukan pinjaman, dll)
- Mobile app anggota (Flutter / API-based)
- Mobile app kolektor (untuk pinjaman lapangan)

### Fase 2 — SaaS Multi-tenant (nanti)
- 1 instalasi melayani banyak koperasi
- Subdomain per tenant + opsi custom domain
- Plan, subscription, billing
- Super admin dashboard
- Tenant onboarding wizard

---

## 3. Keputusan Final

| Topik | Keputusan |
|---|---|
| **Target koperasi** | Koperasi Serba Usaha (semua jenis unit) |
| **Skala awal** | 10.000 anggota per instalasi |
| **Mode operasi** | Dual: konvensional + syariah (bisa keduanya per koperasi) |
| **Portal anggota & mobile** | Fase 1.5 (setelah core fase 1 stabil) |
| **Admin panel** | Filament 3 (atau versi terbaru kompatibel) |
| **Framework** | Laravel 13 (target) — fallback Laravel 12 jika ekosistem belum siap |
| **Lisensi** | API `whitelabel.co.id` per `LICENSE_API.md` |
| **Multi-tenant ready** | Ya, struktur DB & service layer sejak Fase 1 |

---

## 4. Dual-Mode Konvensional + Syariah — Bagaimana?

**Bisa dipilih dua-duanya per koperasi**, dengan pendekatan:

1. **Setting tingkat koperasi**: `operation_mode` = `konvensional` | `syariah` | `dual`
2. **Setting tingkat produk**: tiap produk simpanan/pinjaman punya `akad_type`
3. **Strategy Pattern** di service layer:
   - `InterestCalculator` interface
   - Implementasi: `FlatCalc`, `EffectiveCalc`, `AnuityCalc` (konvensional)
   - Implementasi: `MurabahahCalc`, `MudharabahCalc`, `MusyarakahCalc`, `IjarahCalc` (syariah)
4. **UI Filament** menampilkan field & istilah sesuai mode
   - Konvensional: "Bunga", "Cicilan"
   - Syariah: "Margin/Bagi Hasil", "Angsuran"
5. **Akuntansi**: COA disesuaikan (ada akun khusus syariah seperti dana ZIS, dana kebajikan)

Koperasi mode `dual` bisa punya **produk konvensional dan syariah berdampingan** (umum di KSU besar).

---

## 5. Stack Teknologi

Lihat detail di [`ARCHITECTURE.md`](ARCHITECTURE.md).

Ringkas:
- **Backend**: Laravel 13 + PHP 8.3+
- **Admin**: Filament 3 (atau versi kompatibel Laravel 13)
- **DB**: MySQL 8 / PostgreSQL 15
- **Frontend non-admin**: Livewire 3 + Tailwind
- **Multi-tenancy**: `stancl/tenancy` (mode single-tenant dulu)
- **RBAC**: `spatie/laravel-permission`
- **Mobile (Fase 1.5)**: Flutter consume Laravel API

---

## 6. File Tracking Proyek

| File | Isi |
|---|---|
| [`PROJECT.md`](PROJECT.md) | Vision, scope, keputusan (file ini) |
| [`FEATURES.md`](FEATURES.md) | Daftar lengkap semua fitur dengan checklist status |
| [`ROADMAP.md`](ROADMAP.md) | Timeline fase + persen progress |
| [`ARCHITECTURE.md`](ARCHITECTURE.md) | Stack, struktur, design pattern |
| [`PROGRESS.md`](PROGRESS.md) | Log harian/per-sesi pekerjaan |
| [`LICENSE_API.md`](LICENSE_API.md) | Spek API lisensi whitelabel.co.id |

---

## 7. Aturan Kerja

1. **Setiap selesai modul/fitur** → update checklist di `FEATURES.md` dan persen di `ROADMAP.md`
2. **Setiap sesi kerja** → catat di `PROGRESS.md`
3. **Keputusan teknis penting** → catat di `ARCHITECTURE.md`
4. **Jangan skip dokumentasi** — ini fondasi proyek panjang
