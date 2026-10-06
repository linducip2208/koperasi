<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>Import Center</span></div>
            <div class="kop-page-title">Import Center</div>
            <div class="kop-page-sub">Upload → Preview & validasi → Import atomic. Finansial perlu izin khusus + tercatat jurnal.</div>
        </div>
    </div>

    @if($preview)
        <div class="kop-card" style="padding:1.25rem; margin-bottom:1rem; border-color:#a7f3d0;">
            <div class="font-bold">Preview: {{ $preview->file_name }} <span class="kop-badge info">{{ $preview->tipe }}</span></div>
            <div class="kop-grid cols-4 mt-3">
                <div><div class="kop-stat-label">Total</div><div class="kop-stat-value">{{ $preview->total_rows }}</div></div>
                <div><div class="kop-stat-label">Valid</div><div class="kop-stat-value" style="color:var(--kop-success)">{{ $preview->valid_rows }}</div></div>
                <div><div class="kop-stat-label">Invalid</div><div class="kop-stat-value" style="color:var(--kop-danger)">{{ $preview->invalid_rows }}</div></div>
                <div><div class="kop-stat-label">Duplikat</div><div class="kop-stat-value" style="color:var(--kop-warning)">{{ $preview->duplicate_rows }}</div></div>
            </div>
            <div class="flex gap-2 mt-3 kop-no-print">
                @if($preview->status === 'preview' && $preview->valid_rows > 0)
                    <form method="POST" action="{{ route('imports.confirm', $preview) }}">
                        @csrf
                        <button class="kop-btn" onclick="return confirm('Import {{ $preview->valid_rows }} baris valid?')">Import {{ $preview->valid_rows }} Baris →</button>
                    </form>
                @else
                    <span class="kop-badge neutral">{{ $preview->status }}</span>
                @endif
                @if($preview->error_file)
                    <a class="kop-btn secondary" style="text-decoration:none;" href="{{ route('imports.errors', $preview) }}">Unduh Error CSV</a>
                @endif
            </div>
            <p class="text-xs mt-2" style="color:var(--kop-muted)">Perbaiki data yang salah dari Error CSV, lalu upload ulang. Baris valid saja yang masuk.</p>
        </div>
    @endif

    <div class="kop-grid cols-3" style="margin-bottom:1.25rem;">
        @foreach($types as $tipe => $label)
            <div class="kop-card" style="padding:1rem 1.1rem;">
                <div class="font-bold text-sm">{{ $label }}</div>
                <div class="text-xs mb-2" style="color:var(--kop-muted)">Format: CSV / XLSX · Maks 10 MB</div>
                <div class="flex flex-wrap gap-2 kop-no-print">
                    <a class="kop-btn secondary" style="font-size:.75rem; text-decoration:none;" href="{{ route('imports.template', $tipe) }}">Template</a>
                </div>
                <form method="POST" action="{{ route('imports.upload', $tipe) }}" enctype="multipart/form-data" class="mt-2 flex gap-2 kop-no-print">
                    @csrf
                    <input type="file" name="file" accept=".csv,.xlsx,.xls,.txt" required class="text-xs">
                    <button class="kop-btn" style="font-size:.75rem;">Upload</button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="kop-card" style="overflow:auto;">
        <div class="font-bold text-sm" style="padding:1rem 1.1rem 0;">Riwayat Import</div>
        <table class="kop-table">
            <thead><tr><th>File</th><th>Tipe</th><th class="text-right">Total</th><th class="text-right">Masuk</th><th class="text-right">Gagal</th><th>Status</th><th>Waktu</th></tr></thead>
            <tbody>
                @forelse($history as $h)
                    <tr>
                        <td>{{ $h->file_name }}</td>
                        <td>{{ $h->tipe }}</td>
                        <td class="num">{{ $h->total_rows }}</td>
                        <td class="num">{{ $h->imported_rows }}</td>
                        <td class="num">{{ $h->invalid_rows }}</td>
                        <td><span class="kop-badge {{ $h->status === 'completed' ? 'success' : ($h->status === 'failed' ? 'danger' : 'warning') }}">{{ $h->status }}</span></td>
                        <td>{{ $h->created_at?->format('d M H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center"><em>Belum ada import.</em></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
