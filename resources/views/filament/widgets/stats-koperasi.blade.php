@php $stats = $this->getStats(); @endphp

<x-filament-widgets::widget>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($stats as $i => $s)
            @php
                $colorMap = [
                    'emerald' => ['bg'=>'rgba(16,185,129,0.1)','border'=>'rgba(16,185,129,0.25)','text'=>'#047857','solid'=>'#10b981'],
                    'cyan'    => ['bg'=>'rgba(6,182,212,0.1)','border'=>'rgba(6,182,212,0.25)','text'=>'#0e7490','solid'=>'#06b6d4'],
                    'amber'   => ['bg'=>'rgba(245,158,11,0.1)','border'=>'rgba(245,158,11,0.25)','text'=>'#b45309','solid'=>'#f59e0b'],
                    'rose'    => ['bg'=>'rgba(244,63,94,0.1)','border'=>'rgba(244,63,94,0.25)','text'=>'#be123c','solid'=>'#f43f5e'],
                ];
                $c = $colorMap[$s['color']] ?? $colorMap['emerald'];
            @endphp
            <div class="koperasi-stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/70 dark:border-white/10 p-5 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:shadow-{{ $s['color'] }}-500/10"
                 style="--stat-color: {{ $c['solid'] }};">
                {{-- Decorative gradient blob --}}
                <div class="absolute top-0 right-0 w-40 h-40 -translate-y-1/3 translate-x-1/3 rounded-full opacity-[0.08] pointer-events-none"
                     style="background: radial-gradient(circle, {{ $c['solid'] }} 0%, transparent 70%);"></div>

                <div class="relative">
                    {{-- Header: icon + change badge --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-lg"
                             style="background: linear-gradient(135deg, {{ $c['solid'] }}, {{ $c['solid'] }}cc); box-shadow: 0 8px 20px -6px {{ $c['solid'] }}77;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">{!! $s['icon'] !!}</svg>
                        </div>
                        <div class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2 py-1 rounded-md uppercase tracking-wider"
                             style="background: {{ $c['bg'] }}; color: {{ $c['text'] }}; border: 1px solid {{ $c['border'] }};">
                            @if($s['positive'])
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/></svg>
                            @else
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            @endif
                            {{ $s['change'] }}
                        </div>
                    </div>

                    {{-- Value + label --}}
                    <p class="text-[11px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-[0.1em] mb-1">{{ $s['label'] }}</p>
                    <p class="text-[28px] font-extrabold text-gray-900 dark:text-white tracking-tighter leading-none mb-1">{{ $s['value'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-3">{{ $s['sub'] }}</p>

                    {{-- Sparkline --}}
                    <div class="h-12 -mx-1" id="filament-spark-{{ $i }}" data-spark="{{ json_encode($s['data']) }}" data-color="{{ $s['spark'] }}"></div>
                </div>
            </div>
        @endforeach
    </div>

    @once
    @push('scripts')
    @endpush
    @endonce

    <script>
        (function () {
            function renderSparks() {
                if (typeof ApexCharts === 'undefined') {
                    setTimeout(renderSparks, 200);
                    return;
                }
                document.querySelectorAll('[id^="filament-spark-"]').forEach(el => {
                    if (el.dataset.rendered === '1') return;
                    el.dataset.rendered = '1';
                    const data = JSON.parse(el.dataset.spark);
                    const color = el.dataset.color;
                    new ApexCharts(el, {
                        series: [{ data: data }],
                        chart: { type: 'area', height: 48, sparkline: { enabled: true }, animations: { enabled: true, speed: 700 } },
                        stroke: { curve: 'smooth', width: 2.5 },
                        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0, stops: [0, 100] } },
                        colors: [color],
                        tooltip: { enabled: false },
                    }).render();
                });
            }
            // Load ApexCharts CDN once
            if (typeof ApexCharts === 'undefined' && !window.__apexLoading) {
                window.__apexLoading = true;
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/apexcharts';
                s.onload = renderSparks;
                document.head.appendChild(s);
            } else {
                renderSparks();
            }
        })();
    </script>
</x-filament-widgets::widget>
