<?php

namespace App\Console\Commands;

use App\Services\Seo\IndexNowService;
use Illuminate\Console\Command;

class IndexNowSubmit extends Command
{
    protected $signature = 'seo:indexnow
                            {--all : Submit all sitemap URLs (batched per 5000)}
                            {--new : Submit only new URLs since last run}
                            {--url= : Submit a single URL}';

    protected $description = 'Submit URLs ke IndexNow (Bing, Yandex, Seznam, Naver)';

    public function handle(IndexNowService $service): int
    {
        if ($url = $this->option('url')) {
            $this->info("Mengirim 1 URL ke IndexNow...");
            $results = $service->submitSingle($url);
            $this->table(['Search Engine', 'Status', 'Success'], $this->formatResults($results));
            return self::SUCCESS;
        }

        if ($this->option('new')) {
            $this->info("Membangun daftar URL dan mengirim yang baru...");
            $allUrls = $service->buildAllUrls();
            $results = $service->submitNewOnly($allUrls);
            if (empty($results)) {
                $this->info("Tidak ada URL baru. Semua sudah dikirim sebelumnya.");
                return self::SUCCESS;
            }
            $this->table(['Search Engine', 'Status', 'Success'], $this->formatResults($results));
            return self::SUCCESS;
        }

        $this->info("Mengirim semua URL ke IndexNow (per 5000)...");
        $results = $service->submitAll();
        foreach ($results as $i => $batch) {
            $this->info("Batch " . ($i + 1) . ":");
            $this->table(['Search Engine', 'Status', 'Success'], $this->formatResults($batch));
        }
        return self::SUCCESS;
    }

    protected function formatResults(array $results): array
    {
        return collect($results)->map(fn ($r, $engine) => [
            $engine,
            $r['status'] ?? 'N/A',
            ($r['success'] ?? false) ? '✓' : '✗',
        ])->values()->toArray();
    }
}
