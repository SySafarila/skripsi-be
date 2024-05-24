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

    public function test_login_screen_can_be_rendered(): void
    {
        // $this->seed();
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_using_the_login_screen(): void
    {
        // $this->seed();
        // $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => 'super.admin@admin.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // $response->assertRedirect(route('dashboard'));
    }

    public function test_dosen_can_authenticate_using_the_login_screen(): void
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

    public function test_tendik_can_authenticate_using_the_login_screen(): void
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

    public function test_mahasiswa_can_authenticate_using_the_login_screen(): void
    {
        // $this->seed();
        // $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => '207',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        // $this->seed();
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
