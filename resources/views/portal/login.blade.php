<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Anggota — {{ config('app.name') }}</title>
    <meta name="theme-color" content="#ec4899">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:500,700" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans','sans-serif'],mono:['JetBrains Mono','monospace']}}}}</script>
    <style>
        *,::before,::after{box-sizing:border-box;margin:0;padding:0}
        html{font-family:'Plus Jakarta Sans',system-ui,sans-serif;-webkit-font-smoothing:antialiased}
        body{min-height:100vh;overflow:hidden}
        .login-grid{display:flex;min-height:100vh}
        @media(max-width:1023px){.login-grid{flex-direction:column}}

        .hero-panel{flex:1;position:relative;background:linear-gradient(135deg,#fdf2f8 0%,#fce7f3 30%,#ede9fe 60%,#e0e7ff 100%);display:flex;align-items:center;justify-content:center;overflow:hidden}
        .hero-bg{position:absolute;inset:0}
        .hero-bg .circle{position:absolute;border-radius:50%}
        .circle-1{width:500px;height:500px;background:linear-gradient(135deg,#f9a8d4,#c084fc);top:-150px;right:-120px;opacity:.55;filter:blur(80px)}
        .circle-2{width:400px;height:400px;background:linear-gradient(135deg,#fde047,#fb923c);bottom:-100px;left:-80px;opacity:.45;filter:blur(80px)}
        .circle-3{width:300px;height:300px;background:linear-gradient(135deg,#a5f3fc,#818cf8);top:40%;left:30%;opacity:.35;filter:blur(70px)}
        .circle-4{width:200px;height:200px;background:linear-gradient(135deg,#fda4af,#fb7185);bottom:30%;right:25%;opacity:.3;filter:blur(60px)}
        .dots{position:absolute;inset:0;background-image:radial-gradient(circle,rgba(236,72,153,.12) 1px,transparent 1px);background-size:24px 24px}

        .candy{position:absolute;font-size:2rem;animation:floatSweet 5s ease-in-out infinite;pointer-events:none;z-index:1;filter:drop-shadow(0 4px 8px rgba(0,0,0,.08))}
        @keyframes floatSweet{0%,100%{transform:translateY(0)rotate(0)}25%{transform:translateY(-16px)rotate(5deg)}75%{transform:translateY(8px)rotate(-3deg)}}
        @keyframes sparkle{0%,100%{opacity:0;transform:scale(0)}50%{opacity:1;transform:scale(1)}}

        .hero-content{position:relative;z-index:2;max-width:500px;padding:3rem;text-align:center}
        .hero-badge{display:inline-flex;align-items:center;gap:.4rem;background:linear-gradient(135deg,#ec4899,#a855f7);color:#fff;padding:.45rem 1rem;border-radius:9999px;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2rem;box-shadow:0 8px 24px rgba(236,72,153,.35)}
        .hero-content h2{font-size:2.6rem;font-weight:800;line-height:1.15;margin-bottom:1rem;background:linear-gradient(135deg,#be185d,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .hero-content p{font-size:1.05rem;color:#6b7280;line-height:1.7;margin-bottom:2.5rem;max-width:380px;margin-left:auto;margin-right:auto}
        .hero-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem;max-width:360px;margin:0 auto}
        .mini-card{background:rgba(255,255,255,.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.8);border-radius:20px;padding:1rem .5rem;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,.04)}
        .mini-card .val{font-size:1.4rem;font-weight:800;background:linear-gradient(135deg,#ec4899,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .mini-card .lbl{font-size:.6rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-top:.2rem}
        .hero-footer{position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);color:#d1d5db;font-size:.65rem;z-index:2}

        .form-panel{width:520px;display:flex;align-items:center;justify-content:center;padding:2.5rem;background:linear-gradient(180deg,#fdf2f8 0%,#faf5ff 50%,#f8fafc 100%)}
        @media(max-width:1023px){.form-panel{width:100%;padding:2rem 1.25rem}}
        .form-wrapper{width:100%;max-width:400px}
        .form-card{background:rgba(255,255,255,.85);backdrop-filter:blur(24px);border:1.5px solid rgba(236,72,153,.15);border-radius:24px;padding:2rem;box-shadow:0 4px 32px rgba(236,72,153,.1),0 1px 3px rgba(0,0,0,.04)}
        .brand{display:flex;align-items:center;justify-content:center;gap:.6rem;margin-bottom:1.75rem}
        .brand-logo{width:44px;height:44px;background:linear-gradient(135deg,#ec4899,#a855f7);border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.3rem;box-shadow:0 6px 20px rgba(236,72,153,.35)}
        .brand-text{font-weight:800;font-size:1.15rem;color:#1f2937}
        .brand-text span{background:linear-gradient(135deg,#ec4899,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .form-badge{display:inline-flex;align-items:center;gap:.4rem;background:linear-gradient(135deg,#fce7f3,#ede9fe);border:1px solid #f9a8d4;padding:.3rem .75rem;border-radius:9999px;font-size:.7rem;font-weight:700;color:#be185d;text-transform:uppercase;letter-spacing:.06em;margin-bottom:1.25rem}
        .form-badge .ping{width:7px;height:7px;background:#ec4899;border-radius:50%;position:relative}
        .form-badge .ping::after{content:'';position:absolute;inset:-3px;border-radius:50%;background:#ec4899;animation:ping 1.5s cubic-bezier(0,0,.2,1)infinite;opacity:.4}
        @keyframes ping{0%{transform:scale(1);opacity:.4}100%{transform:scale(2.5);opacity:0}}
        .form-title{font-size:1.75rem;font-weight:800;color:#1f2937;line-height:1.15;margin-bottom:.2rem}
        .form-sub{color:#9ca3af;font-size:.88rem;margin-bottom:1.75rem}

        .input-group{margin-bottom:1.25rem}
        .input-group label{display:block;font-size:.7rem;font-weight:700;color:#4b5563;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.45rem}
        .input-wrap{position:relative}
        .input-wrap svg{position:absolute;left:1rem;top:50%;transform:translateY(-50%);width:1.1rem;height:1.1rem;color:#d1d5db;pointer-events:none;z-index:1}
        .input-wrap input{width:100%;padding:.8rem 1rem .8rem 2.8rem;border:2px solid #f3e8ff;border-radius:16px;font-size:.875rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#1f2937;background:#faf5ff;transition:all .25s;outline:none}
        .input-wrap input:focus{border-color:#e879f9;background:#fff;box-shadow:0 0 0 4px rgba(232,121,249,.12)}
        .input-wrap input::placeholder{color:#d1d5db}

        .btn-primary{width:100%;padding:.85rem;background:linear-gradient(135deg,#ec4899,#a855f7);color:#fff;border:none;border-radius:16px;font-size:.9rem;font-weight:700;cursor:pointer;box-shadow:0 6px 24px rgba(236,72,153,.35);transition:all .3s;display:flex;align-items:center;justify-content:center;gap:.5rem;position:relative;overflow:hidden}
        .btn-primary::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);opacity:0;transition:opacity .3s}
        .btn-primary:hover{box-shadow:0 8px 32px rgba(236,72,153,.5);transform:translateY(-2px)}
        .btn-primary:hover::after{opacity:1}

        .demo-box{margin-top:1.25rem;background:linear-gradient(135deg,#fdf2f8,#faf5ff);border:2px dashed #f9a8d4;border-radius:16px;padding:1rem;font-size:.78rem}
        .demo-box .ttl{font-weight:700;color:#be185d;margin-bottom:.4rem;font-size:.8rem}
        .demo-box .creds{font-family:'JetBrains Mono',monospace;font-size:.65rem;color:#9ca3af;line-height:1.6}
        .demo-box .creds b{color:#6b7280}

        .back-link{display:inline-flex;align-items:center;gap:.4rem;color:#d1d5db;text-decoration:none;font-size:.8rem;font-weight:500;margin-bottom:1.5rem;transition:color .2s}
        .back-link:hover{color:#6b7280}
        .alert{background:linear-gradient(135deg,#fef2f2,#fff1f2);border:1.5px solid #fecdd3;color:#be123c;padding:.75rem 1rem;border-radius:14px;font-size:.8rem;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}

        input[type=password]::-ms-reveal{filter:brightness(.7);transform:scale(.85)}

        .sparkle{position:absolute;width:4px;height:4px;background:#fbbf24;border-radius:50%;animation:sparkle 2s ease-in-out infinite}
    </style>
</head>
<body>

<div class="login-grid">

    <div class="form-panel">
        <div class="form-wrapper">

            <a href="/" class="back-link">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Kembali
            </a>

            <div class="brand">
                <div class="brand-logo">K</div>
                <div class="brand-text">Koperasi<span>App</span></div>
            </div>

            <div class="form-badge"><span class="ping"></span> Portal Anggota</div>

            <h1 class="form-title">Selamat datang <span style="font-size:1.5rem">🍭</span></h1>
            <p class="form-sub">Akses simpanan & pinjamanmu dengan manis.</p>

            @if($errors->any())
                <div class="alert">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('portal.login.post') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label>Email</label>
                    <div class="input-wrap">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="anggota@email.com">
                    </div>
                </div>
                <div class="input-group">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.45rem">
                        <label style="margin-bottom:0">Password</label>
                        <a href="#" style="font-size:.7rem;color:#a855f7;font-weight:700;text-decoration:none">Lupa?</a>
                    </div>
                    <div class="input-wrap">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                </div>

                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.78rem;color:#9ca3af;margin-bottom:1.25rem;font-weight:500">
                    <input type="checkbox" name="remember" style="width:1rem;height:1rem;border-radius:6px;border:2px solid #e9d5ff;accent-color:#a855f7">
                    Ingat saya
                </label>

                <button type="submit" class="btn-primary">
                    Masuk Portal ✨
                </button>
            </form>

            <div class="demo-box">
                <div class="ttl">🍬 Demo Login Anggota</div>
                <div class="creds">
                    <b>Anggota 1:</b> anggota1@demo.local / anggota123<br>
                    <b>Anggota 2:</b> anggota2@demo.local / anggota123
                </div>
            </div>

        </div>
    </div>

    <div class="hero-panel">
        <div class="hero-bg">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="circle circle-3"></div>
            <div class="circle circle-4"></div>
        </div>
        <div class="dots"></div>

        <div class="candy" style="top:12%;left:10%;animation-delay:0s">🍬</div>
        <div class="candy" style="top:25%;right:15%;animation-delay:-1.5s;font-size:2.5rem">🌸</div>
        <div class="candy" style="bottom:20%;left:20%;animation-delay:-3s;font-size:1.8rem">🍭</div>
        <div class="candy" style="bottom:35%;right:10%;animation-delay:-4s">🧁</div>
        <div class="candy" style="top:50%;left:5%;animation-delay:-2s;font-size:1.5rem">💎</div>

        <div class="hero-content">
            <div class="hero-badge">
                <span style="font-size:.9rem">🍬</span> Manisnya Koperasi Modern
            </div>
            <h2>Simpanan & pinjaman semanis permen.</h2>
            <p>Cek saldo, ajukan pinjaman, bayar angsuran — semuanya manis & mudah dari satu portal.</p>

            <div class="hero-cards">
                <div class="mini-card"><div class="val">24/7</div><div class="lbl">Akses</div></div>
                <div class="mini-card"><div class="val">&lt;10s</div><div class="lbl">Cek Saldo</div></div>
                <div class="mini-card"><div class="val">🍭</div><div class="lbl">Manis</div></div>
            </div>
        </div>

        <div class="hero-footer">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
    </div>

</div>

</body>
</html>
