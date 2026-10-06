<x-filament-panels::page>
    @php
        $color = ['OK' => 'emerald', 'WARNING' => 'amber', 'ERROR' => 'rose'][$summary['overall']];
    @endphp
    <div class="fi-section rounded-xl bg-white dark:bg-gray-900 shadow p-6 mb-6">
        <div class="flex items-center gap-4">
            <x-filament::badge :color="$color" size="lg">{{ $summary['overall'] }}</x-filament::badge>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                {{ $summary['total'] }} pemeriksaan ·
                <span class="text-rose-600 font-bold">{{ $summary['errors'] }} error</span> ·
                <span class="text-amber-600 font-bold">{{ $summary['warnings'] }} warning</span>
                · <span class="text-gray-400">tanpa secret yang ditampilkan</span>
            </div>
        </div>
    </div>

    <div class="fi-section rounded-xl bg-white dark:bg-gray-900 shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-gray-500 border-b">
                    <th class="px-4 py-3">Pemeriksaan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($checks as $c)
                    <tr class="border-b border-gray-100 dark:border-white/5">
                        <td class="px-4 py-2.5 font-semibold">{{ $c['label'] }}</td>
                        <td class="px-4 py-2.5">
                            <x-filament::badge :color="['OK' => 'success', 'WARNING' => 'warning', 'ERROR' => 'danger'][$c['status']]">
                                {{ $c['status'] }}
                            </x-filament::badge>
                        </td>
                        <td class="px-4 py-2.5 text-gray-500">{{ $c['detail'] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p class="mt-4 text-xs text-gray-400">CLI: <code>php artisan app:health</code> · <code>php artisan app:license-status</code></p>
</x-filament-panels::page>
