# Report Center

Arsitektur: `app/Reports/` — `ReportDefinition` (key/nama/kategori/permission/filter/run/export) → `ReportRunner` (otorisasi + audit) → `ReportExporter` (CSV streaming / Excel / PDF branded). Registri: `ReportRegistry` (30 report). Semua angka dari database live.

## Lokasi

- Admin → 📊 Laporan → **Report Center** (cari + favorit + terakhir dibuka), **Lihat Report** (viewer + filter + chips + chart + export), **Executive Dashboard** (9 KPI + 3 chart, periode hari→tahun lalu), **Anggota 360**, **Export Center**, **Arsip Report**, **Scheduled Reports**, **Custom Builder**, **AI Insights**.
- API: `GET /api/v1/reports`, `GET /api/v1/reports/{key}`, `POST /api/v1/reports/{key}/run`, `POST /api/v1/reports/{key}/export`.
- CLI: `reports:list`, `reports:health`, `reports:generate`, `reports:scheduled` (harian 06:30).

## Daftar report (30)

Keuangan: neraca, laba-rugi, arus-kas, perubahan-ekuitas, trial-balance, buku-besar, jurnal-umum, posisi-kas, rekap-pendapatan-beban, piutang-hutang. Simpanan: ringkasan, pertumbuhan, mutasi, dorman, top. Pinjaman: portofolio, pencairan, koleksi, tunggakan, aging-7-bucket, jatuh-tempo, restrukturisasi, risiko. Anggota: pertumbuhan, statement. SHU, syariah (portofolio akad, pendapatan), RAT tahunan, rasio keuangan (likuiditas/solvabilitas/profitabilitas + ambang).

## Drill-down

Baris report → buku besar per akun → jurnal (link admin) → transaksi sumber (`referensi_type/id`). Arsip resmi immutable: snapshot JSON + SHA256 (`ReportArchive::verify()`).

## Permission

`reports.view/export/pdf/excel/csv/schedule/archive/import/import_financial/ai/create/update/delete` — di-seed via `ReportPermissionSeeder` (aman di-rerun, tanpa sync).
