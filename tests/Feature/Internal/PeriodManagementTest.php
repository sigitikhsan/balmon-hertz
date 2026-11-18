<?php

namespace Tests\Feature\Internal;

use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Field;
use App\Models\Job;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeriodManagementTest extends TestCase
{
    use RefreshDatabase;

    private function periodData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Gelombang 1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => 10,
            'is_active' => '1',
        ], $overrides);
    }

    private function createJobWithApplication(Period $period): Application
    {
        $field = Field::create([
            'name' => 'Monitoring',
            'slug' => 'monitoring',
            'is_active' => true,
        ]);
        $job = Job::create([
            'period_id' => $period->id,
            'field_id' => $field->id,
            'title' => 'Asisten Monitoring',
            'slug' => 'asisten-monitoring',
            'type' => 'WFO',
            'duration' => '3 Bulan',
            'quota' => 2,
            'description' => 'Deskripsi.',
            'status' => 'open',
        ]);

        return Application::create([
            'application_no' => 'MH-2026-0001',
            'job_id' => $job->id,
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'no_hp' => '081234567890',
            'ttl_tempat' => 'Jakarta',
            'ttl_tanggal' => '2000-01-01',
            'cv_path' => 'lamaran/cv/cv.pdf',
            'surat_path' => 'lamaran/surat/surat.pdf',
            'status' => Application::STATUS_MENUNGGU,
        ]);
    }

    public function test_admin_can_create_period_and_audit_the_change()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->get(route('internal.periods.create'))
            ->assertOk()
            ->assertSee(route('internal.periods.store'))
            ->assertSee('Kuota Peserta');

        $this->post(route('internal.periods.store'), $this->periodData())
            ->assertRedirect(route('internal.periods.index'))
            ->assertSessionHas('success');

        $period = Period::firstOrFail();
        $this->assertSame('Gelombang 1', $period->name);
        $this->assertEquals(10, $period->quota);
        $this->assertTrue($period->is_active);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'period.created',
            'model_type' => Period::class,
            'model_id' => $period->id,
        ]);
        $this->get(route('internal.periods.index'))
            ->assertOk()
            ->assertSee('Gelombang 1')
            ->assertSee(route('internal.periods.edit', $period));
    }

    public function test_invalid_period_dates_and_negative_quota_are_rejected()
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('internal.periods.store'), $this->periodData([
            'start_date' => '2026-07-01',
            'end_date' => '2026-06-30',
            'quota' => -1,
        ]))->assertSessionHasErrors(['end_date', 'quota']);

        $this->assertSame(0, Period::count());
        $this->assertSame(0, AuditLog::count());
    }

    public function test_admin_can_update_period_details_and_audit_before_and_after_values()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = Period::create([
            'name' => 'Gelombang Lama',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-31',
            'quota' => 8,
            'is_active' => true,
        ]);
        $this->actingAs($admin);

        $this->get(route('internal.periods.edit', $period))
            ->assertOk()
            ->assertSee(route('internal.periods.update', $period))
            ->assertSee('Gelombang Lama');

        $this->put(route('internal.periods.update', $period), $this->periodData([
            'name' => 'Gelombang Baru',
            'quota' => 12,
            'is_active' => '0',
        ]))->assertRedirect(route('internal.periods.index'));

        $period->refresh();
        $this->assertSame('Gelombang Baru', $period->name);
        $this->assertEquals(12, $period->quota);
        $this->assertFalse($period->is_active);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'period.updated',
            'model_type' => Period::class,
            'model_id' => $period->id,
        ]);

        $audit = AuditLog::where('model_type', Period::class)
            ->where('model_id', $period->id)
            ->firstOrFail();
        $this->assertSame('Gelombang Lama', $audit->old_values['name']);
        $this->assertSame('Gelombang Baru', $audit->new_values['name']);
    }

    public function test_period_can_be_deactivated_and_reactivated_without_deleting_applications()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = Period::create([
            'name' => 'Gelombang Aktif',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => 10,
            'is_active' => true,
        ]);
        $application = $this->createJobWithApplication($period);
        $this->actingAs($admin);

        $this->patch(route('internal.periods.status', $period), ['is_active' => '0'])
            ->assertRedirect(route('internal.periods.index'));

        $this->assertFalse($period->fresh()->is_active);
        $this->assertDatabaseHas('applications', ['id' => $application->id]);
        $this->assertDatabaseHas('jobs', ['id' => $application->job_id]);
        $this->assertSame('open', $application->job->fresh()->status);
        $this->get(route('apply.create', $application->job->slug))->assertOk();

        $this->patch(route('internal.periods.status', $period), ['is_active' => '1'])
            ->assertRedirect(route('internal.periods.index'));

        $this->assertTrue($period->fresh()->is_active);
        $this->assertSame(2, AuditLog::where('action', 'period.status_updated')->count());
    }

    public function test_period_management_routes_require_internal_authentication()
    {
        $this->get(route('internal.periods.create'))
            ->assertRedirect('/akses-internal');
    }
}
