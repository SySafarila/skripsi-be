<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_halaman_login_dapat_diakses(): void
    {
        // $this->seed();
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admin_berhasil_login_menggunakan_email(): void
    {
        // $this->seed();
        // $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $response2 = $this->post('/login', [
            'email' => 'super.admin@admin.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // $response->assertRedirect(route('dashboard'));
    }

    public function test_dosen_berhasil_login_menggunakan_nidn(): void
    {
        // $this->seed();
        // $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => '133',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // $response->assertRedirect(route('dashboard'));
    }

    public function test_tendik_berhasil_login_menggunakan_nip(): void
    {
        // $this->seed();
        // $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => '1234',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // $response->assertRedirect(route('dashboard'));
    }

    public function test_mahasiswa_berhasil_login_menggunakan_nim(): void
    {
        // $this->seed();
        // $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => '207200005',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // $response->assertRedirect(route('dashboard'));
    }
}
