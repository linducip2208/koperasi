<?php

namespace Tests\Unit;

use App\Services\Seo\IndexNowService;
use Tests\TestCase;

class IndexNowTest extends TestCase
{
    public function test_generate_key_returns_hex_string(): void
    {
        $key = IndexNowService::generateKey();
        $this->assertIsString($key);
        $this->assertSame(64, strlen($key));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $key);
    }

    public function test_key_file_is_created(): void
    {
        $key = IndexNowService::generateKey();

        $content = file_get_contents(public_path('indexnow-key.txt'));
        $this->assertIsString($content);
    }
}
