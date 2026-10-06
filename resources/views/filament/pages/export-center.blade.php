<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>Export Center</span></div>
            <div class="kop-page-title">Export Center</div>
            <div class="kop-page-sub">CSV streaming, Excel, PDF branded — selalu mengikuti filter viewer. Dataset besar di-chunk.</div>
        </div>
    </div>

    @foreach($categories as $ck => $clabel)
        @php $inCat = array_filter($defs, fn ($d) => $d->category() === $ck); @endphp
        @if(! empty($inCat))
            <h3 class="font-bold text-sm mb-2 mt-4" style="color:var(--kop-text)">{{ $clabel }}</h3>
            <div class="kop-card" style="overflow:auto; margin-bottom:.5rem;">
                <table class="kop-table">
                    <thead><tr><th>Report</th><th>Export</th></tr></thead>
                    <tbody>
                        @foreach($inCat as $d)
                            <tr>
                                <td><strong>{{ $d->name() }}</strong><br><span class="text-xs" style="color:var(--kop-muted)">{{ $d->description() }}</span></td>
                                <td class="kop-no-print">
                                    <div class="flex gap-2">
                                        @foreach($d->exports() as $fmt)
                                            <a class="kop-btn secondary" style="font-size:.72rem; padding:.35rem .7rem; text-decoration:none;" href="{{ route('filament.admin.pages.report-viewer', ['report' => $d->key()]) }}">{{ strtoupper($fmt) }} →</a>
                                        @endforeach
                                    </div>
                                    <span class="text-xs" style="color:var(--kop-muted)">Atur filter di viewer, lalu unduh.</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach
</x-filament-panels::page>
