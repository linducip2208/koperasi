<x-filament-panels::page>
    @vite('resources/js/reports.js')
    @if(! $def)
        <div class="kop-card kop-empty">
            <div class="kop-empty-icon">🔍</div>
            <h3>Report tidak dikenal</h3>
            <p>Kembali ke Report Center dan pilih report yang tersedia.</p>
            <a class="kop-btn" href="{{ route('filament.admin.pages.report-center') }}">← Report Center</a>
        </div>
    @else
        <div class="kop-page-head">
            <div>
                <div class="kop-crumb"><a href="{{ route('filament.admin.pages.report-center') }}">Report Center</a> <span>/</span> <span>{{ $def->name() }}</span></div>
                <div class="kop-page-title">{{ $def->name() }}</div>
                <div class="kop-page-sub">{{ $def->description() }}</div>
            </div>
            <div class="flex gap-2 kop-no-print">
                @foreach($def->exports() as $fmt)
                    <a class="kop-btn secondary" style="font-size:.75rem; text-decoration:none;" href="{{ $this->exportUrl($fmt) }}" target="_blank">{{ strtoupper($fmt) }} ⬇</a>
                @endforeach
            </div>
        </div>

        @if($def->filters())
            <form wire:submit.prevent="runReport" class="kop-card kop-no-print" style="padding:1rem 1.25rem; margin-bottom:1rem;">
                {{ $this->form }}
                <div class="flex gap-2 mt-3">
                    <button type="submit" class="kop-btn">Terapkan Filter</button>
                </div>
                @if(! empty($this->data))
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach(array_filter($this->data, fn ($v) => $v !== null && $v !== '') as $k => $v)
                            <span class="kop-chip">{{ $k }}: {{ is_scalar($v) ? $v : json_encode($v) }}</span>
                        @endforeach
                    </div>
                @endif
            </form>
        @endif

        @if($this->error)
            <div class="kop-card" style="padding:1.5rem; border-color:#fecdd3; background:#fff1f2;">
                <strong>Report gagal:</strong> {{ $this->error }}
                <div class="mt-2"><button wire:click="runReport" class="kop-btn secondary">Coba lagi</button></div>
            </div>
        @else
            @if(! empty($result['metrics']))
                <div class="kop-grid cols-4" style="margin-bottom:1rem;">
                    @foreach($result['metrics'] as $m)
                        <div class="kop-card kop-stat">
                            <div>
                                <div class="kop-stat-label">{{ $m['label'] }}</div>
                                <div class="kop-stat-value" style="font-size:1.15rem;">
                                    @if(($m['format'] ?? '') === 'money')
                                        Rp {{ number_format((int) $m['value'], 0, ',', '.') }}
                                    @else
                                        {{ $m['value'] }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(! empty($result['charts']))
                <div class="kop-grid cols-2" style="margin-bottom:1rem;">
                    @foreach($result['charts'] as $i => $ch)
                        <div class="kop-card" style="padding:1rem 1.1rem;">
                            <div class="font-bold text-sm mb-2" style="color:var(--kop-text)">{{ $ch['title'] }}</div>
                            <div style="height:240px;"><canvas data-chart='@json($ch)'></canvas></div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="kop-card" style="overflow:auto;">
                @if(empty($result['rows']))
                    <div class="kop-empty">
                        <div class="kop-empty-icon">📭</div>
                        <h3>Belum ada data pada periode ini</h3>
                        <p>Ubah filter tanggal atau parameter lain.</p>
                    </div>
                @else
                    <table class="kop-table">
                        <thead>
                            <tr>
                                @foreach($result['columns'] as $c)
                                    <th class="{{ ($c['align'] ?? '') === 'right' ? 'text-right' : '' }}">{{ $c['label'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['rows'] as $row)
                                <tr>
                                    @foreach($result['columns'] as $c)
                                        @php $v = $row[$c['key']] ?? ''; @endphp
                                        <td class="{{ ($c['align'] ?? '') === 'right' ? 'num' : '' }}">
                                            @if(isset($row['__link']) && $c === $result['columns'][0])
                                                <a href="{{ $row['__link'] }}" style="color:var(--kop-primary); font-weight:600;">
                                            @endif
                                            @if(($c['format'] ?? '') === 'money')
                                                {{ number_format((int) $v, 0, ',', '.') }}
                                            @elseif(($c['format'] ?? '') === 'badge')
                                                <span class="kop-badge info">{{ $v }}</span>
                                            @else
                                                {{ $v }}
                                            @endif
                                            @if(isset($row['__link']) && $c === $result['columns'][0])</a>@endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        @if(! empty($result['totals']))
                            <tfoot>
                                <tr>
                                    @foreach($result['columns'] as $i => $c)
                                        <td class="{{ ($c['align'] ?? '') === 'right' ? 'num' : '' }}">
                                            @if($i === 0) TOTAL
                                            @elseif(isset($result['totals'][$c['key']])) {{ number_format((int) $result['totals'][$c['key']], 0, ',', '.') }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                @endif
            </div>
            <p class="text-xs mt-2" style="color:var(--kop-muted)">{{ count($result['rows'] ?? []) }} baris · dari data live · {{ now()->format('d M Y H:i') }}</p>
        @endif
    @endif
</x-filament-panels::page>
