@php
    /** @var int $healthScore */
    /** @var array $grade */
    /** @var float $npl */
    /** @var float $ldr */
    /** @var int $totalAset */
    $colorClasses = [
        'emerald' => 'from-emerald-500 to-teal-500 text-emerald-600 bg-emerald-50',
        'blue'    => 'from-blue-500 to-cyan-500 text-blue-600 bg-blue-50',
        'amber'   => 'from-amber-500 to-yellow-500 text-amber-600 bg-amber-50',
        'orange'  => 'from-orange-500 to-red-500 text-orange-600 bg-orange-50',
        'rose'    => 'from-rose-500 to-pink-500 text-rose-600 bg-rose-50',
    ];
    $cls = $colorClasses[$grade['color']] ?? $colorClasses['blue'];
@endphp

<x-filament-widgets::widget>
    <div class="rounded-2xl bg-gradient-to-br {{ explode(' ', $cls)[0] }} {{ explode(' ', $cls)[1] }} p-[1px] shadow-lg">
        <div class="rounded-2xl bg-white dark:bg-gray-900 p-5 h-full">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ explode(' ', $cls)[0] }} {{ explode(' ', $cls)[1] }} flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Kesehatan Koperasi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Skor & rasio finansial</p>
                </div>
            </div>

            <div class="text-center py-3">
                <div class="relative inline-flex items-center justify-center w-32 h-32">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                        <path class="text-slate-200 dark:text-gray-700" stroke="currentColor" stroke-width="3" fill="none"
                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        <path class="{{ explode(' ', $cls)[2] }}" stroke="currentColor" stroke-width="3" fill="none"
                              stroke-dasharray="{{ $healthScore }}, 100" stroke-linecap="round"
                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    </svg>
                    <div class="absolute flex flex-col items-center">
                        <span class="font-extrabold text-3xl {{ explode(' ', $cls)[2] }}">{{ $grade['grade'] }}</span>
                        <span class="text-xs font-bold text-slate-500">{{ $healthScore }}/100</span>
                    </div>
                </div>
                <div class="mt-2 font-bold text-sm {{ explode(' ', $cls)[2] }}">{{ $grade['label'] }}</div>
            </div>

            <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-slate-100 dark:border-gray-800">
                <div class="text-center">
                    <div class="text-[10px] uppercase tracking-wider text-slate-500 font-bold">NPL</div>
                    <div class="font-extrabold text-sm {{ $npl > 5 ? 'text-rose-600' : ($npl > 2 ? 'text-amber-600' : 'text-emerald-600') }}">{{ $npl }}%</div>
                </div>
                <div class="text-center border-x border-slate-100 dark:border-gray-800">
                    <div class="text-[10px] uppercase tracking-wider text-slate-500 font-bold">LDR</div>
                    <div class="font-extrabold text-sm {{ ($ldr >= 70 && $ldr <= 90) ? 'text-emerald-600' : 'text-amber-600' }}">{{ $ldr }}%</div>
                </div>
                <div class="text-center">
                    <div class="text-[10px] uppercase tracking-wider text-slate-500 font-bold">ASET</div>
                    <div class="font-extrabold text-sm text-slate-900 dark:text-white">{{ number_format($totalAset / 1_000_000_000, 1) }}M</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
