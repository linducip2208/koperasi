# Daftar Lengkap Fitur Aplikasi Koperasi

> Status checklist: `[ ]` = belum, `[~]` = sedang dikerjakan, `[x]` = selesai
> Phase: `P1` = Fase 1 standalone, `P1.5` = Portal/Mobile, `P2` = SaaS

**Total modul:** 35+ | **Total Filament Resource:** 38+ | **Total fitur:** 400+

---

## ⭐ Update 2026-04-28 — Sesi Penambahan Besar

### 🆕 Filament Resource Baru (15 resource — 12 model baru)
- ✅ **Asuransi Mikro** (3 resource): `AsuransiProduk`, `AsuransiPolis`, `AsuransiKlaim` — produk asuransi jiwa/kredit/kesehatan/kebakaran/kendaraan/tabungan, polis per anggota, klaim dengan workflow approval
- ✅ **Payroll Karyawan** (`Gaji`): slip gaji bulanan dengan auto-calc bruto/netto, tunjangan, lembur, BPJS, PPh 21
- ✅ **Anggaran/Budget Tahunan** (`Anggaran`): budget per COA per bulan, total tahunan auto-sum
- ✅ **Rekonsiliasi Bank** (`RekonsiliasiBank`): match saldo buku vs rekening koran, log selisih
- ✅ **Kas Opname** (`KasOpname`): hitung fisik kas vs sistem, audit trail
- ✅ **Pinjaman Restrukturisasi** (`PinjamanRestrukturisasi`): rescheduling/restructuring/reconditioning/top-up/haircut
- ✅ **Periode Akuntansi** (`PeriodeAkuntansi`): tutup buku per bulan, lock data setelah closed
- ✅ **Notifikasi Template** (`NotifikasiTemplate`): admin edit template WA/Email/SMS/Push pakai variable {nama}, {nominal}, {saldo}
- ✅ **Numbering Setting** (`NumberingSetting`): format penomoran custom per dokumen ({prefix}{ymd}{seq:5})
- ✅ **Toko Master Data** (4 resource): `TokoKategori`, `TokoSupplier`, `TokoSatuan`, `TokoPembelian` (sebelumnya admin ga bisa input via UI)
- ✅ **TagihanMaster**: master iuran wajib (bulanan/tahunan/sekali bayar) dengan auto-potong simpanan opsional
- ✅ **SimpananBlokir**: track blokir simpanan untuk jaminan, klaim asuransi, sengketa

### 🆕 Fitur Public-Facing Baru
- ✅ **Pendaftaran Anggota Online** (`/daftar`): form publik tanpa login → simpan ke DB sebagai status "calon" + bikin akun User dengan password sementara → admin approve via Filament `AnggotaResource` → ubah status ke "aktif"
- ✅ **Public Loan Simulator** (`/simulasi-pinjaman`): kalkulator cicilan 12 metode + JSON-LD WebApplication schema + CTA WhatsApp
- ✅ **QR Login Portal Anggota**: scan kartu QR → auto login portal (signed URL 5 tahun expiry)

### 🆕 Fitur Akuntansi & Operasional Baru
- ✅ **Excel Export Laporan**: Neraca/Laba-Rugi/Arus Kas via maatwebsite/excel + filter per cabang/produk
- ✅ **PDF Export Laporan** custom layout dengan dompdf — `?download=1` mode
- ✅ **Activity Log Audit Trail** (spatie/laravel-activitylog) di Anggota/Pinjaman/Simpanan dengan diff JSON viewer
- ✅ **WhatsApp Trigger Otomatis** (3 Observer): SimpananTransaksi setor → notifySetoran, Pinjaman tanggal_pencairan → notifyPencairan, PinjamanPembayaran created → konfirmasi bayar
- ✅ **Payment Gateway Dynamic Adapter** (per CLAUDE.md global): 3 format generic (redirect/qris/virtual_account), `PaymentManager`, model `PaymentProvider` dengan API key Crypt-encrypted
- ✅ **System Health Widget** di KustomDashboard: 6 indicator (DB, WA, Backup, License, Sesi Aktif, Mode Operasi) dengan animated pulse

