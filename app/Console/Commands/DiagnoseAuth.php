<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/**
 * koperasi:diagnose-auth — End-to-end auth diagnostic.
 *
 * Walks through 12 critical checks — output di setiap step memberikan
 * exact root cause kalau ada yang salah. Juga simulate full HTTP flow
 * (GET login → POST credentials → GET /admin).
 */
class DiagnoseAuth extends Command
{
    protected $signature = 'koperasi:diagnose-auth';
    protected $description = 'Deep diagnostic untuk masalah login admin/portal';

    public function handle(Kernel $kernel): int
    {
        $this->info('🔬 KOPERASI AUTH DIAGNOSTIC — DEEP DIVE');
        $this->newLine();

        $errors = [];
        $check = fn (string $name, bool $ok, string $detail = '') => $this->showCheck($name, $ok, $detail, $errors);

        /* === 1. DB CONNECTION === */
        $this->line('  <fg=cyan;options=bold>[1] Database Connection</>');
        $driver = DB::connection()->getDriverName();
        $check('DB driver is MySQL', $driver === 'mysql', "Actual: {$driver}");

        try {
            $version = DB::select('SELECT VERSION() as v')[0]->v ?? '?';
            $check('MySQL connection working', true, "Version: {$version}");
            $dbName = DB::connection()->getDatabaseName();
            $check('Database name', !empty($dbName), "DB: {$dbName}");
        } catch (\Throwable $e) {
            $check('MySQL connection working', false, $e->getMessage());
            return $this->summary($errors, 1);
        }

        /* === 2. SESSION DRIVER === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[2] Session Driver</>');
        $sessionDriver = config('session.driver');
        $check('Session driver', in_array($sessionDriver, ['database', 'file']), "Driver: {$sessionDriver}");

        if ($sessionDriver === 'database') {
            try {
                $sessionTable = DB::table('sessions')->count();
                $check('Sessions table accessible', true, "Rows: {$sessionTable}");
            } catch (\Throwable $e) {
                $check('Sessions table accessible', false, $e->getMessage());
            }
        }

        /* === 3. ADMIN USER === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[3] Admin User in Database</>');
        $admin = User::where('email', 'admin@koperasi.local')->first();
        $check('Admin user exists', $admin !== null, $admin ? "ID: {$admin->id}, Name: {$admin->name}" : 'NOT FOUND');

        if (!$admin) {
            $this->error('  → CRITICAL: Run `php artisan db:seed --class=AdminUserSeeder` to recreate admin user.');
            return $this->summary($errors, 3);
        }

        $check('Admin is aktif', (bool) $admin->aktif, "aktif: " . ($admin->aktif ? 'true' : 'false'));
        $check('Admin email verified or null', true, "email: {$admin->email}");

        /* === 4. PASSWORD HASH === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[4] Password Hash Verification</>');
        $passwordCheck = Hash::check('admin123', $admin->password);
        $check('Hash::check("admin123") works', $passwordCheck, $passwordCheck ? 'Password "admin123" matches stored hash' : 'Password DOES NOT match — admin123 invalid');

        if (!$passwordCheck) {
            $this->warn('  → Resetting admin password to "admin123"...');
            $admin->update(['password' => Hash::make('admin123')]);
            $admin->refresh();
            $passwordCheck = Hash::check('admin123', $admin->password);
            $check('After reset: Hash::check works', $passwordCheck);
        }

        /* === 5. ROLE & PERMISSIONS === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[5] Role & Permissions</>');
        $hasRole = $admin->hasRole('super-admin');
        $check('Admin has super-admin role', $hasRole, $hasRole ? 'role: super-admin' : 'NO super-admin role');
        if (!$hasRole) {
            $this->warn('  → Assigning super-admin role...');
            $admin->assignRole('super-admin');
        }
        $permsCount = $admin->getAllPermissions()->count();
        $check('Admin has permissions', $permsCount > 0, "Count: {$permsCount}");

        /* === 6. AUTH::ATTEMPT === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[6] Auth::attempt() Functional Test</>');
        Auth::logout();
        $attemptResult = Auth::attempt(['email' => 'admin@koperasi.local', 'password' => 'admin123']);
        $check('Auth::attempt(admin/admin123) succeeds', $attemptResult);

        if ($attemptResult) {
            $authedUser = Auth::user();
            $check('Auth::user() returns admin', $authedUser?->email === 'admin@koperasi.local', "Authed: " . ($authedUser?->email ?? 'null'));
        }

        /* === 7. CAN ACCESS PANEL === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[7] Filament Panel Access</>');
        try {
            $panel = \Filament\Facades\Filament::getPanel('admin');
            $check('Admin panel registered', $panel !== null);
            $canAccess = $admin->canAccessPanel($panel);
            $check('Admin can access panel', $canAccess);
        } catch (\Throwable $e) {
            $check('Admin panel registered', false, $e->getMessage());
        }

        /* === 8. ROUTES === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[8] Critical Routes</>');
        $routes = collect(Route::getRoutes())->mapWithKeys(fn ($r) => [$r->methods()[0] . ' /' . $r->uri() => true])->all();
        $check('GET /admin/login exists', isset($routes['GET /admin/login']));
        $check('POST /admin/login exists', isset($routes['POST /admin/login']));
        $check('GET /admin exists', isset($routes['GET /admin']));
        $check('GET /portal/login exists', isset($routes['GET /portal/login']));
        $check('POST /portal/login exists', isset($routes['POST /portal/login']));
        $check('GET /login-admin (simple) exists', isset($routes['GET /login-admin']));

        /* === 9. SIMULATE GET /admin/login === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[9] Simulate GET /admin/login</>');
        Auth::logout();
        try {
            $req = Request::create('/admin/login', 'GET');
            $res = $kernel->handle($req);
            $check('GET /admin/login returns 200', $res->getStatusCode() === 200, "Status: {$res->getStatusCode()}, Size: " . strlen($res->getContent()) . 'b');
            $hasForm = str_contains($res->getContent(), 'wire:submit') || str_contains($res->getContent(), '<form');
            $check('Login form rendered', $hasForm);
            $hasCsrfMeta = preg_match('/csrf-token" content="[^"]+"/', $res->getContent()) === 1;
            $check('CSRF meta tag present', (bool) $hasCsrfMeta);
        } catch (\Throwable $e) {
            $check('GET /admin/login works', false, $e->getMessage());
        }

        /* === 10. SIMULATE POST /admin/login === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[10] Simulate POST /admin/login (via fallback)</>');
        try {
            $req = Request::create('/admin/login', 'POST', [
                'email' => 'admin@koperasi.local',
                'password' => 'admin123',
            ]);
            $res = $kernel->handle($req);
            $status = $res->getStatusCode();
            $check('POST /admin/login returns 302', $status === 302, "Status: {$status}, Location: " . $res->headers->get('Location', '?'));
        } catch (\Throwable $e) {
            $check('POST /admin/login works', false, $e->getMessage());
        }

        /* === 11. SIMULATE GET /admin (logged in) === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[11] Simulate GET /admin (logged in)</>');
        try {
            $admin = User::where('email', 'admin@koperasi.local')->first();
            Auth::login($admin);
            $req = Request::create('/admin', 'GET');
            $res = $kernel->handle($req);
            $body = $res->getContent();
            $status = $res->getStatusCode();
            $check('GET /admin returns 200', $status === 200, "Status: {$status}, Size: " . strlen($body) . 'b');

            $hasSidebar = substr_count($body, 'fi-sidebar-item-label');
            $check('Sidebar items rendered', $hasSidebar >= 10, "Items: {$hasSidebar}");
            $hasGroups = substr_count($body, 'fi-sidebar-group-label flex-1');
            $check('Sidebar groups rendered', $hasGroups >= 5, "Groups: {$hasGroups}");
        } catch (\Throwable $e) {
            $check('GET /admin works', false, $e->getMessage());
        }

        /* === 12. STATIC ASSETS === */
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>[12] Static Assets</>');
        $assets = [
            'public/css/filament/filament/app.css',
            'public/js/filament/filament/app.js',
            'public/js/filament/notifications/notifications.js',
            'public/css/filament/forms/forms.css',
        ];
        foreach ($assets as $a) {
            $path = base_path($a);
            $exists = file_exists($path);
            $size = $exists ? round(filesize($path) / 1024) . 'KB' : '?';
            $check("File exists: {$a}", $exists, $size);
        }

        return $this->summary($errors, 0);
    }

