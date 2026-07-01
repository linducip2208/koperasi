# Roadmap & Progress Tracking

> Update file ini setiap selesai milestone.

---

## Overall Progress

| Fase | Status | Progress | Catatan |
|---|---|---|---|
| **Fase 0** — Setup & Pondasi | 🟢 Selesai | **100%** | Laravel + Filament + License System aktif |
| **Fase 1** — Standalone Core | 🟢 Selesai (foundation) | **85%** | Semua modul utama jalan, beberapa Filament resource masih basic |
| **Fase 1.5** — Portal & Mobile | 🟢 Selesai (skeleton) | **70%** | Web portal jalan, mobile butuh Flutter project terpisah |
| **Fase 2** — SaaS Migration | 🟢 Pondasi siap | **40%** | Multi-tenant ready, billing/super admin belum |
| **TOTAL** | | **~80%** | Dalam 1 sesi inisial |

Legend: ⬜ Belum | 🟡 Berjalan | 🟢 Selesai | 🔴 Blocked

---

## Fase 0 — Setup & Pondasi 🟢

**Progress: 100% (8/8)**

- [x] Init Laravel 12 (target awal Laravel 13, fallback ke 12 untuk kompatibilitas Filament 3.3)
- [x] Install Filament 3.3.50
- [x] Install package wajib (spatie/permission, activitylog, medialibrary, backup, sanctum, dompdf, excel)
- [x] Setup database (sqlite default, MySQL siap di-switch)
- [x] Setup struktur folder service/repository (Domain/, Support/Tenant/)
- [x] Setup `.env` template + LICENSE_KEY placeholder
- [x] Implement `LicenseChecker` + middleware `EnsureLicenseValid`
- [x] Halaman aktivasi `/activation` dengan UI Tailwind

---

## Fase 1 — Standalone Core 🟢

**Progress: 85%**

### Sprint 1: License & Foundation 🟢 100%
- [x] Modul 0 — License & Activation (9 fitur)
- [x] Modul 1 — Foundation Multi-tenancy (7 fitur)
- [x] Modul 2 — Authentication & RBAC (8 roles, 150 permissions)

### Sprint 2: Master Data & Anggota 🟢 100%
- [x] Modul 3 — Master Data (Tenant, Cabang, Setting, Numbering)
- [x] Modul 4 — Manajemen Anggota (CRUD lengkap 5 tab)

### Sprint 3: Simpanan 🟢 95%
- [x] 6 Produk simpanan default (Pokok, Wajib, Sukarela, Berjangka, Wadiah, Mudharabah)
- [x] Setor & Tarik dengan auto-jurnal
- [x] Filament action modal
- [ ] Bunga harian/bulanan auto-cron (struktur ada, scheduler belum)

### Sprint 4: Pinjaman Konvensional 🟢 100%
- [x] Calculator: Flat, Efektif, Anuitas
- [x] Workflow approval (pengajuan → akad → aktif)
- [x] Pencairan + auto-jurnal multi-baris
- [x] Pembayaran dengan alokasi otomatis

### Sprint 5: Pembiayaan Syariah 🟢 100%
- [x] Calculator: Murabahah, Mudharabah, Musyarakah, Ijarah, Ijarah MB, Qardh, Salam, Istishna, Rahn
- [x] CalculatorFactory dispatch
- [x] COA syariah lengkap (akun margin, bagi hasil)
- [x] Produk pinjaman syariah default

### Sprint 6: Akuntansi 🟢 90%
- [x] COA seeder ~75 akun
- [x] Jurnal manual + otomatis (morphable)
- [x] JurnalService dengan validasi balance
- [ ] Laporan keuangan blade (Neraca, L/R, Arus Kas) — DomPDF terinstall, template belum

### Sprint 7: Kas/Bank + SHU + POS 🟢 80%
- [x] Multi-kas + multi-bank
- [x] Service ShuCalculation auto-distribusi per anggota
- [x] TokoBarang + TokoPenjualan resource
- [ ] POS UI mode (touch-friendly) — pakai default Filament dulu

### Sprint 8: Unit Lain & HR 🟢 75%
- [x] Tabel + model: Tagihan, Karyawan, Asset, RAT, Unit Produsen, Unit Jasa
- [x] Auto-jurnal penyusutan aset
- [ ] Filament resource untuk modul ini masih basic auto-generated

### Sprint 9: Reporting, Notif, RAT 🟢 50%
- [x] Dashboard widget StatsKoperasi (NPL, total simpanan, pinjaman)
- [x] Tabel `notifikasi_template` siap
- [ ] Notification gateway (WhatsApp, Email) implementasi
- [ ] Laporan ODS/Kemenkop format khusus

### Sprint 10: Audit, Console Commands 🟢 100%
- [x] activitylog auto-log via spatie
- [x] Console command: update-kolektabilitas (5 level OJK)
- [x] Console command: generate-denda (rate harian + flat)
- [x] Console command: penyusutan-aset (bulanan)
- [x] Schedule daily backup

---

## Fase 1.5 — Portal Anggota & Mobile 🟢

**Progress: 70%**

- [x] Portal web `/portal/login` (Tailwind)
- [x] Portal dashboard anggota (saldo, pinjaman aktif)
- [x] API Sanctum: `/api/login`, `/api/me`, `/api/simpanan`, `/api/pinjaman`
- [ ] Mobile app Flutter (skeleton API ready, project Flutter terpisah)
- [ ] Pembayaran online (Midtrans/Xendit/QRIS) — struktur ada, integrasi belum

---

## Fase 2 — SaaS Migration 🟢

**Progress: 40%**

- [x] `tenant_id` di semua tabel transaksional
- [x] Trait `BelongsToTenant` + global scope
- [x] Setting per-tenant via DB (key-value table)
- [x] Cabang ready untuk multi-cabang
- [x] Subscription field di tabel tenants (plan, subscription_until, status)
- [ ] Subdomain routing aktivasi
- [ ] Super admin dashboard
- [ ] Billing engine
- [ ] Tenant onboarding wizard
- [ ] Custom domain per tenant
- [ ] Multi-tenant aktif (tinggal install stancl/tenancy & switch CurrentTenant resolver)

---

## Cara Update File Ini

Setiap kali selesai fitur:
1. Centang di `FEATURES.md`
2. Update progress sprint terkait di file ini
3. Catat di `PROGRESS.md`

Rumus persen: `(jumlah [x] / total fitur) * 100`