### 🔧 Fix Resource Stub Sebelumnya Kosong
- ✅ `TokoPenjualanResource` (POS Kasir): repeater item belanja, 6 metode bayar, auto-fill harga dari TokoBarang
- ✅ `ShuPerhitunganResource`: form 7 persentase distribusi dengan auto-recalc
- ✅ `UnitJasaOrderResource`: FK ke layanan + anggota + kas
- ✅ `UnitProdusenSetoranResource`: FK ke anggota + komoditi + kas, auto-fill harga
- ✅ Sinkronisasi 12 akad syariah konsisten antara Filament + pSEO + CalculatorFactory

### 📊 Database Demo Data
- ~236.000+ records total
- 27+ tabel master/operasional masing-masing 100 records (run `php artisan koperasi:seed-100`)
- 5.000 anggota + 5.001 users
- 15.000 simpanan + 45.000 transaksi
- 8.000 pinjaman + 4.800 pembayaran + 154.830 jadwal cicilan

### 🗂️ Filament Resource Lengkap (38+)
**Anggota & Simpan-Pinjam (8)**: Anggota, Simpanan, SimpananBlokir, Pinjaman, PinjamanRestrukturisasi, ProdukSimpanan, ProdukPinjaman, Tagihan
**Toko & Unit Usaha (8)**: TokoBarang, TokoKategori, TokoSupplier, TokoSatuan, TokoPenjualan (POS), TokoPembelian, UnitJasaLayanan, UnitJasaOrder, UnitProdusenKomoditi, UnitProdusenSetoran
**Akuntansi (7)**: Coa, Jurnal, Kas, KasOpname, RekonsiliasiBank, Anggaran, PeriodeAkuntansi
**HRD & Asset (3)**: Karyawan, Gaji (Payroll), Asset
**Asuransi (3)**: AsuransiProduk, AsuransiPolis, AsuransiKlaim
**SHU & RAT (2)**: ShuPerhitungan, Rat
**Pengaturan (6)**: Tenant, User, ActivityLog, NotifikasiTemplate, NumberingSetting, PaymentProvider, TagihanMaster

---

**Total modul:** 25 | **Total fitur:** ~336 | **Selesai (foundation/code):** ~95%

> Status update 2026-04-28: Hampir semua tabel ada UI Filament. Selanjutnya tinggal polish UX & integrasi external (PPOB, mobile app).

---

## 0. License & Activation `[P1]`

- [ ] Service `LicenseChecker` (validate, activate, revoke, version check)
- [ ] Halaman aktivasi awal (form input activation_key)
- [ ] Middleware boot-time check
- [ ] Cache validasi 6 jam
- [ ] Offline fallback via HMAC checksum (`storage/license.sig`)
- [ ] Halaman error lisensi (graceful, bukan crash)
- [ ] Auto-check update di dashboard admin
- [ ] Notifikasi update tersedia
- [ ] Form revoke domain (untuk migrasi server)

---

## 1. Foundation Multi-tenancy `[P1]` (hidden, siap upgrade SaaS)

- [ ] Install `stancl/tenancy` mode single-tenant
- [ ] `tenant_id` di semua tabel transaksional
- [ ] Trait `BelongsToTenant` + global scope
- [ ] Default tenant seeder (id=1)
- [ ] Storage path per-tenant (`storage/app/tenants/{id}/`)
- [ ] Cache & queue prefix per-tenant
- [ ] Setting per-tenant via DB (bukan `.env`)

---

## 2. Authentication & RBAC `[P1]`

- [ ] Login admin, kasir, manajer, pengurus, pengawas
- [ ] Login anggota (Phase 1.5 — pakai guard berbeda)
- [ ] Spatie permission (role + permission)
- [ ] Default roles: super-admin, admin, manajer, kasir, AO (Account Officer), kolektor, pengawas
- [ ] 2FA (Google Authenticator)
- [ ] Forgot password / reset password
- [ ] Force password change pertama login
- [ ] Login history & device tracking
- [ ] IP whitelist (admin)
- [ ] Session timeout config

---

## 3. Master Data `[P1]`

