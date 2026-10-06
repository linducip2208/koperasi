<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Installer standalone: 1 instalasi = 1 koperasi = 1 database = 1 lisensi.
 * Terkunci otomatis setelah selesai (storage/app/.installed).
 */
class InstallController extends Controller
{
    public const LOCK = 'app/.installed';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists(self::LOCK)) {
                abort(404);
            }
            return $next($request);
        });
    }

    public function show(Request $request, string $step = 'welcome')
    {
        $steps = ['welcome', 'requirements', 'database', 'application', 'cooperative', 'admin', 'license', 'finish'];
        if (! in_array($step, $steps, true)) abort(404);

        $data = session('install', []);
        $checks = $step === 'requirements' ? $this->requirements() : [];

        return view('install.wizard', compact('step', 'steps', 'data', 'checks'));
    }

    public function store(Request $request, string $step)
    {
        $data = session('install', []);

        match ($step) {
            'database' => $this->saveDatabase($request, $data),
            'application' => $this->saveApplication($request, $data),
            'cooperative' => $this->saveCooperative($request, $data),
            'admin' => $this->saveAdmin($request, $data),
            default => null,
        };

        $order = ['welcome', 'requirements', 'database', 'application', 'cooperative', 'admin', 'license', 'finish'];
        $next = $order[min(array_search($step, $order) + 1, count($order) - 1)];

        // Eksekusi instalasi saat masuk finish.
        if ($next === 'finish' && $step === 'license') {
            $this->runInstall($data);
        }

        return redirect()->route('install.show', $next);
    }

    /** Daftar cek environment untuk step requirements. */
    public function requirements(): array
    {
        $checks = [];
        $checks[] = ['label' => 'PHP >= 8.2', 'ok' => version_compare(PHP_VERSION, '8.2.0', '>=')];
        foreach (['pdo', 'openssl', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'] as $ext) {
            $checks[] = ['label' => "Ekstensi {$ext}", 'ok' => extension_loaded($ext)];
        }
        foreach (['storage/app' => storage_path('app'), 'storage/logs' => storage_path('logs'), 'bootstrap/cache' => base_path('bootstrap/cache')] as $label => $path) {
            $checks[] = ['label' => "Writable {$label}", 'ok' => is_writable($path)];
        }
        $checks[] = ['label' => '.env dapat ditulis', 'ok' => ! file_exists(base_path('.env')) || is_writable(base_path('.env'))];
        return $checks;
    }

    protected function saveDatabase(Request $request, array &$data): void
    {
        $v = $request->validate([
            'db_connection' => ['required', 'in:sqlite,mysql'],
            'db_database' => ['required', 'string', 'max:255'],
            'db_host' => ['nullable', 'string'],
            'db_port' => ['nullable', 'string'],
            'db_username' => ['nullable', 'string'],
            'db_password' => ['nullable', 'string'],
        ]);

        // Uji koneksi sebelum disimpan.
        config(['database.default' => $v['db_connection']]);
        if ($v['db_connection'] === 'sqlite') {
            $path = str_ends_with($v['db_database'], '.sqlite') ? $v['db_database'] : database_path($v['db_database'].'.sqlite');
            if (! file_exists($path)) touch($path);
            config(['database.connections.sqlite.database' => $path]);
        } else {
            config([
                'database.connections.mysql.host' => $v['db_host'] ?: '127.0.0.1',
                'database.connections.mysql.port' => $v['db_port'] ?: '3306',
                'database.connections.mysql.database' => $v['db_database'],
                'database.connections.mysql.username' => $v['db_username'] ?? '',
                'database.connections.mysql.password' => $v['db_password'] ?? '',
            ]);
        }
        DB::purge();
        DB::connection()->getPdo();

        $data['database'] = $v;
        session(['install' => $data]);
        $this->writeEnv($v);
    }

    protected function saveApplication(Request $request, array &$data): void
    {
        $v = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'app_url' => ['required', 'url', 'max:255'],
            'timezone' => ['required', 'in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura'],
        ]);
        $data['application'] = $v;
        session(['install' => $data]);
        $this->writeEnv(['APP_NAME' => '"'.$v['app_name'].'"', 'APP_URL' => $v['app_url']]);
    }

    protected function saveCooperative(Request $request, array &$data): void
    {
        $v = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'operation_mode' => ['required', 'in:konvensional,syariah,dual'],
            'telp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'tahun_buku' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);
        $data['cooperative'] = $v;
        session(['install' => $data]);
    }

    protected function saveAdmin(Request $request, array &$data): void
    {
        $v = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);
        $data['admin'] = ['name' => $v['name'], 'email' => $v['email'], 'password' => $v['password']];
        session(['install' => $data]);
    }

    protected function runInstall(array $data): void
    {
        Artisan::call('migrate', ['--force' => true]);

        $coop = $data['cooperative'] ?? [];
        $tenant = Tenant::first() ?? new Tenant();
        $tenant->fill([
            'nama' => $coop['nama'] ?? 'Koperasi',
            'operation_mode' => $coop['operation_mode'] ?? 'konvensional',
            'telp' => $coop['telp'] ?? null,
            'email' => $coop['email'] ?? null,
            'alamat' => $coop['alamat'] ?? null,
            'tahun_buku' => $coop['tahun_buku'] ?? now()->year,
            'status' => 'aktif',
        ])->save();

        $adm = $data['admin'] ?? [];
        $user = User::firstOrCreate(
            ['email' => $adm['email'] ?? 'admin@koperasi.local'],
            ['name' => $adm['name'] ?? 'Administrator', 'password' => Hash::make($adm['password'] ?? \Illuminate\Support\Str::random(16)), 'tenant_id' => $tenant->id]
        );
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
            $user->assignRole($role);
        }

        \Illuminate\Support\Facades\Storage::disk('local')->put(self::LOCK, json_encode([
            'installed_at' => now()->toDateTimeString(),
            'version' => config('product.version'),
        ]));
        session()->forget('install');
    }

    /** Tulis kredensial ke .env (hanya key yang diizinkan). */
    protected function writeEnv(array $values): void
    {
        $map = [
            'db_connection' => 'DB_CONNECTION', 'db_host' => 'DB_HOST', 'db_port' => 'DB_PORT',
            'db_database' => 'DB_DATABASE', 'db_username' => 'DB_USERNAME', 'db_password' => 'DB_PASSWORD',
            'APP_NAME' => 'APP_NAME', 'APP_URL' => 'APP_URL',
        ];
        $path = base_path('.env');
        if (! file_exists($path)) copy(base_path('.env.example'), $path);
        $env = file_get_contents($path);
        foreach ($values as $k => $v) {
            if (! isset($map[$k])) continue;
            $key = $map[$k];
            $val = str_contains((string) $v, ' ') ? '"'.$v.'"' : $v;
            if (preg_match("/^{$key}=.*/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $env);
            } else {
                $env .= "\n{$key}={$val}";
            }
        }
        file_put_contents($path, $env, LOCK_EX);
    }
}
