# Import & Export Center

## Import

Tipe: anggota, COA, produk simpanan/pinjaman, saldo awal simpanan (finansial), outstanding awal pinjaman (finansial). Format CSV/XLSX (maks 10 MB).

Alur: Template (per tipe) → Upload → **Preview** (total/valid/invalid/duplikat + Error CSV) → Confirm. >1000 baris via queue (`ProcessImport`). Finansial: atomic per 500 baris + jurnal penyeimbang ke akun `3.9.9.01 Modal Saldo Awal` + audit log; butuh `reports.import_financial`.

Engine (`app/Imports/`): deteksi delimiter, strip BOM, format angka Indonesia (`1.250.000,50`), multi-format tanggal, validasi (required/email/enum/unique/exists), duplikat dalam-file + database. Riwayat di `import_batches`.

## Export

Report Center → viewer/Export Center: CSV (streaming + chunk, BOM), Excel, PDF (kop koperasi, nomor halaman, footer rahasia). Export selalu mengikuti filter aktif; dataset besar di-chunk, tidak load sekaligus.
