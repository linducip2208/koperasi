# API Anggota v1

Base: `/api/v1/*` (alias legacy `/api/*` tetap hidup). Auth Sanctum token.

| Method | Endpoint | Auth | Rate limit |
|---|---|---|---|
| POST | `/api/v1/login` | — | 5/menit |
| POST | `/api/v1/logout` | token | 120/menit |
| GET | `/api/v1/me` | token | 120/menit |
| GET | `/api/v1/simpanan` | token (milik sendiri) | 120/menit |
| GET | `/api/v1/pinjaman` | token (milik sendiri) | 120/menit |

Semua data di-scope ke anggota pemilik token (IDOR dicegah). Validasi request standar; error tanpa stack trace di production. Portal web memakai session guard terpisah dengan aturan yang sama.