### Profil Koperasi
- [ ] Data koperasi: nama, badan hukum, NIK Koperasi, NPWP, akta pendirian
- [ ] Logo, kop surat, ttd digital pengurus
- [ ] Alamat, kontak, email, website
- [ ] Pengurus & pengawas (struktur organisasi)

### Setting Operasional
- [ ] Mode operasi: konvensional | syariah | dual
- [ ] Mata uang & format angka
- [ ] Tahun buku aktif
- [ ] Periode tutup buku
- [ ] Hari libur / kalender kerja
- [ ] Setting denda keterlambatan
- [ ] Setting biaya admin (provisi, materai, asuransi)
- [ ] Setting bunga simpanan (per produk)
- [ ] Setting bunga/margin pinjaman (per produk)
- [ ] Setting metode perhitungan bunga (flat/menurun/anuitas)
- [ ] Setting bagi hasil syariah (nisbah)

### Wilayah & Cabang
- [ ] Provinsi, kota, kecamatan, kelurahan (master Indonesia)
- [ ] Cabang & kantor kas (siap Phase 2 multi-cabang)

---

## 4. Manajemen Anggota `[P1]`

- [ ] CRUD anggota lengkap
- [ ] Field: NIK, KTA, nama, TTL, JK, alamat, telp, email, NPWP
- [ ] Foto profil + scan KTP, KK, dokumen pendukung
- [ ] Ahli waris (multiple)
- [ ] Pekerjaan, penghasilan, sumber dana
- [ ] Kategori anggota: biasa, luar biasa, calon, kehormatan
- [ ] Status: aktif, tidak aktif, keluar, meninggal, dikeluarkan
- [ ] Riwayat status (audit trail)
- [ ] Upload foto/dokumen
- [ ] Cetak KTA (kartu anggota)
- [ ] Cetak sertifikat keanggotaan
- [ ] Import massal dari Excel/CSV
- [ ] Export anggota ke Excel
- [ ] Halaman ringkasan anggota (saldo terpadu, riwayat)
- [ ] Pendaftaran online (Phase 1.5)
- [ ] Auto-generate nomor anggota (format custom)

---

## 5. Simpanan `[P1]`

### Jenis Simpanan
- [ ] Simpanan Pokok (sekali setor saat masuk)
- [ ] Simpanan Wajib (rutin bulanan)
- [ ] Simpanan Sukarela (bebas)
- [ ] Simpanan Berjangka / Deposito
- [ ] Simpanan Khusus (qurban, haji, umroh, pendidikan, hari raya, idul fitri)
- [ ] Simpanan Wadiah (syariah)
- [ ] Simpanan Mudharabah (syariah, bagi hasil)

### Transaksi
- [ ] Setoran (cash, transfer, potong gaji)
- [ ] Penarikan (dengan approval untuk nominal besar)
- [ ] Mutasi antar simpanan
- [ ] Auto-debit angsuran pinjaman
- [ ] Auto-debit iuran wajib

### Bunga & Bagi Hasil
- [ ] Perhitungan bunga harian (saldo harian)
- [ ] Perhitungan bunga bulanan (saldo akhir/rata-rata)
- [ ] Distribusi bagi hasil mudharabah
- [ ] Pajak bunga PPh 23 (auto-potong)
- [ ] Posting bunga otomatis ke jurnal

### Lain
- [ ] Block/hold saldo (jaminan pinjaman)
- [ ] Buku tabungan cetak
- [ ] Statement / mutasi rekening (PDF)
- [ ] Tutup rekening
- [ ] Auto-tutup buku akhir bulan

---

## 6. Pinjaman / Pembiayaan `[P1]`

### Konvensional
- [ ] Pinjaman Produktif
- [ ] Pinjaman Konsumtif
- [ ] Pinjaman Multiguna
- [ ] Metode bunga flat
- [ ] Metode bunga menurun (efektif)
- [ ] Metode anuitas

### Syariah
- [ ] Murabahah (jual-beli dengan margin)
- [ ] Mudharabah (bagi hasil)
- [ ] Musyarakah (kemitraan)
- [ ] Ijarah (sewa)
- [ ] Ijarah Muntahiya Bittamlik (sewa-beli)
- [ ] Qardh (pinjaman kebajikan tanpa bunga)
- [ ] Rahn (gadai)
- [ ] Salam (pesanan)
- [ ] Istishna (pesanan barang produksi)

