# Workflow, Approval & Koleksi

## Workflow generik (`app/Workflow/WorkflowService`)

Alur: draft → submitted → review → approved → executed → closed (+ rejected/revision). Transisi dicek izin (`{modul}.{aksi}`) + diaudit (`workflow_transition`). Dipakai Pengadaan; pola sama dapat dipakai modul lain.

## Approval Center (Operations)

Satu pintu: pinjaman pengajuan (approve per level + tolak), pembayaran pending (verifikasi/tolak), pengadaan submitted/review, calon anggota (aktifkan). Semua aksi memanggil service resmi — bukan update status mentah.

## Credit Scoring (`CreditScoringService`)

8 faktor (usia keanggotaan, simpanan, riwayat pinjaman/bayar, tunggakan, DTI, penjamin, agunan) → skor 0–100 + level BAIK/CUKUP/KURANG + rekomendasi plafon (kelipatan simpanan) & tenor. Bobot/ambang configurable via settings `kredit.skor_kredit`. Deterministik, tested.

## Collection Center (Operations)

Antrian bucket (hari ini, overdue, 1–7 … 180+), assign kolektor (`pinjaman.kolektor_id`), follow-up (kunjungan/telepon/WA/janji bayar + nominal/tanggal + bukti foto), reminder WA via queue, performa kolektor (follow-up & akad dipegang). Jejak audit di `collection_followups`.

## Simulasi Pinjaman

Admin → Operasional → Simulasi Pinjaman: plafon, rate, tenor, metode (semua kalkulator), frekuensi → jadwal angsuran integer-rupiah.

## Agunan & penjamin

Pinjaman → Agunan & Penjamin: jenis agunan + taksiran + LTV + penjamin (nama/NIK/telp/hubungan). Scoring memakai keberadaan jaminan.
