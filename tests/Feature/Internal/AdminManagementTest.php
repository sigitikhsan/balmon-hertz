<?php

namespace Tests\Feature\Internal;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsSuperAdmin(): User
    {
        $user = User::factory()->create([
            'email' => 'superadmin@balmon.test',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_SUPERADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->actingAs($user);

        return $user;
    }

    private function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'email' => 'admin@balmon.test',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_superadmin_can_view_admin_list()
    {
        $this->actingAsSuperAdmin();

        User::factory()->create([
            'name' => 'Budi Admin',
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $response = $this->get(route('internal.admins.index'));

        $response->assertOk();
        $response->assertSee('Budi Admin');
    }

    public function test_admin_cannot_view_admin_management()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('internal.admins.index'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_create_admin()
    {
        $this->actingAsSuperAdmin();

        $response = $this->post(route('internal.admins.store'), [
            'name' => 'Admin Baru',
            'email' => 'admin.baru@balmon.test',
            'password' => 'password123',
            'status' => User::STATUS_AKTIF,
        ]);

        $response->assertRedirect(route('internal.admins.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Baru',
            'email' => 'admin.baru@balmon.test',
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->assertCredentials([
            'email' => 'admin.baru@balmon.test',
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.created',
        ]);
    }

    public function test_admin_creation_requires_unique_email_and_strong_password()
    {
        $this->actingAsSuperAdmin();

        User::factory()->create([
            'email' => 'duplikat@balmon.test',
            'role' => User::ROLE_ADMIN,
        ]);

        $this->post(route('internal.admins.store'), [
            'name' => 'Admin Duplikat',
            'email' => 'duplikat@balmon.test',
            'password' => 'short',
            'status' => User::STATUS_AKTIF,
        ])->assertSessionHasErrors(['email', 'password']);
    }

    public function test_superadmin_can_update_admin()
    {
        $this->actingAsSuperAdmin();

        $admin = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@balmon.test',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $response = $this->put(route('internal.admins.update', $admin), [
            'name' => 'Nama Baru',
            'email' => 'baru@balmon.test',
            'password' => 'passwordbaru123',
            'status' => User::STATUS_AKTIF,
        ]);

        $response->assertRedirect(route('internal.admins.index'));

        $admin->refresh();

        $this->assertSame('Nama Baru', $admin->name);
        $this->assertSame('baru@balmon.test', $admin->email);
        $this->assertTrue(password_verify('passwordbaru123', $admin->password));
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.updated',
            'model_id' => $admin->id,
        ]);
    }

    public function test_superadmin_can_deactivate_and_reactivate_admin()
    {
        $this->actingAsSuperAdmin();

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->patch(route('internal.admins.status', $admin), [
            'status' => User::STATUS_NONAKTIF,
        ])->assertRedirect(route('internal.admins.index'));

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'status' => User::STATUS_NONAKTIF,
        ]);

        $this->patch(route('internal.admins.status', $admin), [
            'status' => User::STATUS_AKTIF,
        ])->assertRedirect(route('internal.admins.index'));

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.status_updated',
            'model_id' => $admin->id,
        ]);
    }

    public function test_admin_cannot_access_create_admin_form()
    {
        $this->actingAsAdmin();

        $this->get(route('internal.admins.create'))->assertStatus(403);
    }

    public function test_superadmin_cannot_manage_another_superadmin()
    {
        $this->actingAsSuperAdmin();

        $otherSuperAdmin = User::factory()->create([
            'role' => User::ROLE_SUPERADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->patch(route('internal.admins.status', $otherSuperAdmin), [
            'status' => User::STATUS_NONAKTIF,
        ])->assertStatus(404);
    }

    public function test_creating_admin_records_audit_with_current_user()
    {
        $superAdmin = $this->actingAsSuperAdmin();

        $this->post(route('internal.admins.store'), [
            'name' => 'Audit Admin',
            'email' => 'audit@balmon.test',
            'password' => 'password123',
            'status' => User::STATUS_AKTIF,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.created',
            'user_id' => $superAdmin->id,
        ]);
    }
}