### Workflow
- [ ] Pengajuan pinjaman (form lengkap)
- [ ] Survey lapangan (form + foto)
- [ ] Analisa kredit (5C: Character, Capacity, Capital, Collateral, Condition)
- [ ] Credit scoring sederhana (riwayat anggota)
- [ ] Workflow approval multi-level (kasir → AO → manajer → ketua)
- [ ] Komite kredit (untuk nominal besar)
- [ ] Akad / kontrak (template + cetak)
- [ ] Tanda tangan digital
- [ ] Pencairan (cash, transfer, ke simpanan)

### Operasional
- [ ] Auto-generate jadwal angsuran
- [ ] Pembayaran angsuran (full, sebagian, multi-angsuran)
- [ ] Denda keterlambatan otomatis
- [ ] Pelunasan dipercepat (dengan diskon margin syariah)
- [ ] Restrukturisasi (perpanjangan, perubahan nominal)
- [ ] Top-up pinjaman
- [ ] Take-over dari koperasi/bank lain

### Manajemen Risiko
- [ ] Klasifikasi kolektabilitas (Lancar, DPK, Kurang Lancar, Diragukan, Macet)
- [ ] Auto-update kolektabilitas berdasar tunggakan hari
- [ ] PPAP (Penyisihan Penghapusan Aktiva Produktif)
- [ ] Hapus buku
- [ ] Hapus tagih
- [ ] Penagihan (assign kolektor, log kunjungan)

### Jaminan / Agunan
- [ ] Master jenis agunan (BPKB, sertifikat, simpanan, deposito, emas)
- [ ] Valuasi agunan (LTV ratio)
- [ ] Foto & dokumen agunan
- [ ] Riwayat agunan (masuk, keluar, eksekusi)

---

## 7. Akuntansi `[P1]`

### Setup
- [ ] Chart of Accounts (COA) default lengkap koperasi
- [ ] COA versi syariah (akun khusus)
- [ ] Custom akun (tambah/edit)
- [ ] Pemetaan akun otomatis (akun simpanan, pinjaman, kas, dll)

### Jurnal
- [ ] Jurnal umum manual
- [ ] Jurnal otomatis dari simpanan
- [ ] Jurnal otomatis dari pinjaman & angsuran
- [ ] Jurnal otomatis dari kas/bank
- [ ] Jurnal otomatis dari toko/POS
- [ ] Jurnal otomatis dari penyusutan
- [ ] Jurnal penyesuaian akhir periode
- [ ] Jurnal balik
- [ ] Posting & unposting
- [ ] Audit trail jurnal

### Buku & Laporan
- [ ] Buku besar
- [ ] Neraca saldo
- [ ] Neraca lajur
- [ ] Tutup buku bulanan
- [ ] Tutup buku tahunan
- [ ] Neraca (laporan posisi keuangan)
- [ ] Laba/Rugi
- [ ] Arus Kas (langsung)
- [ ] Arus Kas (tidak langsung)
- [ ] Perubahan Ekuitas
- [ ] CALK (Catatan atas Laporan Keuangan)
- [ ] Format laporan SAK ETAP
- [ ] Format laporan PSAK 27 (lama, opsional)
- [ ] Format laporan PSAK Syariah

### Anggaran
- [ ] Setup anggaran tahunan
- [ ] Realisasi vs anggaran
- [ ] Variance analysis

---

## 8. Kas & Bank `[P1]`

- [ ] Multi-kas (kas besar, kas kecil per cabang/teller)
- [ ] Multi-rekening bank koperasi
- [ ] Penerimaan kas (per kategori)
- [ ] Pengeluaran kas (per kategori)
- [ ] Transfer antar kas
- [ ] Setor ke bank
- [ ] Tarik dari bank
- [ ] Rekonsiliasi bank manual
- [ ] Import statement bank (CSV) untuk rekonsiliasi
- [ ] Kas opname harian
- [ ] Buku kas harian
- [ ] Saldo per kas / bank realtime
- [ ] Limit saldo kasir (warning)

---

## 9. SHU (Sisa Hasil Usaha) `[P1]`

