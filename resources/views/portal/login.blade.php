<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Anggota — {{ config('app.name') }}</title>
    <meta name="theme-color" content="#059669">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:500,700" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans','sans-serif'],mono:['JetBrains Mono','monospace']}}}}</script>
    <style>
        *,::before,::after{box-sizing:border-box;margin:0;padding:0}
        html{font-family:'Plus Jakarta Sans',system-ui,sans-serif;-webkit-font-smoothing:antialiased;font-feature-settings:"cv02","cv03","cv04","cv11"}
        body{min-height:100vh}
        .login-grid{display:flex;min-height:100vh}
        @media(max-width:1023px){.login-grid{flex-direction:column-reverse}}

        .hero-panel{flex:1;position:relative;background:linear-gradient(135deg,#047857 0%,#0d9488 50%,#0891b2 100%);display:flex;align-items:center;justify-content:center;overflow:hidden}
        .hero-mesh{position:absolute;inset:0;background:radial-gradient(at 12% 8%,#10b981 0%,transparent 45%),radial-gradient(at 88% 12%,#06b6d4 0%,transparent 45%),radial-gradient(at 18% 92%,#14b8a6 0%,transparent 45%),radial-gradient(at 92% 88%,#0891b2 0%,transparent 45%)}
        .hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:40px 40px}
        .blob{position:absolute;border-radius:50%;filter:blur(72px);opacity:.4;mix-blend-mode:screen;pointer-events:none}
        @keyframes float{0%,100%{transform:translateY(0)rotate(0)}50%{transform:translateY(-20px)rotate(1deg)}}
        @keyframes blob{0%,100%{transform:translate(0,0)scale(1)}33%{transform:translate(30px,-20px)scale(1.1)}66%{transform:translate(-20px,15px)scale(.95)}}
        .animate-float{animation:float 7s ease-in-out infinite}
        .animate-blob{animation:blob 14s ease-in-out infinite}
        @keyframes fadeSlideUp{0%{transform:translateY(24px);opacity:0}100%{transform:translateY(0);opacity:1}}
        .animate-fade-slide{animation:fadeSlideUp .8s cubic-bezier(.16,1,.3,1) both}
        .delay-1{animation-delay:.1s}.delay-2{animation-delay:.2s}.delay-3{animation-delay:.3s}

        .hero-content{position:relative;z-index:1;max-width:480px;padding:3rem;color:#fff}
        .hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.12);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15);padding:.4rem .85rem;border-radius:9999px;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;margin-bottom:1.5rem}
        .hero-content h2{font-size:2.5rem;font-weight:800;line-height:1.1;margin-bottom:1rem}
        .hero-content p{font-size:1rem;opacity:.85;line-height:1.6;margin-bottom:2.5rem}
        .hero-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:2rem}
        .hero-stat{text-align:center;padding:.75rem;background:rgba(255,255,255,.08);border-radius:12px}
        .hero-stat .val{font-size:1.5rem;font-weight:800}
        .hero-stat .lbl{font-size:.55rem;text-transform:uppercase;letter-spacing:.08em;opacity:.7;margin-top:.2rem}
        .hero-footer{position:absolute;bottom:2rem;left:3rem;color:rgba(255,255,255,.3);font-size:.65rem;z-index:1}

        .form-panel{width:520px;display:flex;align-items:center;justify-content:center;padding:2.5rem;background:#f8fafc}
        @media(max-width:1023px){.form-panel{width:100%;padding:2rem 1.25rem}}
        .form-wrapper{width:100%;max-width:400px}
        .form-card{background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:2rem;box-shadow:0 1px 3px rgba(0,0,0,.03),0 12px 32px -8px rgba(0,0,0,.08)}
        .brand{display:flex;align-items:center;gap:.65rem;margin-bottom:1.75rem}
        .brand-logo{width:42px;height:42px;background:linear-gradient(135deg,#047857,#0d9488);border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.2rem;box-shadow:0 4px 12px rgba(5,150,105,.35)}
        .brand-text{font-weight:800;font-size:1.15rem;color:#0f172a}
        .brand-text span{background:linear-gradient(135deg,#047857,#0891b2);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .form-badge{display:inline-flex;align-items:center;gap:.4rem;background:#d1fae5;border:1px solid #a7f3d0;padding:.25rem .65rem;border-radius:9999px;font-size:.65rem;font-weight:700;color:#047857;text-transform:uppercase;letter-spacing:.06em;margin-bottom:1.25rem}
        .form-badge .ping{width:7px;height:7px;background:#10b981;border-radius:50%;position:relative}
        .form-badge .ping::after{content:'';position:absolute;inset:-2px;border-radius:50%;background:#10b981;animation:ping 1.5s cubic-bezier(0,0,.2,1)infinite;opacity:.5}
        @keyframes ping{0%{transform:scale(1);opacity:.5}100%{transform:scale(2);opacity:0}}
        .form-title{font-size:1.75rem;font-weight:800;color:#0f172a;line-height:1.1;margin-bottom:.25rem}
        .form-sub{color:#64748b;font-size:.875rem;margin-bottom:1.75rem}

        .input-group{margin-bottom:1.25rem}
        .input-group label{display:block;font-size:.7rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.4rem}
        .input-wrap{position:relative}
        .input-wrap svg{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:#94a3b8;pointer-events:none}
        .input-wrap input{width:100%;padding:.75rem .9rem .75rem 2.5rem;border:1.5px solid #e2e8f0;border-radius:12px;font-size:.875rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#0f172a;background:#f8fafc;transition:all .2s;outline:none}
        .input-wrap input:focus{border-color:#10b981;background:#fff;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
        .input-wrap input::placeholder{color:#cbd5e1}
        .btn-primary{width:100%;padding:.8rem;background:linear-gradient(135deg,#047857,#0d9488);color:#fff;border:none;border-radius:12px;font-size:.875rem;font-weight:700;cursor:pointer;box-shadow:0 4px 16px rgba(5,150,105,.3);transition:all .25s;display:flex;align-items:center;justify-content:center;gap:.5rem}
        .btn-primary:hover{box-shadow:0 8px 28px rgba(5,150,105,.4);transform:translateY(-1px)}

        .demo-box{margin-top:1.25rem;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:12px;padding:1rem;font-size:.78rem}
        .demo-box .ttl{font-weight:700;color:#334155;margin-bottom:.4rem;font-size:.8rem}
        .demo-box .creds{font-family:'JetBrains Mono',monospace;font-size:.65rem;color:#64748b;line-height:1.5}
        .demo-box .creds b{color:#334155}
        .alert{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:.75rem 1rem;border-radius:12px;font-size:.8rem;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}

        .back-link{display:inline-flex;align-items:center;gap:.4rem;color:#94a3b8;text-decoration:none;font-size:.8rem;font-weight:500;margin-bottom:1.5rem;transition:color .2s}
        .back-link:hover{color:#0f172a}
        .back-link svg{transition:transform .2s}
        .back-link:hover svg{transform:translateX(-2px)}

        @media(max-width:640px){
            .form-card{padding:1.5rem;border-radius:16px}
            .form-title{font-size:1.5rem}
            .hero-content{padding:2rem 1.5rem}
            .hero-content h2{font-size:1.75rem}
        }
    </style>
</head>
<body>

<div class="login-grid">

    <div class="form-panel">
        <div class="form-wrapper">

            <a href="/" class="back-link">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Kembali ke beranda
            </a>

            <div class="brand">
                <div class="brand-logo">K</div>
                <div class="brand-text">Koperasi<span>App</span></div>
            </div>

            <div class="form-badge"><span class="ping"></span> Portal Anggota</div>

            <h1 class="form-title">Selamat datang<br>kembali</h1>
            <p class="form-sub">Masuk untuk akses saldo, transaksi, dan ajukan pinjaman online.</p>

            @if($errors->any())
                <div class="alert">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('portal.login.post') }}" method="POST">
                @csrf
                <div class="input-group animate-fade-slide">
                    <label>Email</label>
                    <div class="input-wrap">
                        <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="anggota@email.com">
                    </div>
                </div>

                <div class="input-group animate-fade-slide delay-1">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem">
                        <label style="margin-bottom:0">Password</label>
                        <a href="#" style="font-size:.65rem;color:#059669;font-weight:700;text-decoration:none">Lupa?</a>
                    </div>
                    <div class="input-wrap">
                        <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                </div>

                <label class="animate-fade-slide delay-2" style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.78rem;color:#64748b;margin-bottom:1.25rem;font-weight:500">
                    <input type="checkbox" name="remember" style="width:1rem;height:1rem;border-radius:4px;border:1.5px solid #cbd5e1;accent-color:#059669">
                    Ingat saya
                </label>

                <button type="submit" class="btn-primary animate-fade-slide delay-3">
                    Masuk ke Portal
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </button>
            </form>

            <div class="demo-box">
                <div class="ttl">🧪 Demo Login Anggota</div>
                <div class="creds">
                    <b>Anggota 1:</b> anggota1@demo.local / anggota123<br>
                    <b>Anggota 2:</b> anggota2@demo.local / anggota123
                </div>
                <div style="font-size:.6rem;color:#94a3b8;margin-top:.4rem">Jalankan <code style="background:#e2e8f0;padding:.1rem .3rem;border-radius:4px">php artisan koperasi:seed-demo</code> untuk data demo.</div>
            </div>

        </div>
    </div>

    <div class="hero-panel">
        <div class="hero-mesh"></div>
        <div class="hero-grid"></div>
        <div class="blob animate-blob" style="background:#10b981;width:420px;height:420px;top:-100px;right:-100px"></div>
        <div class="blob animate-blob" style="background:#06b6d4;width:350px;height:350px;bottom:-80px;left:-80px;animation-delay:-7s"></div>
        <div class="blob" style="background:#14b8a6;width:280px;height:280px;top:40%;right:20%;animation:blob 16s ease-in-out infinite;animation-delay:-4s;opacity:.25"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2 9.19 8.62 2 9.24l5.45 4.73L5.82 21 12 17.27 18.18 21l-1.63-7.03L22 9.24l-7.19-.62L12 2z"/></svg>
                Software Koperasi #1 Indonesia
            </div>
            <h2>Kelola simpanan &<br>pinjaman dari mana saja.</h2>
            <p>Cek saldo real-time, ajukan pinjaman online, bayar angsuran — semua dari portal anggota atau aplikasi mobile.</p>

            <div class="hero-stats">
                <div class="hero-stat"><div class="val">24/7</div><div class="lbl">Akses</div></div>
                <div class="hero-stat"><div class="val">&lt;10s</div><div class="lbl">Cek Saldo</div></div>
                <div class="hero-stat"><div class="val">100%</div><div class="lbl">Aman</div></div>
            </div>

            <div style="margin-top:2.5rem;display:flex;align-items:center;gap:.75rem;font-size:.8rem;opacity:.7">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                Enkripsi end-to-end · Data anggota terlindungi
            </div>
        </div>

        <div class="hero-footer">&copy; {{ date('Y') }} {{ config('app.name') }} · Powered by Laravel</div>
    </div>

</div>

</body>
</html>
