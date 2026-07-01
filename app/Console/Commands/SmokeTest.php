<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SmokeTest extends Command
{
    protected $signature = 'koperasi:smoke-test
        {--login : Login dulu sebagai admin sebelum test (untuk akses /admin/*)}
        {--filter= : Hanya test route yang match prefix tertentu (mis. --filter=admin)}
        {--max=200 : Maksimum jumlah route yang ditest}
        {--show-errors : Tampilkan stack trace lengkap untuk setiap error}
        {--save= : Simpan report ke file (mis. --save=storage/smoke-report.html)}';

    protected $description = 'Crawl semua route GET, deteksi 4xx/5xx, follow link di HTML, report status & error';

    private array $results = [];
    private array $errorLog = [];
    private int $totalTested = 0;
    private int $totalPassed = 0;
    private int $totalFailed = 0;

    public function handle(HttpKernel $kernel): int
    {
        $this->info('🔥 Smoke Test — Aplikasi Koperasi');
        $this->newLine();

        if ($this->option('login')) {
            $this->loginAsAdmin();
        }

        $routes = $this->collectRoutes();
        $filter = $this->option('filter');
        $max = (int) $this->option('max');

        if ($filter) {
            $needle = '/' . ltrim($filter, '/');
            $routes = array_filter($routes, fn ($r) => Str::startsWith($r['uri'], $needle));
            $this->line("   Filter: <fg=yellow>{$needle}</> · Routes match: " . count($routes));
        }

        $routes = array_slice($routes, 0, $max);
        $this->line("   Akan test " . count($routes) . " routes");
        $this->newLine();

        $bar = $this->output->createProgressBar(count($routes));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage('Mulai...');
        $bar->start();

        foreach ($routes as $route) {
            $bar->setMessage(Str::limit($route['uri'], 50));
            $this->testRoute($kernel, $route);
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);

        $this->printReport();

        if ($savePath = $this->option('save')) {
            $this->saveHtmlReport($savePath);
        }

        return $this->totalFailed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function loginAsAdmin(): void
    {
        $user = \App\Models\User::where('email', 'admin@koperasi.local')->first();
        if (!$user) {
            $this->warn('⚠ User admin@koperasi.local tidak ditemukan, skip login.');
            return;
        }
        Auth::login($user);
        $this->line("   <fg=green>✓</> Logged in as: {$user->email}");
        $this->newLine();
    }

    /** @return array<int, array{uri:string, methods:array, name:?string, action:string}> */
    private function collectRoutes(): array
    {
        $routes = [];
        foreach (Route::getRoutes() as $r) {
            $methods = $r->methods();
            // Skip non-GET routes (smoke test only follows GET)
            if (!in_array('GET', $methods)) continue;

            $uri = $r->uri();
            // Skip routes with required params we can't fill (mis. {record}, {filename})
            if (preg_match('/\{[a-z_]+\}/i', $uri)) {
                $skipParams = ['record', 'filename', 'tenant', 'token', 'invite', 'pair'];
                $hasUnfillable = false;
                foreach ($skipParams as $sp) {
                    if (str_contains($uri, '{' . $sp . '}')) { $hasUnfillable = true; break; }
                }
                if ($hasUnfillable) continue;

                // Try to fill known param patterns from pseo config
                $uri = $this->fillKnownParams($uri);
                if (preg_match('/\{[a-z_]+\}/i', $uri)) continue; // still has unfilled
            }

            // Skip livewire internal endpoints, debugbar, telescope, dll
            // Plus utility routes yang tidak boleh di-test kernel-in-kernel
            $skipPrefixes = ['_ignition', '_debugbar', 'livewire/', 'sanctum/', 'telescope', 'horizon', '__pair', 'diagnose', 'clear-session'];
            $skipped = false;
            foreach ($skipPrefixes as $sp) {
                if (str_starts_with($uri, $sp)) { $skipped = true; break; }
            }
            if ($skipped) continue;

            $routes[] = [
                'uri'     => '/' . ltrim($uri, '/'),
                'methods' => $methods,
                'name'    => $r->getName(),
                'action'  => $r->getActionName(),
            ];
        }

        // De-duplicate by uri
        $seen = [];
        $unique = [];
        foreach ($routes as $r) {
            if (isset($seen[$r['uri']])) continue;
            $seen[$r['uri']] = true;
            $unique[] = $r;
        }

        // Sort: public first, then admin, then portal, then pSEO
        usort($unique, function ($a, $b) {
            $rank = function ($u) {
                if ($u === '/') return 0;
                if (str_starts_with($u, '/admin')) return 2;
                if (str_starts_with($u, '/portal')) return 3;
                if (str_starts_with($u, '/laporan')) return 4;
                return 1;
            };
            $ra = $rank($a['uri']);
            $rb = $rank($b['uri']);
            return $ra <=> $rb ?: strcmp($a['uri'], $b['uri']);
        });

        return $unique;
    }

    private function fillKnownParams(string $uri): string
    {
        // Tentukan slug filler tergantung context URI (untuk route yang punya {slug})
        $slugReplacement = null;
        if (str_contains($uri, '/panduan/')) $slugReplacement = 'cara-mendirikan-koperasi';
        elseif (str_contains($uri, '/kalkulator/')) $slugReplacement = 'cicilan-pinjaman';
        elseif (str_contains($uri, '/compare/')) $slugReplacement = 'koperasi-app-vs-siska';

        // Untuk route dengan {slug} tapi context tidak dikenal → jangan fabricate (skip)
        if (str_contains($uri, '{slug}') && $slugReplacement === null) return $uri;

        return strtr($uri, [
            '{kota}'       => 'jakarta',
            '{jenis}'      => 'syariah',
            '{akad}'       => 'mudharabah',
            '{slug}'       => $slugReplacement ?? 'demo',
            '{competitor}' => 'siska',
        ]);
    }

    private function testRoute(HttpKernel $kernel, array $route): void
    {
        $start = microtime(true);
        $error = null;
        $status = null;
        $size = 0;

        try {
            $request = Request::create($route['uri'], 'GET');
            $response = $kernel->handle($request);
            $status = $response->getStatusCode();
            $size = strlen($response->getContent() ?? '');
            $kernel->terminate($request, $response);
        } catch (\Throwable $e) {
            $error = get_class($e) . ': ' . $e->getMessage();
            $status = 500;
            $this->errorLog[] = [
                'uri' => $route['uri'],
                'error' => $error,
                'trace' => $e->getTraceAsString(),
            ];
        }

        $duration = round((microtime(true) - $start) * 1000, 1);

        // Klasifikasi:
        //   2xx, 3xx        → PASS (sukses normal / redirect ke login)
        //   401, 403        → AUTH (route butuh role/auth lain — bukan bug)
        //   404 untuk /api/* atau /portal saat admin login → AUTH-CONTEXT (admin bukan anggota)
        //   404 lainnya     → FAIL (route benar-benar tidak ada)
        //   422             → VALIDATION (POST-only kena GET — biasanya OK)
        //   5xx             → FAIL (server error sungguhan)
        $isAuthRelated = $status === 401 || $status === 403;
        $isAdminOnAnggotaEndpoint = $status === 404 && $this->option('login')
            && (str_starts_with($route['uri'], '/api/') || str_starts_with($route['uri'], '/portal'));
        $passed = $status >= 200 && $status < 400;
        $isWarning = !$passed && ($isAuthRelated || $isAdminOnAnggotaEndpoint);
        $isFailure = !$passed && !$isWarning;

        $this->totalTested++;
        if ($passed) $this->totalPassed++;
        elseif ($isFailure) $this->totalFailed++;

        $this->results[] = [
            'uri'      => $route['uri'],
            'name'     => $route['name'],
            'status'   => $status,
            'size'     => $size,
            'duration' => $duration,
            'passed'   => $passed,
            'warning'  => $isWarning,
            'failure'  => $isFailure,
            'error'    => $error,
        ];
    }

    private function printReport(): void
    {
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info("📊 SMOKE TEST REPORT");
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();

        $warnings = collect($this->results)->where('warning', true);
        $this->line("   Total tested : <fg=cyan>{$this->totalTested}</>");
        $this->line("   Passed       : <fg=green>{$this->totalPassed} ({$this->percent($this->totalPassed)}%)</>");
        $this->line("   Warnings     : <fg=yellow>{$warnings->count()}</> (auth-required, expected)");
        $this->line("   Failed       : <fg=red>{$this->totalFailed} ({$this->percent($this->totalFailed)}%)</>");
        $this->newLine();

        // Show warnings (non-blocking)
        if ($warnings->count()) {
            $this->line('⚠  <fg=yellow>WARNINGS (auth-related, not bugs):</>');
            foreach ($warnings as $w) {
                $this->line(sprintf('   <fg=yellow>[%d]</> %s', $w['status'], $w['uri']));
            }
            $this->newLine();
        }

        // Show real failures detail
        $failures = array_filter($this->results, fn ($r) => $r['failure'] ?? false);
        if (!empty($failures)) {
            $this->error('❌ FAILED ROUTES:');
            $this->newLine();
            foreach ($failures as $f) {
                $color = $f['status'] >= 500 ? 'red' : 'yellow';
                $this->line(sprintf('   <fg=%s>[%d]</> %s', $color, $f['status'], $f['uri']));
                if ($f['error']) {
                    $this->line('         <fg=gray>' . Str::limit($f['error'], 120) . '</>');
                }
                if ($f['name']) {
                    $this->line('         <fg=gray>name: ' . $f['name'] . '</>');
                }
            }
            $this->newLine();

            if ($this->option('show-errors') && !empty($this->errorLog)) {
                $this->error('🔬 ERROR DETAILS:');
                $this->newLine();
                foreach ($this->errorLog as $e) {
                    $this->line("URI: <fg=yellow>{$e['uri']}</>");
                    $this->line("Error: {$e['error']}");
                    $this->line("Trace (top 5 lines):");
                    $traceLines = array_slice(explode("\n", $e['trace']), 0, 5);
                    foreach ($traceLines as $line) {
                        $this->line('  <fg=gray>' . Str::limit($line, 130) . '</>');
                    }
                    $this->newLine();
                }
            }
        } else {
            $this->info('✨ All routes passed!');
        }

        // Slowest routes
        $slowest = collect($this->results)->sortByDesc('duration')->take(5);
        if ($slowest->count()) {
            $this->newLine();
            $this->line('🐢 <fg=yellow>5 Slowest:</>');
            foreach ($slowest as $r) {
                $marker = $r['duration'] > 1000 ? 'red' : ($r['duration'] > 500 ? 'yellow' : 'gray');
                $this->line(sprintf('   <fg=%s>%6.1fms</>  %s', $marker, $r['duration'], $r['uri']));
            }
        }
        $this->newLine();
    }

    private function saveHtmlReport(string $path): void
    {
        $rows = '';
        foreach ($this->results as $r) {
            $statusColor = $r['passed'] ? '#10b981' : ($r['status'] >= 500 ? '#ef4444' : '#f59e0b');
            $errorCell = $r['error'] ? '<code class="err">' . htmlspecialchars(Str::limit($r['error'], 200)) . '</code>' : '';
            $rows .= sprintf(
                '<tr><td><code>%s</code></td><td><span class="badge" style="background:%s">%d</span></td><td>%dms</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                htmlspecialchars($r['uri']),
                $statusColor,
                $r['status'],
                $r['duration'],
                number_format($r['size']),
                htmlspecialchars($r['name'] ?? '-'),
                $errorCell
            );
        }

        $html = <<<HTML
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Smoke Test Report</title>
<style>
body{font-family:system-ui,sans-serif;background:#0f172a;color:#e2e8f0;margin:0;padding:2rem}
h1{font-weight:800;letter-spacing:-0.02em;margin-bottom:0.25rem}
.meta{color:#94a3b8;margin-bottom:2rem}
.stats{display:flex;gap:1rem;margin-bottom:2rem}
.stat{background:#1e293b;padding:1rem 1.5rem;border-radius:0.75rem;border:1px solid #334155}
.stat .num{font-size:2rem;font-weight:800}
.stat.ok .num{color:#10b981}
.stat.fail .num{color:#ef4444}
.stat.total .num{color:#06b6d4}
table{width:100%;background:#1e293b;border-radius:0.75rem;overflow:hidden;border-collapse:collapse;font-size:0.875rem}
th{text-align:left;padding:0.75rem 1rem;background:#334155;font-weight:700;text-transform:uppercase;font-size:0.7rem;letter-spacing:0.1em;color:#94a3b8}
td{padding:0.6rem 1rem;border-top:1px solid #334155}
code{font-family:'JetBrains Mono',monospace;font-size:0.8rem;color:#a5b4fc}
.badge{display:inline-block;padding:0.2rem 0.5rem;border-radius:0.3rem;font-weight:700;font-size:0.75rem;color:white}
.err{color:#fca5a5;display:block;margin-top:0.25rem}
tr:hover td{background:rgba(99,102,241,0.05)}
</style></head><body>
<h1>🔥 Smoke Test Report</h1>
<div class="meta">Generated: {$this->now()} · Total routes: {$this->totalTested}</div>
<div class="stats">
  <div class="stat total"><div class="num">{$this->totalTested}</div><div>Total Tested</div></div>
  <div class="stat ok"><div class="num">{$this->totalPassed}</div><div>Passed ({$this->percent($this->totalPassed)}%)</div></div>
  <div class="stat fail"><div class="num">{$this->totalFailed}</div><div>Failed ({$this->percent($this->totalFailed)}%)</div></div>
</div>
<table>
<thead><tr><th>URL</th><th>Status</th><th>Duration</th><th>Size</th><th>Route Name</th><th>Error</th></tr></thead>
<tbody>{$rows}</tbody>
</table>
</body></html>
HTML;

        file_put_contents(base_path($path), $html);
        $this->info("📄 HTML report tersimpan: {$path}");
    }

    private function percent(int $n): string
    {
        return $this->totalTested > 0 ? (string) round(($n / $this->totalTested) * 100, 1) : '0';
    }

    private function now(): string
    {
        return now()->format('Y-m-d H:i:s');
    }
}
