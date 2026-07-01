<!DOCTYPE html>
<html lang="id" class="fi min-h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('filament-panels::pages/auth/login.title') }} &mdash; {{ config('app.name', 'KoperasiApp') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:400,500,700" rel="stylesheet">
    <style>
        *,::before,::after{box-sizing:border-box;margin:0;padding:0}
        html{font-family:'Plus Jakarta Sans',system-ui,sans-serif;-webkit-font-smoothing:antialiased}
        body{min-height:100vh;overflow:hidden}
        .login-grid{display:flex;min-height:100vh}
        @media(max-width:1023px){.login-grid{flex-direction:column-reverse}}

        .hero-panel{flex:1;position:relative;background:linear-gradient(135deg,#047857 0%,#0d9488 50%,#0891b2 100%);display:flex;align-items:center;justify-content:center;overflow:hidden}
        .hero-panel::before{content:'';position:absolute;inset:0;background:radial-gradient(at 12% 8%,#10b981 0%,transparent 45%),radial-gradient(at 88% 12%,#06b6d4 0%,transparent 45%),radial-gradient(at 18% 92%,#14b8a6 0%,transparent 45%),radial-gradient(at 92% 88%,#0891b2 0%,transparent 45%)}
        .hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:40px 40px}
        .blob{position:absolute;border-radius:50%;filter:blur(72px);opacity:.45;mix-blend-mode:multiply}
        @keyframes float{0%,100%{transform:translateY(0)rotate(0)}50%{transform:translateY(-22px)rotate(1deg)}}
        @keyframes blob{0%,100%{transform:translate(0,0)scale(1)}33%{transform:translate(30px,-20px)scale(1.1)}66%{transform:translate(-20px,15px)scale(.95)}}
        .animate-float{animation:float 7s ease-in-out infinite}
        .animate-blob{animation:blob 14s ease-in-out infinite}

        .hero-content{position:relative;z-index:1;max-width:460px;padding:3rem;color:#fff}
        .hero-content h2{font-size:2.5rem;font-weight:800;line-height:1.1;margin-bottom:1rem}
        .hero-content p{font-size:1rem;opacity:.85;line-height:1.6;margin-bottom:2.5rem}
        .hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.12);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15);padding:.4rem .8rem;border-radius:9999px;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;margin-bottom:1.5rem}
        .hero-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:2rem}
        .hero-stat{text-align:center;padding:.75rem;background:rgba(255,255,255,.08);border-radius:12px}
        .hero-stat .val{font-size:1.5rem;font-weight:800}
        .hero-stat .lbl{font-size:.55rem;text-transform:uppercase;letter-spacing:.08em;opacity:.7;margin-top:.2rem}
        .hero-footer{position:absolute;bottom:2rem;left:3rem;color:rgba(255,255,255,.35);font-size:.65rem;z-index:1}

        .form-panel{width:580px;display:flex;align-items:center;justify-content:center;padding:2.5rem;background:#f8fafc}
        @media(max-width:1023px){.form-panel{width:100%;flex:1;order:-1}}
        .form-wrapper{width:100%;max-width:400px}
        .form-badge{display:inline-flex;align-items:center;gap:.4rem;background:#d1fae5;border:1px solid #a7f3d0;padding:.25rem .65rem;border-radius:9999px;font-size:.65rem;font-weight:700;color:#047857;text-transform:uppercase;letter-spacing:.06em;margin-bottom:1.25rem}
        .form-badge .ping{position:relative;width:6px;height:6px;background:#10b981;border-radius:50%}
        .form-badge .ping::after{content:'';position:absolute;inset:-2px;border-radius:50%;background:#10b981;animation:ping 1.5s cubic-bezier(0,0,.2,1)infinite;opacity:.5}
        @keyframes ping{0%{transform:scale(1);opacity:.5}100%{transform:scale(2);opacity:0}}

        .form-wrapper h1{font-size:2rem;font-weight:800;color:#0f172a;line-height:1.1;margin-bottom:.25rem}
        .form-wrapper .sub{color:#64748b;font-size:.9rem;margin-bottom:2rem}

        .fi-simple-main{background:#fff!important;border-radius:16px!important;border:1px solid #e2e8f0!important;box-shadow:0 1px 3px rgba(0,0,0,.03),0 8px 24px -8px rgba(0,0,0,.06)!important;padding:1.75rem!important}
        .fi-simple-main .fi-simple-header h2{display:none!important}
        .fi-simple-main .fi-simple-header .fi-ta-empty-state, .fi-simple-page .fi-simple-header .fi-ta-empty-state{display:none}
        .fi-simple-page .fi-simple-header{display:none!important}
        .fi-input{border-radius:12px!important;border-color:#e2e8f0!important;padding:.65rem .85rem!important;font-size:.875rem!important;font-family:'Plus Jakarta Sans',system-ui,sans-serif!important;line-height:1.5!important}
        .fi-input[type=password]{font-family:system-ui!important;letter-spacing:.15em!important}
        .fi-input-wrapper button[type=button] svg,button[title*="Tampilkan"] svg,button[title*="Show"] svg{width:14px!important;height:14px!important}
        .fi-icon{width:1rem!important;height:1rem!important}
        .fi-input-wrp-label{font-size:.75rem!important;font-weight:700!important;text-transform:uppercase!important}
        .fi-input:focus{border-color:#10b981!important;box-shadow:0 0 0 3px rgba(16,185,129,.15)!important}
        .fi-btn{border-radius:12px!important;font-weight:700!important;padding:.7rem 1.25rem!important;font-size:.85rem!important}
        .fi-btn-primary{background:linear-gradient(135deg,#047857,#0d9488)!important;border:none!important;box-shadow:0 4px 12px rgba(5,150,105,.35)!important;transition:all .25s!important}
        .fi-btn-primary:hover{box-shadow:0 8px 24px rgba(5,150,105,.45)!important;transform:translateY(-1px)}
        .fi-link{color:#059669!important;font-weight:600!important}
        .fi-checkbox-input:checked{border-color:#059669!important;background-color:#059669!important}
        [x-ref=revealPassword]{display:none!important}

        .demo-box{margin-top:1.5rem;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:12px;padding:1rem;font-size:.78rem}
        .demo-box .title{font-weight:700;color:#334155;margin-bottom:.4rem}
        .demo-box .creds{font-family:'JetBrains Mono',monospace;font-size:.65rem;color:#64748b;line-height:1.5}
        .demo-box .creds b{color:#334155}

        .brand-header{display:flex;align-items:center;gap:.65rem;margin-bottom:2rem}
        .brand-logo{width:40px;height:40px;background:linear-gradient(135deg,#047857,#0d9488);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.1rem;box-shadow:0 4px 12px rgba(5,150,105,.35)}
        .brand-text{font-weight:800;font-size:1.15rem;color:#0f172a}
        .brand-text span{background:linear-gradient(135deg,#047857,#0891b2);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
    </style>
    @filamentStyles
</head>
<body class="fi-body fi-panel-admin min-h-screen bg-white">

<div class="login-grid">

    <div class="form-panel">
        <div class="form-wrapper">

            <div class="brand-header">
                <div class="brand-logo">K</div>
                <div class="brand-text">Koperasi<span>App</span></div>
            </div>

            <div class="form-badge">
                <span class="ping"></span> Admin Panel
            </div>

            <h1>Masuk</h1>
            <p class="sub">Panel administrasi koperasi Anda.</p>

            {{ $slot }}

            <div class="demo-box">
                <div class="title">🧪 Demo Login</div>
                <div class="creds">
                    <b>Super Admin:</b> admin@koperasi.local / admin123<br>
                    <b>Admin:</b> admin@koperasi.local / admin123<br>
                    <b>Manager:</b> manager@koperasi.local / admin123<br>
                    <b>Kasir:</b> kasir@koperasi.local / admin123<br>
                    <b>AO:</b> ao@koperasi.local / admin123<br>
                    <b>Kolektor:</b> kolektor@koperasi.local / admin123<br>
                    <b>Pengawas:</b> pengawas@koperasi.local / admin123<br>
                    <b>Akuntan:</b> akuntan@koperasi.local / admin123
                </div>
            </div>

            <div style="text-align:center;margin-top:1.5rem">
                <a href="{{ url('/') }}" style="color:#94a3b8;text-decoration:none;font-size:.8rem;font-weight:500">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <div class="hero-panel">
        <div class="hero-grid"></div>
        <div class="blob animate-blob" style="background:#10b981;width:420px;height:420px;top:-100px;right:-100px"></div>
        <div class="blob animate-blob" style="background:#06b6d4;width:350px;height:350px;bottom:-80px;left:-80px;animation-delay:-7s"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2 9.19 8.62 2 9.24l5.45 4.73L5.82 21 12 17.27 18.18 21l-1.63-7.03L22 9.24l-7.19-.62L12 2z"/></svg>
                Software Koperasi #1 Indonesia
            </div>
            <h2>Koperasi modern.<br>Konvensional &amp; Syariah.</h2>
            <p>Kelola simpanan, pinjaman, akuntansi, SHU, dan unit usaha dalam satu platform terintegrasi. 12 akad syariah siap pakai.</p>

            <div class="hero-stats">
                <div class="hero-stat"><div class="val">15+</div><div class="lbl">Modul</div></div>
                <div class="hero-stat"><div class="val">12</div><div class="lbl">Akad Syariah</div></div>
                <div class="hero-stat"><div class="val">1</div><div class="lbl">Platform</div></div>
            </div>
        </div>

        <div class="hero-footer">&copy; {{ date('Y') }} KoperasiApp · Laravel + Filament</div>
    </div>

</div>

@filamentScripts
</body>
</html>
