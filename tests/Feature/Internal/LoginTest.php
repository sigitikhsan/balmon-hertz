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
        // Menu khusus superadmin tidak tampil untuk admin biasa
        $dashboardResponse->assertDontSee('Manajemen Akun Admin');
    }

    public function test_admin_sidebar_destinations_are_available()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->actingAs($user);

        $accessibleRoutes = [
            'internal.dashboard',
            'internal.applications.index',
            'internal.verifications.index',
            'internal.participants.index',
            'internal.periods.index',
            'internal.jobs.index',
            'internal.fields.index',
            'internal.placements.index',
            'internal.statuses.index',
            'internal.notifications.index',
            'internal.audit-logs.index',
            'internal.reports.index',
        ];

        $superAdminOnlyRoutes = [
            'internal.admins.index',
            'internal.roles.index',
            'internal.settings.index',
        ];

        $dashboard = $this->get(route('internal.dashboard'))->assertOk();

        foreach ($accessibleRoutes as $routeName) {
            $dashboard->assertSee(route($routeName), false);
            $this->get(route($routeName))->assertOk();
        }

        foreach ($superAdminOnlyRoutes as $routeName) {
            $this->get(route($routeName))->assertStatus(403);
        }
    }

    public function test_superadmin_can_login_and_access_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'superadmin@balmon.test',
            'password' => bcrypt('password123'),
            'role' => 'superadmin',
        ]);

        $response = $this->post('/akses-internal/login', [
            'email' => 'superadmin@balmon.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/akses-internal/dashboard');
        $this->assertAuthenticatedAs($user);

        $dashboardResponse = $this->get('/akses-internal/dashboard');
        $dashboardResponse->assertStatus(200);
        // Menu khusus superadmin tampil di sidebar
        $dashboardResponse->assertSee('Manajemen Akun Admin');
        $dashboardResponse->assertSee('Role & Hak Akses');
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

    public function test_inactive_admin_cannot_login()
    {
        User::factory()->create([
            'email' => 'nonaktif@balmon.test',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_NONAKTIF,
        ]);

        $response = $this->post('/akses-internal/login', [
            'email' => 'nonaktif@balmon.test',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_inactive_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'nonaktif@balmon.test',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_NONAKTIF,
        ]);

        $this->actingAs($user);

        $response = $this->get('/akses-internal/dashboard');

        $response->assertRedirect('/akses-internal');
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
