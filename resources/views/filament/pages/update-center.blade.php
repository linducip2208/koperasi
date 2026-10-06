<x-filament-panels::page>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="fi-section rounded-xl bg-white dark:bg-gray-900 shadow p-6">
            <h3 class="font-bold mb-4">Versi</h3>
            <p class="text-sm text-gray-500">Terpasang</p>
            <p class="text-3xl font-extrabold">v{{ $current }}</p>
            <p class="text-sm text-gray-500 mt-4">Terbaru (cek terakhir: {{ $lastCheck['at'] ?? 'belum pernah' }})</p>
            <p class="text-3xl font-extrabold">{{ $lastCheck['latest'] ?? '—' }}</p>
            @if(! empty($lastCheck['notes']))
                <div class="mt-3 text-sm bg-gray-50 dark:bg-white/5 rounded-lg p-3 whitespace-pre-line">{{ $lastCheck['notes'] }}</div>
            @endif
            <div class="flex gap-2 mt-4">
                <x-filament::button wire:click="checkNow" color="primary">Check Update</x-filament::button>
            </div>
        </div>

        <div class="fi-section rounded-xl bg-white dark:bg-gray-900 shadow p-6">
            <h3 class="font-bold mb-4">Alur Update Aman (wajib)</h3>
            <ol class="text-sm space-y-2 list-decimal list-inside text-gray-600 dark:text-gray-300">
                <li>Backup database dulu (tombol di bawah — <code>php artisan app:backup</code>).</li>
                <li>Aktifkan maintenance mode: <code>php artisan down</code>.</li>
                <li>Update files sesuai paket rilis resmi.</li>
                <li>Jalankan <code>php artisan migrate --force</code>.</li>
                <li>Verifikasi: <code>php artisan app:health</code> dan <code>php artisan test</code>.</li>
                <li>Matikan maintenance: <code>php artisan up</code>.</li>
            </ol>
            <div class="mt-4">
                <x-filament::button wire:click="backupNow" color="success">Backup Sekarang</x-filament::button>
            </div>
            <p class="mt-3 text-xs text-rose-500 font-semibold">Jangan update tanpa backup. Restore tanpa backup = kehilangan data.</p>
        </div>
    </div>
</x-filament-panels::page>