- [ ] Setting komponen SHU (% jasa modal, jasa anggota, dana cadangan, pendidikan, sosial, pengurus, karyawan)
- [ ] Hitung SHU otomatis akhir tahun buku
- [ ] Rekap kontribusi per anggota (simpanan + transaksi)
- [ ] Distribusi SHU per anggota
- [ ] Opsi distribusi: ke simpanan sukarela / tunai / tahan
- [ ] Slip SHU per anggota (cetak/PDF)
- [ ] Laporan rekap SHU untuk RAT
- [ ] Posting SHU ke jurnal otomatis

---

## 10. Toko / POS (Unit Konsumen) `[P1]`

### Master
- [ ] Master barang (SKU, barcode, nama, kategori)
- [ ] Multi-satuan (PCS, dus, lusin, dengan konversi)
- [ ] Multi-harga (umum, anggota, grosir)
- [ ] Foto produk
- [ ] Brand & supplier
- [ ] Min/max stock alert

### Stok
- [ ] Stok per gudang/cabang
- [ ] Mutasi stok antar gudang
- [ ] Stok opname
- [ ] Kartu stok (movement log)
- [ ] Penyesuaian stok dengan alasan

### Pembelian
- [ ] Purchase Order (PO)
- [ ] Goods Receipt Note (GRN)
- [ ] Faktur pembelian
- [ ] Retur pembelian
- [ ] Hutang dagang

### Penjualan POS
- [ ] Kasir POS (touch-friendly)
- [ ] Barcode scanner support
- [ ] Diskon (item & total)
- [ ] Promo (buy X get Y, harga paket)
- [ ] Voucher
- [ ] Multi-payment (cash, transfer, QRIS, potong simpanan)
- [ ] **Bayar dengan potong simpanan anggota**
- [ ] Print struk (thermal printer)
- [ ] Retur penjualan
- [ ] Piutang dagang (kasbon anggota)
- [ ] Closing kasir harian

### Laporan
- [ ] Laporan penjualan harian/bulanan
- [ ] Best seller / slow moving
- [ ] Margin per produk
- [ ] HPP (FIFO atau average)

---

## 11. Unit Produsen (KSU) `[P1]`

- [ ] Master jenis hasil produksi (tani, ternak, kerajinan, dll)
- [ ] Pengumpulan hasil dari anggota (timbangan, kualitas)
- [ ] Pencatatan harga beli per anggota
- [ ] Pengolahan / produksi
- [ ] BOM (Bill of Material) sederhana
- [ ] HPP produksi
- [ ] Penjualan ke pasar / mitra
- [ ] Bagi hasil ke produsen anggota
- [ ] Laporan unit produsen

---

## 12. Unit Jasa (KSU) `[P1]`

- [ ] Master layanan jasa (sewa, transport, laundry, dll)
- [ ] Booking / order
- [ ] Invoice & pembayaran
- [ ] Komisi anggota (mitra jasa)
- [ ] Laporan unit jasa

---

## 13. Iuran & Tagihan `[P1]`

- [ ] Master tagihan rutin (iuran khusus, sewa, dll)
- [ ] Generate tagihan massal (bulanan/tahunan)
- [ ] Pembayaran (cash, auto-debit simpanan)
- [ ] Reminder otomatis (email/WA)
- [ ] Laporan tagihan & tunggakan

---

## 14. Asuransi & Penjaminan `[P1]`

- [ ] Master produk asuransi (kredit, jiwa)
- [ ] Auto-hitung premi dari pinjaman
- [ ] Pencatatan klaim
- [ ] Mitra asuransi (Jamkrindo, dll)
- [ ] Laporan polis aktif

---

## 15. HR Sederhana `[P1]`

- [ ] Master karyawan koperasi
- [ ] Komponen gaji (gapok, tunjangan, lembur)
- [ ] Komponen potongan (BPJS, PPh21, kasbon)
- [ ] Slip gaji
- [ ] Payroll bulanan
- [ ] Auto-jurnal gaji ke akuntansi
- [ ] Cuti & izin

---

## 16. Aset Tetap (Fixed Asset) `[P1]`

