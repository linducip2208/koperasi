@php
    /** @var \Illuminate\Support\Collection $pengajuan */
    /** @var int $totalCount */
    /** @var int $totalNilai */
@endphp

<x-filament-widgets::widget>
    <div class="rounded-2xl bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 p-[1px] shadow-lg shadow-amber-500/20">
        <div class="rounded-2xl bg-white dark:bg-gray-900 p-5 h-full">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/40">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Persetujuan Tertunda</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $totalCount }} pengajuan · Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
                </div>
                @if($totalCount > 0)
                    <span class="bg-rose-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">{{ $totalCount }}</span>
                @endif
            </div>

            <div class="space-y-2">
                @forelse($pengajuan as $p)
                    <a href="/admin/pinjamen/{{ $p->id }}/edit" class="block bg-slate-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-gray-700 rounded-lg p-3 transition group">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-sm text-slate-900 dark:text-white truncate">{{ $p->anggota?->nama ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">{{ $p->nomor_akad }}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-extrabold text-sm text-amber-700 dark:text-amber-400">Rp {{ number_format($p->plafon / 1_000_000, 1) }}jt</div>
                                <div class="text-[10px] text-slate-500">{{ $p->tenor }}bln</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-amber-600 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        Tidak ada pengajuan tertunda
                    </div>
                @endforelse
            </div>

            @if($totalCount > 5)
                <a href="/admin/pinjamen?tableFilters%5Bstatus%5D%5Bvalue%5D=pengajuan" class="block text-center text-xs font-bold text-amber-700 hover:text-amber-900 mt-3 pt-3 border-t border-slate-100 dark:border-gray-800">
                    Lihat {{ $totalCount - 5 }} pengajuan lainnya →
                </a>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
