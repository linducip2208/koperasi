<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            ⚡ Aktivitas Terbaru
        </x-slot>
        <x-slot name="description">
            10 transaksi simpan-pinjam terakhir
        </x-slot>

        <div class="space-y-3">
            @forelse($aktivitas as $a)
                <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                    <div class="w-10 h-10 rounded-xl bg-{{ $a['color'] }}-100 dark:bg-{{ $a['color'] }}-900/30 flex items-center justify-center text-lg">
                        {{ $a['icon'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm text-gray-900 dark:text-white truncate">{{ $a['title'] }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $a['desc'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-sm text-gray-900 dark:text-white">{{ $a['amount'] }}</div>
                        <div class="text-xs text-gray-500">{{ $a['time'] }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <div class="text-5xl mb-3">📭</div>
                    <p class="text-sm">Belum ada aktivitas transaksi.</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