- [ ] Master aset (kendaraan, peralatan, gedung)
- [ ] Penyusutan otomatis (garis lurus, saldo menurun)
- [ ] Mutasi aset (lokasi, PIC)
- [ ] Pelepasan aset
- [ ] Auto-jurnal penyusutan bulanan

---

## 17. Reporting & Analytics `[P1]`

### Dashboard
- [ ] Dashboard Eksekutif (KPI utama)
- [ ] Dashboard Manajer (tunggakan, NPL, pertumbuhan)
- [ ] Dashboard Kasir (transaksi hari ini)
- [ ] Dashboard Pengawas (compliance, audit)

### Laporan Operasional
- [ ] Laporan harian (closing kasir, transaksi hari ini)
- [ ] Laporan mingguan
- [ ] Laporan bulanan
- [ ] Laporan tahunan (paket RAT)

### Laporan Khusus Koperasi
- [ ] Daftar anggota aktif
- [ ] Daftar saldo simpanan per jenis
- [ ] Daftar pinjaman aktif
- [ ] Tunggakan (aging report)
- [ ] NPL (Non Performing Loan)
- [ ] LDR (Loan to Deposit Ratio)
- [ ] Rasio kesehatan koperasi (Permenkop)

### Laporan Regulator
- [ ] Format laporan **ODS Kemenkop UKM**
- [ ] Format laporan **OJK** (untuk koperasi LKM, Phase 2)
- [ ] Format laporan **SLIK OJK** (Phase 2)
- [ ] Laporan pajak (PPh 21, PPh 23, PPh 25)

### Export & Custom
- [ ] Export Excel (semua laporan)
- [ ] Export PDF
- [ ] Export CSV
- [ ] Custom report builder (Phase 2)

---

## 18. Notifikasi `[P1]`

- [ ] Notifikasi in-app (bell icon)
- [ ] Email (queue)
- [ ] WhatsApp gateway (Fonnte / WAblas / sendiri)
- [ ] SMS (opsional)
- [ ] Template notifikasi (custom per event)
- [ ] Reminder angsuran (H-3, H-1, H+1)
- [ ] Konfirmasi setoran/penarikan
- [ ] Notifikasi approval pinjaman
- [ ] Notifikasi SHU dibagikan

---

## 19. Audit & Keamanan `[P1]`

- [ ] Activity log (semua aksi user — `spatie/laravel-activitylog`)
- [ ] Login history per user
- [ ] Approval matrix (transaksi besar)
- [ ] 2FA enforce per role
- [ ] IP whitelist admin
- [ ] Backup database otomatis (harian)
- [ ] Backup ke cloud (S3, GDrive)
- [ ] Restore wizard
- [ ] Encrypted sensitive fields (NIK, NPWP)
- [ ] Soft delete dengan recover

---

## 20. RAT (Rapat Anggota Tahunan) `[P1]`

- [ ] Setup acara RAT (tanggal, agenda, lokasi)
- [ ] Daftar hadir digital (QR check-in)
- [ ] Quorum tracker
- [ ] Notulen
- [ ] Voting digital (Phase 1.5)
- [ ] Arsip dokumen RAT
- [ ] Cetak laporan tahunan format RAT
- [ ] Buku tahunan / annual report

---

## 21. Portal Anggota `[P1.5]`

- [ ] Login anggota (guard terpisah)
- [ ] Dashboard anggota (saldo, pinjaman, SHU)
- [ ] Cek saldo semua simpanan
- [ ] Riwayat transaksi (filter & export)
- [ ] Status pinjaman & jadwal angsuran
- [ ] Pengajuan pinjaman online
- [ ] Pendaftaran anggota baru online
- [ ] Pembayaran online (gateway)
- [ ] Cetak buku tabungan PDF
- [ ] Lihat slip SHU
- [ ] Download akad pinjaman
- [ ] Update profil & dokumen
- [ ] Forum / pengumuman

---

## 22. Mobile App `[P1.5]`

### Anggota
- [ ] Splash + onboarding
- [ ] Login + biometric
- [ ] Dashboard saldo
- [ ] Setoran via QRIS
- [ ] Bayar angsuran
- [ ] Pengajuan pinjaman
- [ ] Notifikasi push
- [ ] Cetak struk digital

