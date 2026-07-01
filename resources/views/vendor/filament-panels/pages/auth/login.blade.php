<div>
    @if($errors->any())
    <div style="background:#fef2f2;border:1.5px solid #fecdd3;color:#be123c;padding:.75rem 1rem;border-radius:14px;font-size:.8rem;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('filament.admin.auth.login') }}">
        @csrf

        <div style="margin-bottom:1.25rem">
            <label style="display:block;font-size:.7rem;font-weight:700;color:#4b5563;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.45rem">Email</label>
            <div style="position:relative">
                <svg style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);width:1.1rem;height:1.1rem;color:#d1d5db;pointer-events:none;z-index:1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@koperasi.local" autofocus
                    style="width:100%;padding:.8rem 1rem .8rem 2.8rem;border:2px solid #f3e8ff;border-radius:16px;font-size:.875rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#1f2937;background:#faf5ff;outline:none;box-sizing:border-box;transition:all .25s"
                    onfocus="this.style.borderColor='#e879f9';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(232,121,249,.12)'"
                    onblur="this.style.borderColor='#f3e8ff';this.style.background='#faf5ff';this.style.boxShadow='none'">
            </div>
        </div>

        <div style="margin-bottom:1rem">
            <label style="display:block;font-size:.7rem;font-weight:700;color:#4b5563;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.45rem">Password</label>
            <div style="position:relative">
                <svg style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);width:1.1rem;height:1.1rem;color:#d1d5db;pointer-events:none;z-index:1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                <input type="password" name="password" id="admin-password" required placeholder="••••••••" autocomplete="current-password"
                    style="width:100%;padding:.8rem 2.8rem .8rem 2.8rem;border:2px solid #f3e8ff;border-radius:16px;font-size:.875rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#1f2937;background:#faf5ff;outline:none;box-sizing:border-box;transition:all .25s;font-family:system-ui;letter-spacing:.12em"
                    onfocus="this.style.borderColor='#e879f9';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(232,121,249,.12)'"
                    onblur="this.style.borderColor='#f3e8ff';this.style.background='#faf5ff';this.style.boxShadow='none'">
                <button type="button" onclick="var p=this.parentElement.querySelector('input');var t=p.type==='password'?'text':'password';p.type=t;this.innerHTML=t==='text'?'<svg width=16 height=16 fill=none stroke=#c084fc stroke-width=2 viewBox=\\'0 0 24 24\\'><path stroke-linecap=round stroke-linejoin=round d=\\'M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.507 10.066 7.507.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88\\'/></svg>':'<svg width=16 height=16 fill=none stroke=#c084fc stroke-width=2 viewBox=\\'0 0 24 24\\'><path stroke-linecap=round stroke-linejoin=round d=\\'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z\\'/><path stroke-linecap=round stroke-linejoin=round d=\\'M15 12a3 3 0 11-6 0 3 3 0 016 0Z\\'/></svg>'" style="position:absolute;right:6px;top:50%;transform:translateY(-50%);padding:2px;background:none;border:none;cursor:pointer;z-index:2;display:flex;align-items:center;line-height:1">
                    <svg width="16" height="16" fill="none" stroke="#c084fc" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/></svg>
                </button>
            </div>
        </div>

        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.78rem;color:#9ca3af;margin-bottom:1.25rem;font-weight:500">
            <input type="checkbox" name="remember" style="width:1rem;height:1rem;border-radius:6px;border:2px solid #e9d5ff;accent-color:#a855f7">
            Ingat saya
        </label>

        <button type="submit" style="width:100%;padding:.85rem;background:linear-gradient(135deg,#ec4899,#a855f7);color:#fff;border:none;border-radius:16px;font-size:.9rem;font-weight:700;cursor:pointer;box-shadow:0 6px 24px rgba(236,72,153,.35);transition:all .3s;font-family:'Plus Jakarta Sans',system-ui,sans-serif"
            onmouseover="this.style.boxShadow='0 8px 32px rgba(236,72,153,.5)';this.style.transform='translateY(-2px)'"
            onmouseout="this.style.boxShadow='0 6px 24px rgba(236,72,153,.35)';this.style.transform='none'">
            Masuk Admin ✨
        </button>
    </form>
</div>
