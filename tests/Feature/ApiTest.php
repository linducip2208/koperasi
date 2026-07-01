<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
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

    private function createUser(string $email = 'api@test.local'): User
    {
        return User::factory()->create([
            'email'     => $email,
            'password'  => bcrypt('secret123'),
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

    // ─── LOGIN ───

    public function test_login_with_valid_credentials(): void
    {
        $this->createUser();

        $response = $this->postJson('/api/login', [
            'email'    => 'api@test.local',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $this->createUser();

        $response = $this->postJson('/api/login', [
            'email'    => 'api@test.local',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_with_missing_fields(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_with_nonexistent_user(): void
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'ghost@test.local',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    // ─── LOGOUT ───

    public function test_logout_deletes_token(): void
    {
        $user = $this->createUser();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_logout_without_token_fails(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    // ─── AUTHENTICATED ENDPOINTS ───

    public function test_me_endpoint_requires_auth(): void
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }

    public function test_me_endpoint_returns_user_data(): void
    {
        $user = $this->createUser();
        $this->createAnggotaForUser($user);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonPath('data.nama', $user->name);
    }

    public function test_simpanan_endpoint_requires_auth(): void
    {
        $response = $this->getJson('/api/simpanan');

        $response->assertStatus(401);
    }

    public function test_pinjaman_endpoint_requires_auth(): void
    {
        $response = $this->getJson('/api/pinjaman');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_simpanan(): void
    {
        $user = $this->createUser();
        $this->createAnggotaForUser($user);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/simpanan');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_pinjaman(): void
    {
        $user = $this->createUser();
        $this->createAnggotaForUser($user);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/pinjaman');

        $response->assertStatus(200);
    }
}
