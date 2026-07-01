<div class="space-y-3 text-sm">
    <div class="grid grid-cols-2 gap-3">
        <div><span class="text-slate-500 text-xs uppercase">Waktu</span><div class="font-bold">{{ $log->created_at->format('d M Y H:i:s') }}</div></div>
        <div><span class="text-slate-500 text-xs uppercase">Modul</span><div class="font-bold">{{ $log->log_name }}</div></div>
        <div><span class="text-slate-500 text-xs uppercase">Event</span><div class="font-bold">{{ $log->event }}</div></div>
        <div><span class="text-slate-500 text-xs uppercase">Causer</span><div class="font-bold">{{ $log->causer?->name ?? 'Sistem' }}</div></div>
    </div>
    <div><span class="text-slate-500 text-xs uppercase">Deskripsi</span><div>{{ $log->description }}</div></div>
    @if(!empty($log->properties['old']) || !empty($log->properties['attributes']))
        <div class="grid grid-cols-2 gap-3">
            <div>
                <span class="text-rose-600 text-xs uppercase font-bold">Sebelum</span>
                <pre class="text-xs bg-rose-50 p-2 rounded mt-1 overflow-auto">{{ json_encode($log->properties['old'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            <div>
                <span class="text-emerald-600 text-xs uppercase font-bold">Sesudah</span>
                <pre class="text-xs bg-emerald-50 p-2 rounded mt-1 overflow-auto">{{ json_encode($log->properties['attributes'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    @endif
</div>
