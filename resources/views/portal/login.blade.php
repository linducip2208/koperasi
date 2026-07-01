<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Anggota — {{ config('app.name') }}</title>
    <meta name="theme-color" content="#059669">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        .gradient-bg { background: linear-gradient(135deg, #047857 0%, #0d9488 50%, #0891b2 100%); }
        .gradient-mesh {
            background:
                radial-gradient(at 12% 8%, #10b981 0%, transparent 45%),
                radial-gradient(at 88% 12%, #06b6d4 0%, transparent 45%),
                radial-gradient(at 18% 92%, #14b8a6 0%, transparent 45%),
                radial-gradient(at 92% 88%, #0891b2 0%, transparent 45%),
                linear-gradient(135deg, #047857 0%, #0d9488 50%, #0891b2 100%);
        }
        .gradient-text { background: linear-gradient(135deg, #047857, #0891b2); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .blob { position: absolute; border-radius: 50%; filter: blur(72px); opacity: 0.55; mix-blend-mode: multiply; }
        .grid-pattern {
            background-image:
                linear-gradient(rgba(5,150,105,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(5,150,105,0.06) 1px, transparent 1px);
            background-size: 36px 36px;
        }
        @keyframes float { 0%,100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-22px) rotate(1deg); } }
        @keyframes blob {
            0%,100% { transform: translate(0,0) scale(1); }
            33% { transform: translate(30px,-20px) scale(1.1); }
            66% { transform: translate(-20px,15px) scale(0.95); }
        }
        @keyframes shimmer { 0% { backgroundPosition: '-200% 0' } 100% { backgroundPosition: '200% 0' } }
        .animate-float { animation: float 7s ease-in-out infinite; }
        .animate-blob { animation: blob 14s ease-in-out infinite; }
        .glass-form {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: 0 24px 48px -12px rgba(15,23,42,0.18), 0 4px 16px -4px rgba(15,23,42,0.06);
        }
        .input-shell {
            position: relative; transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .input-shell input:focus + .input-glow {
            opacity: 1;
        }
        .input-glow {
            position: absolute; inset: -2px; border-radius: 14px;
            background: linear-gradient(135deg, rgba(16,185,129,0.4), rgba(6,182,212,0.4));
            opacity: 0; transition: opacity 0.2s;
            z-index: -1; filter: blur(8px);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex relative overflow-hidden">
    <div class="absolute inset-0 grid-pattern"></div>
    <div class="blob bg-emerald-300 w-[520px] h-[520px] -top-40 -left-40 animate-blob"></div>
    <div class="blob bg-cyan-300 w-[420px] h-[420px] top-1/2 -right-32 animate-blob" style="animation-delay: -7s;"></div>
    <div class="blob bg-teal-200 w-[360px] h-[360px] -bottom-32 left-1/3 animate-blob" style="animation-delay: -3s;"></div>

    {{-- Left side: Form --}}
    <div class="relative flex-1 flex items-center justify-center p-4 md:p-8 lg:p-12 z-10">
        <div class="w-full max-w-md">
            <a href="/" class="inline-flex items-center gap-2 mb-8 text-slate-600 hover:text-slate-900 transition group">
                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                <span class="text-sm font-semibold">Kembali ke beranda</span>
            </a>

            <div class="flex items-center gap-3 mb-9">
                <div class="relative">
                    <div class="w-12 h-12 rounded-2xl gradient-bg flex items-center justify-center text-white font-extrabold text-xl shadow-xl shadow-emerald-500/40">K</div>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 ring-2 ring-white"></span>
                </div>
                <div>
                    <div class="font-extrabold text-xl text-slate-900 tracking-tight">Koperasi<span class="gradient-text">App</span></div>
                    <div class="text-[10px] text-slate-500 uppercase tracking-[0.18em] font-bold">Portal Anggota</div>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full text-[11px] font-bold text-emerald-700 mb-4 uppercase tracking-wider">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                </span>
                Server online
            </div>

            <h1 class="text-3xl md:text-[40px] font-extrabold tracking-tighter text-slate-900 mb-2 leading-[1.05]">Selamat datang<br/>kembali</h1>
            <p class="text-slate-600 mb-8 text-[15px]">Masuk untuk akses saldo, transaksi, dan ajukan pinjaman online — kapan saja, di mana saja.</p>

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-4 flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-semibold">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('portal.login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-700 mb-1.5 uppercase tracking-wider">Email</label>
                    <div class="input-shell relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               placeholder="anggota@email.com"
                               class="relative w-full pl-11 pr-4 py-3 bg-white/80 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition outline-none text-sm font-medium z-10">
                        <div class="input-glow"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1.5">
                        <label class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Password</label>
                        <a href="#" class="text-[11px] text-emerald-600 hover:text-emerald-700 hover:underline font-bold">Lupa password?</a>
                    </div>
                    <div class="input-shell relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                        <input type="password" name="password" required
                               placeholder="••••••••"
                               class="relative w-full pl-11 pr-4 py-3 bg-white/80 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition outline-none text-sm font-medium z-10">
                        <div class="input-glow"></div>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-[12px] text-slate-600 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/30">
                    <span class="font-medium">Ingat saya selama 30 hari</span>
                </label>

                <button type="submit" class="relative w-full gradient-bg text-white py-3.5 rounded-xl font-extrabold hover:shadow-2xl hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group overflow-hidden text-sm tracking-tight">
                    <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
                    <span class="relative">Masuk ke Portal</span>
                    <svg class="relative w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-200/70">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm">
                    <div class="font-semibold text-slate-800 mb-2">🧪 Demo Login</div>
                    <div class="space-y-1 text-slate-600 text-xs font-mono">
                        <div><span class="font-bold text-slate-800">Anggota 1:</span> anggota1@demo.local / anggota123</div>
                        <div><span class="font-bold text-slate-800">Anggota 2:</span> anggota2@demo.local / anggota123</div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Jalankan <code class="bg-slate-200 px-1 rounded">php artisan koperasi:seed-demo</code> untuk 5000+ data demo.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right side: Premium Mockup --}}
    <div class="hidden lg:flex relative flex-1 gradient-mesh items-center justify-center overflow-hidden z-0">
        <div class="absolute inset-0 grid-pattern opacity-15"></div>

        {{-- Floating decorative elements --}}
        <div class="absolute top-20 right-20 w-3 h-3 rounded-full bg-amber-300 shadow-lg shadow-amber-500/50 animate-float"></div>
        <div class="absolute bottom-32 right-44 w-2 h-2 rounded-full bg-emerald-200 animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/3 left-20 w-4 h-4 rounded-full border-2 border-white/40 animate-float" style="animation-delay: -5s;"></div>

        <div class="relative max-w-lg p-12 text-white">
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur border border-white/20 px-3 py-1 rounded-full text-[11px] font-extrabold mb-6 uppercase tracking-[0.18em]">
                <svg class="w-3 h-3 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2 9.19 8.62 2 9.24l5.45 4.73L5.82 21 12 17.27 18.18 21l-1.63-7.03L22 9.24l-7.19-.62L12 2z"/></svg>
                Trusted by 10K+ Anggota
            </div>

            <h2 class="text-4xl xl:text-5xl font-extrabold tracking-tighter leading-[1.05] mb-5">
                Kelola simpanan &amp; pinjaman <span class="bg-gradient-to-r from-amber-200 via-yellow-300 to-amber-200 bg-clip-text text-transparent">dari mana saja.</span>
            </h2>
            <p class="text-base xl:text-lg opacity-90 mb-10 leading-relaxed font-medium">Cek saldo, ajukan pinjaman, bayar angsuran — semua dari portal atau aplikasi mobile.</p>

            {{-- Premium floating card --}}
            <div class="relative animate-float">
                <div class="absolute inset-0 rounded-3xl bg-white/20 blur-2xl"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/25 shadow-2xl">
                    <div class="bg-white rounded-2xl p-5 text-slate-900 shadow-2xl shadow-black/20">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Total Simpanan</p>
                                <p class="text-3xl font-extrabold gradient-text tracking-tighter leading-none">Rp 12.450.000</p>
                                <p class="text-[11px] text-emerald-600 font-bold mt-1.5 inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/></svg>
                                    +Rp 250.000 minggu ini
                                </p>
                            </div>
                            <div class="w-11 h-11 rounded-xl gradient-bg flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25"/></svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-slate-50 rounded-xl p-2.5 text-center">
                                <div class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Pokok</div>
                                <div class="font-extrabold text-sm text-slate-900 mt-0.5">100rb</div>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-2.5 text-center">
                                <div class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Wajib</div>
                                <div class="font-extrabold text-sm text-slate-900 mt-0.5">2.4jt</div>
                            </div>
                            <div class="rounded-xl p-2.5 text-center text-white relative overflow-hidden" style="background: linear-gradient(135deg, #10b981, #06b6d4);">
                                <div class="text-[9px] opacity-90 font-bold uppercase tracking-wider">Sukarela</div>
                                <div class="font-extrabold text-sm mt-0.5">9.95jt</div>
                            </div>
                        </div>
                    </div>

                    {{-- Sparkline --}}
                    <div class="mt-4 flex items-end gap-1.5 h-12 px-1">
                        @foreach([35,42,38,55,48,62,58,72,68,80,75,90] as $h)
                            <div class="flex-1 bg-gradient-to-t from-white/40 to-white/80 rounded-t" style="height: {{ $h }}%"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-10">
                <div class="text-center">
                    <div class="text-3xl font-extrabold tracking-tight">24/7</div>
                    <div class="text-[10px] opacity-75 font-bold uppercase tracking-wider mt-0.5">Akses</div>
                </div>
                <div class="text-center border-x border-white/15">
                    <div class="text-3xl font-extrabold tracking-tight">10s</div>
                    <div class="text-[10px] opacity-75 font-bold uppercase tracking-wider mt-0.5">Cek Saldo</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-extrabold tracking-tight">A+</div>
                    <div class="text-[10px] opacity-75 font-bold uppercase tracking-wider mt-0.5">Aman</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
