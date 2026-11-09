<?php

namespace Tests\Feature\Internal;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered()
    {
        $response = $this->get('/akses-internal');
        $response->assertStatus(200);
    }

    public function test_admin_can_login_and_access_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'admin@balmon.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/akses-internal/login', [
            'email' => 'admin@balmon.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/akses-internal/dashboard');
        $this->assertAuthenticatedAs($user);

        $dashboardResponse = $this->get('/akses-internal/dashboard');
        $dashboardResponse->assertStatus(200);
    }

    public function test_regular_user_cannot_access_internal_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'user@balmon.test',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->post('/akses-internal/login', [
            'email' => 'user@balmon.test',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_rate_limiting_on_login()
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/akses-internal/login', [
                'email' => 'wrong@balmon.test',
                'password' => 'wrongpassword',
            ]);
        }

        $response->assertStatus(429);
    }
}
