# Progress Log

## 2026-10-07 — Sesi Penuntasan II: SHU payout, penjamin, locale, AI settings

- SHU `bagikan()`: kredit sukarela + jurnal ringkas/chunk + idempotent + kunci status; aksi Setujui/Bagikan di resource
- Approval calon anggota diperbaiki (status calon, bukan kategori); filter + badge + Today's Actions
- Penjamin di agunan; locale session via settings + middleware; AI provider via settings + section Pengaturan
- Test 129 OK (519 assertion)

---

- Ledger single-query + cache + flush (neraca/LR 1 query; budget 12 query); whereDate/driver-safe; DATEDIFF → PHP
- AI Generic HTTP (endpoint/model/key via settings) + fallback lokal; AiInsight tetap berlabel
- i18n nav 24 halaman (`HasTranslatedNav` + id/en), Tabler theme layer resmi Filament
- Penjamin di agunan (migrasi + relation manager); SHU snapshot+lock; idempotency offline-ready
- Installer: fix fatal `$this->middleware()` → guard (test 2/2); verifikasi QR publik; API transaksi/notifikasi
- Pint: file baru bersih; Test 127 OK (512 assertion)

---

- Executive Dashboard: Today's Actions (approval pending, jatuh tempo hari ini, dokumen expiring, temuan critical, lisensi)
- Verifikasi kartu publik (`/portal/verifikasi`, signed + throttle + masking) + tautan di kartu
- API: `/api/v1/transaksi` (paginasi, milik sendiri) + `/api/v1/notifikasi` di v1 & legacy
- Idempotency offline-ready: kolom unik + `device_id` di simpanan/pinjaman pembayaran; service + portal + PPOB/webhook dukung; docs API
- SHU: snapshot di `meta` + kunci hitung-ulang bila disetujui/distribusi
- DATEDIFF → PHP diffInDays (driver-safe); Credit Scoring UI; Procurement terima-barang; en.json +15 keys
- Test 125 OK (FinalHardeningTest 6 baru)

---

### Navigasi
- Grup Filament baru: DASHBOARD, OPERASIONAL, ACCOUNTING, REPORTS, OPERATIONS, GOVERNANCE, SYSTEM (ganti 73 resource/page, permission-aware tetap)

### Kredit & koleksi
- Simulasi Pinjaman (semua kalkulator, jadwal integer), CreditScoringService (8 faktor, configurable, tested)
- Collection Center: bucket 1–7…180+, assign kolektor, follow-up + PTP + bukti, WA reminder, performa; migrasi `kolektor_id` + `collection_followups`

### Workflow & approval & pengadaan
- WorkflowService generik (transisi + izin + audit); Approval Center (pinjaman/pembayaran/pengadaan/calon anggota); Procurement + resource + nomor PR

### Dokumen, OCR, audit, compliance
- MemberDocument + resource (upload aman, random filename, versi, expiry, unduh via controller privat); DocumentController::memberDoc (IDOR + traversal guard)
- OCR abstraction (interface + manual + propose-tanpa-timpa); AuditFinding + resource; Compliance checklist (settings)

### Akuntansi & laporan baru
- Year-End Closing wizard (checklist + job + lock tahun); Buku: kas-forecast, anggaran-realisasi, valuasi persediaan (registry 33)
- Portal statement PDF + tombol unduh; API notifikasi anggota

### Quality, fraud, search, notif, webhook
- Data Quality (8 cek: NIK ganda, jurnal bocor, …); Fraud rule-based (besar/malam/duplikat/reversal, driver-aware); Universal Search (permission-aware); Notification Center (activity); Webhook Center (status + replay)
- Fix driver: whereDate, DATEDIFF/julianday, HOUR/strftime; seed jurnal balance; index hotpath

### Dead-UI & CDN
- href="#"/Coming Soon dibersihkan (semua beraksi nyata); grep CDN views/js/css = NOL

### Test
- 119 OK (458 assertion): CollectionWorkflowTest 6 + ReportCenterTest 12 + suite lama; `reports:health` 33/33 OK

---

