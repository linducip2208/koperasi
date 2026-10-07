<?php

namespace Tests\Feature;

use App\Http\Controllers\InstallController;
use Tests\TestCase;

class InstallerTest extends TestCase
{
    public function test_requirements_mengembalikan_struktur_benar(): void
    {
        $c = new InstallController;
        $checks = $c->requirements();

        $this->assertNotEmpty($checks);
        foreach ($checks as $check) {
            $this->assertArrayHasKey('label', $check);
            $this->assertArrayHasKey('ok', $check);
            $this->assertIsBool($check['ok']);
        }

        $labels = array_column($checks, 'label');
        $this->assertContains('PHP >= 8.2', $labels);
    }

    public function test_halaman_install_terbuka_tanpa_auth(): void
    {
        // Tanpa lock file, wizard langkah requirements dapat dirender.
        $response = $this->get('/install/requirements');
        $response->assertOk();
    }
}
