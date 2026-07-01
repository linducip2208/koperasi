<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use RefreshDatabase;

    private int $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantId = \App\Models\Tenant::create([
            'nama'       => 'Test Koperasi',
            'email'      => 'test@koperasi.local',
            'status'     => 'aktif',
        ])->id;
    }

    private function createUser(string $email = 'anggota@test.local'): User
    {
        return User::factory()->create([
            'email'     => $email,
            'password'  => bcrypt('secret123'),
            'name'      => 'Anggota Test',
            'tenant_id' => $this->tenantId,
        ]);
    }

    private int $anggotaCounter = 0;

    private function createAnggotaForUser(User $user): Anggota
    {
        $this->anggotaCounter++;
        return Anggota::create([
            'tenant_id'     => $this->tenantId,
            'nomor_anggota' => sprintf('AGT-%03d', $this->anggotaCounter),
            'nama'          => $user->name,
            'email'         => $user->email,
            'user_id'       => $user->id,
            'status'        => 'aktif',
            'tanggal_masuk' => now(),
        ]);
    }

    // ─── PORTAL LOGIN PAGE ───

    public function test_portal_login_page_loads(): void
    {
        $response = $this->get('/portal/login');

        $response->assertStatus(200);
    }

    public function test_portal_login_page_has_login_form(): void
    {
        $response = $this->get('/portal/login');

        $response->assertSee('login', true);
    }

    public function test_portal_dashboard_redirects_to_login_when_guest(): void
    {
        $response = $this->get('/portal');

        $response->assertRedirect();
    }

    // ─── AUTHENTICATED PORTAL ACCESS ───

    public function test_authenticated_user_with_anggota_can_access_dashboard(): void
    {
        $user = $this->createUser();
        $this->createAnggotaForUser($user);

        $response = $this->actingAs($user)->get('/portal');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_with_anggota_can_access_simpanan(): void
    {
        $user = $this->createUser();
        $this->createAnggotaForUser($user);

        $response = $this->actingAs($user)->get('/portal/simpanan');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_with_anggota_can_access_pinjaman(): void
    {
        $user = $this->createUser();
        $this->createAnggotaForUser($user);

        $response = $this->actingAs($user)->get('/portal/pinjaman');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_with_anggota_can_access_profil(): void
    {
        $user = $this->createUser();
        $this->createAnggotaForUser($user);

        $response = $this->actingAs($user)->get('/portal/profil');

        $response->assertStatus(200);
    }

    // ─── DATA ISOLATION ───

    public function test_user_only_sees_own_profil_data(): void
    {
        $user1 = $this->createUser('user1@test.local');
        $user1->update(['name' => 'User Satu']);
        $this->createAnggotaForUser($user1);

        $user2 = $this->createUser('user2@test.local');
        $user2->update(['name' => 'User Dua']);
        $this->createAnggotaForUser($user2);

        $response = $this->actingAs($user1)->get('/portal/profil');

        $response->assertStatus(200)
            ->assertSee('User Satu')
            ->assertDontSee('User Dua');
    }

    // ─── LOGOUT ───

    public function test_logout_requires_csrf_protection(): void
    {
        $user = $this->createUser();
        $this->createAnggotaForUser($user);

        $this->actingAs($user)->post('/portal/logout');

        $this->assertAuthenticated();
    }
}