### Arsitektur
- `CooperativeContext` — ganti semua `Tenant::find(1)` (8 titik) + view-share
- `app/Reports/`: ReportDefinition/Filter/Result/Registry(30)/Runner(audit)/Exporter(CSV stream/Excel/PDF)/Archiver(checksum)/CustomReportRunner(whitelist)
- `ReportPermissionSeeder` (aman rerun), `ReportArchive/SavedReport/ScheduledReport/CustomReport/ImportBatch` + migrasi
- Filament: ReportCenter (search/favorit/recent), ReportViewer (filter dinamis + chips + chart + export), ExecutiveDashboard (9 KPI + periode), Member360, ExportCenter, ReportArchive, ScheduledReports, CustomBuilder, AiInsight
- API `/api/v1/reports*` + legacy alias; commands `reports:list|health|generate|scheduled` (scheduler 06:30)
- Import Center: engine CSV/XLSX (delimiter/BOM/ID-number/date), preview valid/invalid/duplikat + Error CSV, atomic 500/batch + jurnal penyeimbang, queue >1000 (`ProcessImport`), template per tipe
- ProfitSharingCalculator (integer + largest-remainder + snapshot) + AiManager (interface + local-heuristic + sanitasi + label)
- Portal: statement PDF + tombol unduh di transaksi
- Tabler lokal (@tabler/core + chart.js via npm), `koperasi.css` design system (tema koperasi), portal pakai tabler-app, admin dapat kop-* — 0 CDN di views
- Fix nyata: groupSaldo roll-up akun anak; whereDate tahan-driver (sqlite artifact); SeedDemoData jurnal kini balance berbaris; NIK unique; index hotpath; SecurityHeaders; installer/license bypass RequirePair; CSRF-except webhook
- Portal statement PDF; error pages; i18n id/en reports; docs reports/import-export/syariah; README produk
- Test: 113 OK (444 assertion) termasuk ReportCenterTest 12 (neraca balance, profit sharing, import, archive immutable, custom whitelist, AI sanitize, API auth)

---

### SAK EP (baru)
- `LaporanKeuanganService::perubahanEkuitas()` + `::calk()` — SAK EP koperasi
- Route `/laporan/perubahan-ekuitas`, `/laporan/calk` (+ Excel), kartu baru di page admin
- View PDF `laporan/perubahan-ekuitas.blade.php`, `laporan/calk.blade.php`

### E-RAT (baru)
- Model `RatKehadiran` + `Rat::refreshQuorum()` (arsip selesai dipertahankan, live dari baris hadir)
- `RatController`: QR check-in `/rat/{id}/checkin` + Buku Tahunan `/rat/{id}/buku-tahunan` (SAK EP + SHU + voting + hadir)
- `RatVotingResource` admin (dulu stub kosong) + aksi QR & Buku Tahunan di `RatResource`
- Portal voting diperketat: periode, opsi valid, anggota aktif, anti-double
- Command `koperasi:rat-sync-quorum` (100 RAT disinkron) + `RatVotingSeeder` (2 voting contoh)

### Stabilisasi operasional
- Denda: `GenerateDenda` → delegasi ke `PinjamanService::hitungDendaHarian` (single source) + dukung `denda_flat_per_hari`
- Schedule: tambah `koperasi:update-kolektabilitas` daily 01:30 (sebelumnya belum terjadwal)
- Test baru `tests/Feature/SakEpRatTest.php` (5 test) — full suite: **88 test, 383 assertion, OK**

---

## 2026-04-27 — Sesi Inisial: Build Lengkap All Phases

### Sesi 1: Foundation (Fase 0–2 core)
- Init Laravel 12 + Filament 3.3.50 + 7 package wajib
- 11 migrasi → 50+ tabel, 33 model, 8 seeders (89 COA + 6 produk simpanan + 9 produk pinjaman + 8 roles + 150 permissions)
- License system terintegrasi `whitelabel.co.id` (validate, activate, revoke, version-check + offline checksum HMAC)
- Multi-tenant ready: trait `BelongsToTenant` + `CurrentTenant` + middleware resolver
- 9 calculator (3 konvensional + 6 syariah) + factory pattern — diverify via tinker
- 3 service utama (PinjamanService, SimpananService, JurnalService) dengan auto-jurnal
- 19 Filament resource + Dashboard widget (NPL, total simpanan, pinjaman aktif)
- Portal Anggota web + API Sanctum (mobile-ready)
- 3 console command + scheduler harian/bulanan

