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

        .hero-content{position:relative;z-index:1;max-width:480px;padding:3rem;color:#fff}
        .hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.12);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15);padding:.4rem .85rem;border-radius:9999px;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;margin-bottom:1.5rem}
        .hero-content h2{font-size:2.5rem;font-weight:800;line-height:1.1;margin-bottom:1rem}
        .hero-content p{font-size:1rem;opacity:.85;line-height:1.6;margin-bottom:2.5rem}
        .hero-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:2rem}
        .hero-stat{text-align:center;padding:.75rem;background:rgba(255,255,255,.08);border-radius:12px}
        .hero-stat .val{font-size:1.5rem;font-weight:800}
        .hero-stat .lbl{font-size:.55rem;text-transform:uppercase;letter-spacing:.08em;opacity:.7;margin-top:.2rem}
        .hero-footer{position:absolute;bottom:2rem;left:3rem;color:rgba(255,255,255,.3);font-size:.65rem;z-index:1}

        .form-panel{width:540px;display:flex;align-items:center;justify-content:center;padding:2.5rem;background:#f8fafc}
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

        .fi-simple-main{background:transparent!important;border:none!important;box-shadow:none!important;padding:0!important;border-radius:0!important}
        .fi-simple-page .fi-simple-header{display:none!important}

        .fi-input-wrp{display:flex;flex-direction:column;gap:.35rem}
        .fi-input-wrp-label{font-size:.7rem!important;font-weight:700!important;text-transform:uppercase!important;letter-spacing:.06em!important;color:#334155!important}
        .fi-input-wrp-label .fi-icon{display:none!important}
        .fi-input{border-radius:12px!important;border:1.5px solid #e2e8f0!important;padding:.75rem .85rem!important;font-size:.875rem!important;font-family:'Plus Jakarta Sans',system-ui,sans-serif!important;background:#f8fafc!important;transition:all .2s!important;box-shadow:none!important}
        .fi-input:focus{border-color:#10b981!important;background:#fff!important;box-shadow:0 0 0 3px rgba(16,185,129,.12)!important}
        .fi-input[type=password]{font-family:system-ui!important;letter-spacing:.15em!important}
        .fi-input::placeholder{color:#cbd5e1!important}
        .fi-input-wrapper button[type=button] svg{width:14px!important;height:14px!important}
        .fi-input-wrapper{padding:0!important;box-shadow:none!important;background:transparent!important;border:none!important;border-radius:0!important}

        .fi-btn{border-radius:12px!important;font-weight:700!important;font-size:.85rem!important;padding:.75rem 1.25rem!important;width:100%!important;justify-content:center!important;gap:.5rem!important}
        .fi-btn-primary{background:linear-gradient(135deg,#047857,#0d9488)!important;border:none!important;box-shadow:0 4px 16px rgba(5,150,105,.3)!important;transition:all .25s!important;color:#fff!important}
        .fi-btn-primary:hover{box-shadow:0 8px 28px rgba(5,150,105,.4)!important;transform:translateY(-1px)!important}
        .fi-link{color:#059669!important;font-weight:600!important}
        .fi-checkbox-input:checked{border-color:#059669!important;background-color:#059669!important}
        .fi-checkbox-input{border-radius:6px!important;border:1.5px solid #cbd5e1!important}
        .fi-checkbox-label span{font-size:.78rem!important;font-weight:500!important;color:#64748b!important}
        [x-ref=revealPassword]{display:none!important}

        .demo-box{margin-top:1.25rem;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:12px;padding:1rem;font-size:.78rem}
        .demo-box .ttl{font-weight:700;color:#334155;margin-bottom:.4rem;font-size:.8rem}
        .demo-box .creds{font-family:'JetBrains Mono',monospace;font-size:.65rem;color:#64748b;line-height:1.5}
        .demo-box .creds b{color:#334155}

        @media(max-width:640px){
            .form-card{padding:1.25rem;border-radius:16px}
            .form-title{font-size:1.4rem}
            .hero-content{padding:2rem 1.5rem}
            .hero-content h2{font-size:1.75rem}
            .hero-stats{gap:.5rem}
            .hero-stat{padding:.5rem}
            .hero-stat .val{font-size:1.2rem}
        }
    </style>
    @filamentStyles
</head>
<body class="fi-body fi-panel-admin min-h-screen bg-white">

<div class="login-grid">

    <div class="form-panel">
        <div class="form-wrapper">

            <div class="brand">
                <div class="brand-logo">K</div>
                <div class="brand-text">Koperasi<span>App</span></div>
            </div>

            <div class="form-badge"><span class="ping"></span> Admin Panel</div>

            <h1 class="form-title">Masuk</h1>
            <p class="form-sub">Panel administrasi koperasi Anda.</p>

            <div class="form-card">
                {{ $slot }}
            </div>

            <div class="demo-box">
                <div class="ttl">🧪 Demo Login</div>
                <div class="creds">
                    <b>Super Admin:</b> admin@koperasi.local / admin123<br>
                    <b>Manager:</b> manager@koperasi.local / admin123<br>
                    <b>Kasir:</b> kasir@koperasi.local / admin123<br>
                    <b>AO:</b> ao@koperasi.local / admin123<br>
                    <b>Kolektor:</b> kolektor@koperasi.local / admin123<br>
                    <b>Pengawas:</b> pengawas@koperasi.local / admin123<br>
                    <b>Akuntan:</b> akuntan@koperasi.local / admin123
                </div>
            </div>

            <div style="text-align:center;margin-top:1.25rem">
                <a href="{{ url('/') }}" style="color:#94a3b8;text-decoration:none;font-size:.8rem;font-weight:500;display:inline-flex;align-items:center;gap:.35rem;transition:color .2s">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Kembali ke Beranda
                </a>
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
            <h2>Koperasi modern.<br>Konvensional &amp; Syariah.</h2>
            <p>Kelola simpanan, pinjaman, akuntansi, SHU, dan unit usaha dalam satu platform. 12 akad syariah siap pakai. 41 modul terintegrasi.</p>

            <div class="hero-stats">
                <div class="hero-stat"><div class="val">41</div><div class="lbl">Modul</div></div>
                <div class="hero-stat"><div class="val">12</div><div class="lbl">Akad Syariah</div></div>
                <div class="hero-stat"><div class="val">8</div><div class="lbl">Role User</div></div>
            </div>

            <div style="margin-top:2.5rem;display:flex;align-items:center;gap:.75rem;font-size:.8rem;opacity:.7">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                Keamanan data terjamin · Audit trail lengkap
            </div>
        </div>

        <div class="hero-footer">&copy; {{ date('Y') }} {{ config('app.name') }} · Laravel + Filament</div>
    </div>

</div>

@filamentScripts
</body>
</html>