    private function showCheck(string $name, bool $ok, string $detail, array &$errors): void
    {
        $marker = $ok ? '<fg=green;options=bold>✓</>' : '<fg=red;options=bold>✗</>';
        $line = "    {$marker} " . str_pad($name, 50) . ($detail ? "<fg=gray>  ({$detail})</>" : '');
        $this->line($line);
        if (!$ok) {
            $errors[] = "{$name}: {$detail}";
        }
    }

    private function summary(array $errors, int $minSteps): int
    {
        $this->newLine();
        $this->info('═════════════════════════════════════════════════════════');
        if (empty($errors)) {
            $this->info('  ✅ ALL CHECKS PASSED — Backend is HEALTHY');
            $this->info('  Login admin SHOULD work. Issue is browser-side.');
            $this->newLine();
            $this->line('  <fg=yellow>Possible browser-side causes:</>');
            $this->line('    1. Stale cookies → buka /clear-session lalu Ctrl+F5');
            $this->line('    2. Old service worker → DevTools > Application > Clear storage');
            $this->line('    3. Browser extension blocking → coba Incognito mode');
            $this->line('    4. Cached old JS bundle → DevTools > Network > Disable cache');
            return self::SUCCESS;
        }

        $this->error("  ❌ FOUND " . count($errors) . " ISSUE(S):");
        foreach ($errors as $i => $e) {
            $this->line('    ' . ($i + 1) . '. ' . $e);
        }
        $this->info('═════════════════════════════════════════════════════════');
        return self::FAILURE;
    }
}
