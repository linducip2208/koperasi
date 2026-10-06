<x-filament-panels::page>
    @php
        $d = $status['data'] ?? [];
        $badge = ['ACTIVE' => 'success', 'GRACE_PERIOD' => 'warning', 'UNPAIRED' => 'gray', 'EXPIRED' => 'danger', 'SUSPENDED' => 'danger', 'REVOKED' => 'danger', 'INVALID' => 'danger'][$status['status']] ?? 'gray';
        $features = $d['features'] ?? [];
        if (is_string($features)) $features = array_filter(array_map('trim', explode(',', $features)));
    @endphp

    <div class="grid md:grid-cols-2 gap-6">
        <div class="fi-section rounded-xl bg-white dark:bg-gray-900 shadow p-6">
            <h3 class="font-bold mb-4">Status Lisensi</h3>
            <table class="w-full text-sm">
                <tbody>
                    <tr class="border-b"><td class="py-2 text-gray-500">Product</td><td class="py-2 font-bold">{{ config('product.name') }} v{{ config('product.version') }}</td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">License ID</td><td class="py-2 font-mono">{{ $d['license_id'] ?? $d['id'] ?? '—' }}</td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">Customer</td><td class="py-2 font-semibold">{{ $d['customer'] ?? $d['cooperative'] ?? '—' }}</td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">Domain</td><td class="py-2 font-mono">{{ $domain }}</td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">Status</td><td class="py-2"><x-filament::badge :color="$badge">{{ $status['status'] }}</x-filament::badge></td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">Plan</td><td class="py-2 font-semibold">{{ strtoupper($d['plan'] ?? '—') }}</td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">Issued</td><td class="py-2">{{ $d['issued_at'] ?? '—' }}</td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">Expires</td><td class="py-2">{{ $d['expires_at'] ?? '—' }}</td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">Last heartbeat</td><td class="py-2">{{ $status['last_heartbeat'] ?? '—' }}</td></tr>
                    <tr class="border-b"><td class="py-2 text-gray-500">Grace deadline</td><td class="py-2">{{ $status['grace_deadline'] ?? '—' }}</td></tr>
                    <tr><td class="py-2 text-gray-500">Installation ID</td><td class="py-2 font-mono text-xs">{{ $status['installation_id'] }}</td></tr>
                </tbody>
            </table>
        </div>

        <div class="space-y-6">
            <div class="fi-section rounded-xl bg-white dark:bg-gray-900 shadow p-6">
                <h3 class="font-bold mb-3">Features</h3>
                @forelse($features as $f)
                    <x-filament::badge color="info" class="mr-1 mb-1">{{ $f }}</x-filament::badge>
                @empty
                    <p class="text-sm text-gray-400">Tidak ada daftar fitur pada payload.</p>
                @endforelse
            </div>

            <div class="fi-section rounded-xl bg-white dark:bg-gray-900 shadow p-6">
                <h3 class="font-bold mb-3">Actions</h3>
                <div class="flex flex-wrap gap-2">
                    <x-filament::button wire:click="recheck" color="primary">Recheck ke Server</x-filament::button>
                    <x-filament::button tag="a" href="{{ route('activation.show') }}" color="gray">Aktivasi / Pairing</x-filament::button>
                </div>
                <p class="mt-3 text-xs text-gray-400">Diagnostic (aman dibagikan ke support):<br>
                <code class="break-all">product={{ config('product.slug') }} v{{ config('product.version') }} · install={{ $status['installation_id'] }} · domain={{ $domain }} · status={{ $status['status'] }}</code></p>
                <p class="mt-2 text-xs text-rose-500">Tidak pernah tampil di sini: private key, API secret, kredensial server lisensi.</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
