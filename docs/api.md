# API Anggota v1

Base: `/api/v1/*` (alias legacy `/api/*` tetap hidup). Auth Sanctum token.

| Method | Endpoint | Auth | Rate limit |
|---|---|---|---|
| POST | `/api/v1/login` | — | 5/menit |
| POST | `/api/v1/logout` | token | 120/menit |
| GET | `/api/v1/me` | token | 120/menit |
| GET | `/api/v1/simpanan` | token (milik sendiri) | 120/menit |
| GET | `/api/v1/pinjaman` | token (milik sendiri) | 120/menit |

| GET | `/api/v1/transaksi` | token (milik sendiri, paginasi) | 120/menit |
| GET | `/api/v1/notifikasi` | token (pengumuman + jatuh tempo 7 hari) | 120/menit |

Semua data di-scope ke anggota pemilik token (IDOR dicegah). Validasi request standar; error tanpa stack trace di production. Portal web memakai session guard terpisah dengan aturan yang sama.

## Offline-ready / idempotency

Klien lapangan (rencana Flutter) wajib kirim:

- `_idempotency` (setoran portal) / `idempotency_key` (service) — retry dengan key sama mengembalikan baris awal, tidak dobel.
- `_device` / `device_id` — identitas perangkat, tersimpan di `device_id`.
- `created_at` klien vs `server_at` (`created_at` server) — server selalu menang untuk urutan.

Kolom `idempotency_key` unik di `simpanan_transaksi` & `pinjaman_pembayaran`; webhook memakai `webhook_events.payment_id` unik. Jangan sinkron tanpa idempotency.
