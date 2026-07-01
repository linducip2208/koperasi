@php
    /** @var \Illuminate\Support\Collection $upcoming */
    /** @var int $totalAmount */
    /** @var int $overdueCount */
@endphp

<x-filament-widgets::widget>
    <div class="rounded-2xl bg-gradient-to-br from-blue-500 via-cyan-500 to-teal-500 p-[1px] shadow-lg shadow-blue-500/20">
        <div class="rounded-2xl bg-white dark:bg-gray-900 p-5 h-full">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/40">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Cicilan Minggu Ini</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Total: Rp {{ number_format($totalAmount / 1_000_000, 1) }}jt</p>
                </div>
                @if($overdueCount > 0)
                    <span class="bg-rose-100 text-rose-700 text-[10px] font-bold px-2 py-1 rounded-md">
                        {{ $overdueCount }} TELAT
                    </span>
                @endif
            </div>

            <div class="space-y-2">
                @forelse($upcoming as $j)
                    @php
                        $daysLeft = \Carbon\Carbon::parse($j->tanggal_jatuh_tempo)->diffInDays(now(), false);
                        $isUrgent = $daysLeft >= -1; // hari ini atau besok
                    @endphp
                    <div class="bg-slate-50 dark:bg-gray-800 rounded-lg p-3 {{ $isUrgent ? 'ring-2 ring-amber-300' : '' }}">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-sm text-slate-900 dark:text-white truncate">{{ $j->pinjaman?->anggota?->nama ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($j->tanggal_jatuh_tempo)->translatedFormat('d M') }} · ke-{{ $j->angsuran_ke }}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-extrabold text-sm text-blue-700 dark:text-blue-400">Rp {{ number_format($j->total_angsuran / 1000) }}rb</div>
                                @if($isUrgent)
                                    <div class="text-[10px] font-bold text-amber-600">⚠ {{ $daysLeft >= 0 ? 'HARI INI' : abs($daysLeft).' HARI LAGI' }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        Tidak ada cicilan jatuh tempo minggu ini
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
