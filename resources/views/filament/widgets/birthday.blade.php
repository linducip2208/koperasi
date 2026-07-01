@php
    /** @var \Illuminate\Support\Collection $birthdays */
    /** @var int $totalMonth */
    /** @var int $todayCount */
@endphp

<x-filament-widgets::widget>
    <div class="rounded-2xl bg-gradient-to-br from-pink-500 via-fuchsia-500 to-purple-500 p-[1px] shadow-lg shadow-pink-500/20">
        <div class="rounded-2xl bg-white dark:bg-gray-900 p-5 h-full">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-pink-400 to-fuchsia-500 flex items-center justify-center text-white shadow-lg shadow-pink-500/40">
                    <span class="text-2xl">🎂</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Ulang Tahun</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ now()->translatedFormat('F') }} · {{ $totalMonth }} anggota</p>
                </div>
                @if($todayCount > 0)
                    <span class="bg-pink-500 text-white text-[10px] font-bold px-2 py-1 rounded-md animate-pulse">
                        🎉 {{ $todayCount }} HARI INI
                    </span>
                @endif
            </div>

            <div class="space-y-2">
                @forelse($birthdays as $a)
                    @php
                        $isToday = \Carbon\Carbon::parse($a->tanggal_lahir)->day === now()->day;
                        $isUmurMs = \Carbon\Carbon::parse($a->tanggal_lahir)->diffInYears(now());
                    @endphp
                    <div class="bg-slate-50 dark:bg-gray-800 rounded-lg p-3 {{ $isToday ? 'ring-2 ring-pink-300 bg-gradient-to-r from-pink-50 to-fuchsia-50 dark:from-pink-900/20 dark:to-fuchsia-900/20' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-pink-400 to-fuchsia-500 text-white font-extrabold flex items-center justify-center text-sm flex-shrink-0">
                                {{ substr($a->nama, 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-sm text-slate-900 dark:text-white truncate">
                                    {{ $a->nama }} {{ $isToday ? '🎉' : '' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    {{ \Carbon\Carbon::parse($a->tanggal_lahir)->translatedFormat('d M') }} · {{ $isUmurMs }}th
                                </div>
                            </div>
                            @if($isToday && $a->telp)
                                <a href="https://wa.me/62{{ ltrim($a->telp, '0') }}?text=Selamat%20Ulang%20Tahun,%20{{ urlencode($a->nama) }}!%20Semoga%20panjang%20umur%20%26%20sukses%20selalu." target="_blank"
                                    class="bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold px-2 py-1 rounded-md flex-shrink-0">
                                    🎁 WA
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        Tidak ada ulang tahun bulan ini
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