### Kolektor
- [ ] Login kolektor
- [ ] Daftar kunjungan harian (route)
- [ ] Setoran lapangan (offline-first)
- [ ] Foto bukti & GPS lokasi
- [ ] Sinkronisasi saat online

---

## 23. Integrasi Eksternal `[P1.5 / P2]`

- [ ] Payment gateway: Midtrans `[P1.5]`
- [ ] Payment gateway: Xendit `[P1.5]`
- [ ] QRIS dinamis & statis `[P1.5]`
- [ ] WhatsApp gateway (Fonnte/WAblas) `[P1]`
- [ ] Virtual Account BCA/Mandiri/BRI/BNI `[P2]`
- [ ] SLIK OJK (BI Checking) `[P2]`
- [ ] Dukcapil (verifikasi NIK) `[P2]`
- [ ] e-Materai `[P2]`
- [ ] Tanda tangan digital (PrivyID) `[P2]`
- [ ] Open API untuk fintech mitra `[P2]`

---

## 24. Multi-cabang `[P2]`

- [ ] Master cabang & kantor kas
- [ ] User per cabang
- [ ] Transaksi inter-cabang
- [ ] Konsolidasi laporan
- [ ] Stok antar cabang
- [ ] Transfer antar kas cabang

---

## 25. SaaS Foundation `[P2]`

- [ ] Aktifkan multi-tenant mode
- [ ] Subdomain per tenant (`koperasi-x.app.com`)
- [ ] Custom domain per tenant
- [ ] Plan & subscription (Basic, Pro, Enterprise)
- [ ] Billing & invoice
- [ ] Payment gateway untuk subscription
- [ ] Super admin dashboard
- [ ] Tenant onboarding wizard
- [ ] Tenant suspension & termination
- [ ] Tenant data export (GDPR-style)
- [ ] Usage metrics & quota (jumlah anggota, transaksi/bulan)
- [ ] White-label per tenant (logo, warna, domain)

---

## Ringkasan Progress per Modul

| # | Modul | Phase | Total | Done | % |
|---|---|---|---|---|---|
| 0 | License & Activation | P1 | 9 | 0 | 0% |
| 1 | Foundation Multi-tenancy | P1 | 7 | 0 | 0% |
| 2 | Authentication & RBAC | P1 | 10 | 0 | 0% |
| 3 | Master Data | P1 | 18 | 0 | 0% |
| 4 | Manajemen Anggota | P1 | 16 | 0 | 0% |
| 5 | Simpanan | P1 | 21 | 0 | 0% |
| 6 | Pinjaman/Pembiayaan | P1 | 40 | 0 | 0% |
| 7 | Akuntansi | P1 | 27 | 0 | 0% |
| 8 | Kas & Bank | P1 | 13 | 0 | 0% |
| 9 | SHU | P1 | 8 | 0 | 0% |
| 10 | Toko/POS | P1 | 30 | 0 | 0% |
| 11 | Unit Produsen | P1 | 9 | 0 | 0% |
| 12 | Unit Jasa | P1 | 5 | 0 | 0% |
| 13 | Iuran & Tagihan | P1 | 5 | 0 | 0% |
| 14 | Asuransi | P1 | 5 | 0 | 0% |
| 15 | HR Sederhana | P1 | 7 | 0 | 0% |
| 16 | Aset Tetap | P1 | 5 | 0 | 0% |
| 17 | Reporting & Analytics | P1 | 21 | 0 | 0% |
| 18 | Notifikasi | P1 | 9 | 0 | 0% |
| 19 | Audit & Keamanan | P1 | 10 | 0 | 0% |
| 20 | RAT | P1 | 8 | 0 | 0% |
| 21 | Portal Anggota | P1.5 | 13 | 0 | 0% |
| 22 | Mobile App | P1.5 | 13 | 0 | 0% |
| 23 | Integrasi Eksternal | P1.5/P2 | 10 | 0 | 0% |
| 24 | Multi-cabang | P2 | 6 | 0 | 0% |
| 25 | SaaS Foundation | P2 | 12 | 0 | 0% |
| | **TOTAL** | | **~336** | **0** | **0%** |