### Sesi 2: Enhancement (lanjutan)
- ✅ Customize 13 Filament resource: ProdukSimpanan, ProdukPinjaman, Coa, Kas, Tenant, User, Tagihan, Karyawan, Asset, Rat, TokoBarang, Jurnal — semua tab Indonesia + filter + actions
- ✅ Laporan Keuangan PDF: Service `LaporanKeuanganService` + 3 blade template (Neraca, Laba/Rugi, Arus Kas) via DomPDF, plus Filament page `LaporanKeuangan`
- ✅ 6 Relation Manager: Anggota → AhliWaris/Simpanan/Pinjaman, Pinjaman → Jadwal/Pembayaran, Simpanan → Transaksi
- ✅ WhatsApp Gateway (Fonnte + WAblas) + NotifikasiService (reminder angsuran, konfirmasi setoran, approval pinjaman)
- ✅ Console command `koperasi:reminder-angsuran --days=N` + schedule H-3 & H-1
- ✅ Filament Setting page `Pengaturan Sistem` (WA gateway + persen SHU + POS settings)
- ✅ Landing page SEO lengkap di `/` — meta title/description/keywords, OpenGraph, Twitter Card, 3 schema.org JSON-LD (SoftwareApplication, Organization, FAQPage), 18 fitur showcase, 3 paket harga, 6 FAQ accordion, floating WhatsApp, **CTA WA: 0812-9605-2010**
- ✅ Sitemap.xml dinamis + robots.txt dengan disallow admin/portal/activation/api

### Verified Live
| Endpoint | Status | Size |
|---|---|---|
| `/` (Landing SEO) | ✅ HTTP 200 | 36 KB |
| `/sitemap.xml` | ✅ HTTP 200 | 1 KB |
| `/robots.txt` | ✅ HTTP 200 | 138 B |
| `/admin/login` (Filament) | ✅ HTTP 200 | 43 KB |
| `/portal/login` | ✅ HTTP 200 | 2 KB |

### Stats Final

| Kategori | Jumlah |
|---|---|
| Migrasi | 11 file (50+ tabel) |
| Model Eloquent | 33 |
| Filament Resource | 19 + 6 Relation Manager + 2 Page Custom |
| Domain Service | 6 (Pinjaman, Simpanan, Jurnal, SHU, LaporanKeuangan, NotifikasiWA) |
| Calculator | 9 (Flat, Efektif, Anuitas, Murabahah, Mudharabah, Musyarakah, Ijarah, IjarahMB, Qardh) |
| Console Command | 4 (kolektabilitas, denda, susut aset, reminder angsuran) |
| Scheduled Job | 6 (daily kolektabilitas + denda + 2x reminder, monthly susut, daily backup) |
| Routes | API 4 + Web 9 + Filament admin (40+) |
| Default Data | 89 COA, 6 produk simpanan, 9 produk pinjaman, 8 roles, 150 permissions, 2 kas, 1 tenant, 1 admin |

### Cara Akses

```
Landing (publik):     http://localhost:8000/
Admin Panel:          http://localhost:8000/admin
                      Login: admin@koperasi.local / admin123
Portal Anggota:       http://localhost:8000/portal/login
Aktivasi Lisensi:     http://localhost:8000/activation
Laporan Keuangan PDF: http://localhost:8000/admin → Laporan → Laporan Keuangan
SEO:                  /sitemap.xml, /robots.txt
API Mobile (Sanctum): POST /api/login, GET /api/me, /simpanan, /pinjaman
```

### Kontak Sales (di Landing)
WhatsApp: **0812-9605-2010** (auto-link via wa.me)

### Dokumentasi
- `PROJECT.md` — Vision & scope
- `FEATURES.md` — 336 fitur dengan checklist
- `ROADMAP.md` — Progress per fase
- `ARCHITECTURE.md` — Tech decisions
- `LICENSE_API.md` — Spec API lisensi
- `PROGRESS.md` — File ini
