<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin (Simple) — Koperasi App</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        background: linear-gradient(135deg, #047857 0%, #0d9488 50%, #0891b2 100%);
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        padding: 1rem;
    }
    .card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        width: 100%;
        max-width: 420px;
        padding: 2.5rem 2rem;
    }
    h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .sub {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }
    .badge {
        display: inline-block;
        background: #dcfce7;
        color: #047857;
        font-weight: 700;
        font-size: 0.625rem;
        padding: 0.2rem 0.5rem;
        border-radius: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 1rem;
    }
    label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.4rem;
        margin-top: 1rem;
    }
    input[type="email"], input[type="password"] {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        background: #f8fafc;
        font-family: inherit;
    }
    input:focus { outline: 2px solid #10b981; background: white; }
    button {
        width: 100%;
        padding: 0.85rem;
        background: linear-gradient(135deg, #047857, #0891b2);
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        border-radius: 0.5rem;
        margin-top: 1.5rem;
        cursor: pointer;
        transition: transform 0.15s;
    }
    button:hover { transform: translateY(-1px); box-shadow: 0 8px 20px -4px rgba(5,150,105,0.4); }
    .errors {
        background: #fee2e2;
        color: #991b1b;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    .help {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.6;
    }
    .help code {
        background: #f1f5f9;
        padding: 0.1rem 0.3rem;
        border-radius: 0.2rem;
        font-family: "SFMono-Regular", Consolas, monospace;
        font-size: 0.8em;
        color: #4f46e5;
    }
    .help a { color: #047857; text-decoration: none; font-weight: 600; }
    .help a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="card">
    <span class="badge">Login Admin · Mode Simple</span>
    <h1>🔐 Masuk Admin</h1>
    <p class="sub">Form alternatif sederhana — tanpa JavaScript / Livewire.</p>

    @if($errors->any())
        <div class="errors">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    @endif

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="/admin/login">
        @csrf
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', 'admin@koperasi.local') }}" autofocus required>

        <label>Password</label>
        <input type="password" name="password" placeholder="admin123" required>

        <button type="submit">Masuk →</button>
    </form>

    <div class="help">
        <strong>Default credentials:</strong><br>
        Email: <code>admin@koperasi.local</code><br>
        Password: <code>admin123</code>

        <hr style="margin: 1rem 0; border: none; border-top: 1px solid #e2e8f0;">

        <strong>Halaman lain:</strong><br>
        <a href="/admin/login">→ Login admin (Filament default)</a><br>
        <a href="/portal/login">→ Login portal anggota</a><br>
        <a href="/clear-session">→ Reset session/cookie (kalau ada masalah)</a><br>
        <a href="/">→ Halaman beranda</a>
    </div>
</div>

</body>
</html>
