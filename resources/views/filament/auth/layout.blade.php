<!DOCTYPE html>
<html lang="id" class="fi min-h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ config('app.name', 'KoperasiApp') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:400,500,700" rel="stylesheet">
    <style>
        *,::before,::after{box-sizing:border-box;margin:0;padding:0}
        html{font-family:'Plus Jakarta Sans',system-ui,sans-serif;-webkit-font-smoothing:antialiased}
        body{min-height:100vh}
        .login-grid{display:flex;min-height:100vh}
        @media(max-width:1023px){.login-grid{flex-direction:column}}

        .hero-panel{flex:1;position:relative;background:linear-gradient(135deg,#fdf2f8 0%,#fce7f3 30%,#ede9fe 60%,#e0e7ff 100%);display:flex;align-items:center;justify-content:center;overflow:hidden}
        .hero-bg{position:absolute;inset:0}
        .hero-bg .circle{position:absolute;border-radius:50%}
        .circle-1{width:500px;height:500px;background:linear-gradient(135deg,#f9a8d4,#c084fc);top:-150px;right:-120px;opacity:.55;filter:blur(80px)}
        .circle-2{width:400px;height:400px;background:linear-gradient(135deg,#fde047,#fb923c);bottom:-100px;left:-80px;opacity:.45;filter:blur(80px)}
        .circle-3{width:300px;height:300px;background:linear-gradient(135deg,#a5f3fc,#818cf8);top:40%;left:30%;opacity:.35;filter:blur(70px)}
        .dots{position:absolute;inset:0;background-image:radial-gradient(circle,rgba(236,72,153,.1) 1px,transparent 1px);background-size:24px 24px}
        .candy{position:absolute;font-size:2rem;animation:floatSweet 5s ease-in-out infinite;pointer-events:none;z-index:1;filter:drop-shadow(0 4px 8px rgba(0,0,0,.06))}
        @keyframes floatSweet{0%,100%{transform:translateY(0)rotate(0)}25%{transform:translateY(-16px)rotate(5deg)}75%{transform:translateY(8px)rotate(-3deg)}}

        .hero-content{position:relative;z-index:2;max-width:500px;padding:3rem;text-align:center}
        .hero-badge{display:inline-flex;align-items:center;gap:.4rem;background:linear-gradient(135deg,#ec4899,#a855f7);color:#fff;padding:.45rem 1rem;border-radius:9999px;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2rem;box-shadow:0 8px 24px rgba(236,72,153,.35)}
        .hero-content h2{font-size:2.4rem;font-weight:800;line-height:1.15;margin-bottom:1rem;background:linear-gradient(135deg,#be185d,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .hero-content p{font-size:1rem;color:#6b7280;line-height:1.7;margin-bottom:2rem;max-width:380px;margin-left:auto;margin-right:auto}
        .hero-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem;max-width:360px;margin:0 auto}
        .mini-card{background:rgba(255,255,255,.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.8);border-radius:20px;padding:1rem .5rem;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,.04)}
        .mini-card .val{font-size:1.4rem;font-weight:800;background:linear-gradient(135deg,#ec4899,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .mini-card .lbl{font-size:.6rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-top:.2rem}
        .hero-footer{position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);color:#d1d5db;font-size:.65rem;z-index:2}

        .form-panel{width:540px;display:flex;align-items:center;justify-content:center;padding:2.5rem;background:linear-gradient(180deg,#fdf2f8 0%,#faf5ff 50%,#f8fafc 100%)}
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
        .form-sub{color:#9ca3af;font-size:.88rem;margin-bottom:1rem}

        .fi-simple-main{background:transparent!important;border:none!important;box-shadow:none!important;padding:0!important;border-radius:0!important}
        .fi-simple-page .fi-simple-header{display:none!important}
        .fi-input-wrp{display:flex;flex-direction:column;gap:.35rem}
        .fi-input-wrp-label{font-size:.7rem!important;font-weight:700!important;text-transform:uppercase!important;letter-spacing:.06em!important;color:#4b5563!important}
        .fi-input-wrp-label .fi-icon{display:none!important}
        .fi-input{border-radius:16px!important;border:2px solid #f3e8ff!important;padding:.8rem .85rem!important;font-size:.875rem!important;font-family:'Plus Jakarta Sans',system-ui,sans-serif!important;background:#faf5ff!important;transition:all .25s!important;box-shadow:none!important}
        .fi-input:focus{border-color:#e879f9!important;background:#fff!important;box-shadow:0 0 0 4px rgba(232,121,249,.12)!important}
        .fi-input[type=password]{font-family:system-ui!important;letter-spacing:.15em!important}
        .fi-input::placeholder{color:#d1d5db!important}
        .fi-input-wrapper button[type=button]{position:absolute!important;right:2px!important;top:50%!important;transform:translateY(-50%)!important;z-index:2!important;padding:4px!important;min-width:auto!important;min-height:auto!important;width:auto!important;height:auto!important;background:transparent!important;border:none!important;box-shadow:none!important;cursor:pointer!important;display:flex!important;align-items:center!important;justify-content:center!important}
        .fi-input-wrapper button[type=button] svg,.fi-input-wrapper button[type=button] svg *,.fi-input-wrapper button[type=button] .fi-icon,.fi-input-wrapper button[type=button] .fi-icon svg{width:16px!important;height:16px!important;max-width:16px!important;max-height:16px!important;min-width:16px!important;min-height:16px!important;color:#c084fc!important;display:block!important}
        .fi-input-wrapper [x-ref=revealPassword]{display:none!important}
        .fi-input-wrapper{padding:0!important;box-shadow:none!important;background:transparent!important;border:none!important;border-radius:0!important}
        .fi-input-wrapper-prefix,.fi-input-wrapper-suffix{display:none!important}

        .fi-btn{border-radius:16px!important;font-weight:700!important;font-size:.85rem!important;padding:.8rem 1.25rem!important;width:100%!important;justify-content:center!important;gap:.5rem!important}
        .fi-btn-primary{background:linear-gradient(135deg,#ec4899,#a855f7)!important;border:none!important;box-shadow:0 6px 24px rgba(236,72,153,.35)!important;transition:all .3s!important;color:#fff!important}
        .fi-btn-primary:hover{box-shadow:0 8px 32px rgba(236,72,153,.5)!important;transform:translateY(-2px)!important}
        .fi-link{color:#a855f7!important;font-weight:600!important}
        .fi-checkbox-input:checked{border-color:#a855f7!important;background-color:#a855f7!important}
        .fi-checkbox-input{border-radius:8px!important;border:2px solid #e9d5ff!important}
        .fi-checkbox-label span{font-size:.78rem!important;font-weight:500!important;color:#9ca3af!important}

        .demo-box{margin-top:1.25rem;background:linear-gradient(135deg,#fdf2f8,#faf5ff);border:2px dashed #f9a8d4;border-radius:16px;padding:1rem;font-size:.78rem}
        .demo-box .ttl{font-weight:700;color:#be185d;margin-bottom:.4rem;font-size:.8rem}
        .demo-box .creds{font-family:'JetBrains Mono',monospace;font-size:.65rem;color:#9ca3af;line-height:1.6}
        .demo-box .creds b{color:#6b7280}

        @media(max-width:640px){
            .form-card{padding:1.25rem;border-radius:20px}
            .hero-content{padding:2rem 1.5rem}
            .hero-content h2{font-size:1.75rem}
        }

        input[type=password]::-ms-reveal,
        input[type=password]::-ms-clear,
        input[type=password]::-webkit-password-toggle-button {display:none!important}
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

            <h1 class="form-title">Masuk yuk <span style="font-size:1.3rem">🍬</span></h1>
            <p class="form-sub">Panel administrasi koperasi yang manis.</p>

            <div class="form-card">
                {{ $slot }}
            </div>

            <div class="demo-box">
                <div class="ttl">🍭 Demo Login</div>
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
                <a href="{{ url('/') }}" style="color:#d1d5db;text-decoration:none;font-size:.8rem;font-weight:500;display:inline-flex;align-items:center;gap:.35rem;transition:color .2s">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <div class="hero-panel">
        <div class="hero-bg">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="circle circle-3"></div>
        </div>
        <div class="dots"></div>

        <div class="candy" style="top:10%;left:8%">🍬</div>
        <div class="candy" style="top:28%;right:12%;animation-delay:-1.5s;font-size:2.5rem">🌸</div>
        <div class="candy" style="bottom:22%;left:15%;animation-delay:-3s">🍭</div>
        <div class="candy" style="bottom:30%;right:8%;animation-delay:-4s;font-size:1.6rem">🧁</div>

        <div class="hero-content">
            <div class="hero-badge">
                <span style="font-size:.9rem">🍬</span> Manisnya Koperasi Modern
            </div>
            <h2>Koperasi semanis permen.<br>Manisnya transaksi.</h2>
            <p>Kelola simpanan, pinjaman, akuntansi, SHU, dan unit usaha dalam satu platform. 41 modul terintegrasi.</p>

            <div class="hero-cards">
                <div class="mini-card"><div class="val">41</div><div class="lbl">Modul</div></div>
                <div class="mini-card"><div class="val">12</div><div class="lbl">Akad</div></div>
                <div class="mini-card"><div class="val">8</div><div class="lbl">Role</div></div>
            </div>
        </div>

        <div class="hero-footer">&copy; {{ date('Y') }} {{ config('app.name') }} · Laravel + Filament</div>
    </div>

</div>

@filamentScripts
<script>
(function fixEye(){
    var b = document.querySelector('.fi-input-wrapper input[type=password]');
    if(!b) return setTimeout(fixEye, 50);
    var s = b.parentElement.querySelectorAll('button svg, button .fi-icon svg');
    s.forEach(function(e){ e.setAttribute('width','16'); e.setAttribute('height','16'); e.style.width='16px'; e.style.height='16px' });
})();
</script>
</body>
</html>
