<?php

namespace Tests\Feature\Internal;

use App\Models\AuditLog;
use App\Models\Field;
use App\Models\Job;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldManagementTest extends TestCase
{
    use RefreshDatabase;

    private function fieldData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Monitoring',
            'description' => 'Bidang monitoring sistem.',
            'is_active' => '1',
        ], $overrides);
    }

    private function createField(): Field
    {
        return Field::create([
            'name' => 'Monitoring',
            'slug' => 'monitoring',
            'description' => 'Bidang monitoring sistem.',
            'is_active' => true,
        ]);
    }

    private function createJob(Field $field): Job
    {
        $period = Period::create([
            'name' => 'Gelombang 1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => 5,
            'is_active' => true,
        ]);

        return Job::create([
            'period_id' => $period->id,
            'field_id' => $field->id,
            'title' => 'Asisten Monitoring',
            'slug' => 'asisten-monitoring',
            'type' => 'WFO',
            'duration' => '3 Bulan',
            'quota' => 2,
            'description' => 'Deskripsi lowongan.',
            'status' => 'open',
        ]);
    }

    public function test_admin_can_create_and_update_audited_field()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->get(route('internal.fields.create'))
            ->assertOk()
            ->assertSee('Nama Bidang')
            ->assertSee(route('internal.fields.store'))
            ->assertDontSee('name="slug"', false);

        $this->post(route('internal.fields.store'), $this->fieldData())
            ->assertRedirect(route('internal.fields.index'))
            ->assertSessionHas('success');

        $field = Field::firstOrFail();
        $this->assertSame('Monitoring', $field->name);
        $this->assertSame('monitoring', $field->slug);
        $this->assertTrue($field->is_active);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'field.created',
            'model_type' => Field::class,
            'model_id' => $field->id,
        ]);

        $this->put(route('internal.fields.update', $field), $this->fieldData([
            'name' => 'Monitoring Jaringan',
            'description' => 'Pemantauan jaringan.',
        ]))->assertRedirect(route('internal.fields.index'));

        $field->refresh();
        $this->assertSame('Monitoring Jaringan', $field->name);
        $this->assertSame('monitoring', $field->slug);
        $this->assertSame('Pemantauan jaringan.', $field->description);

        $audit = AuditLog::where('action', 'field.updated')
            ->where('model_id', $field->id)
            ->firstOrFail();
        $this->assertSame('Monitoring', $audit->old_values['name']);
        $this->assertSame('Monitoring Jaringan', $audit->new_values['name']);
    }

    public function test_invalid_field_name_preserves_existing_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->createField();
        $this->actingAs($admin);

        $this->post(route('internal.fields.store'), $this->fieldData([
            'name' => '',
        ]))->assertSessionHasErrors(['name']);

        $this->assertSame(1, Field::count());
        $this->assertSame(0, AuditLog::count());
    }

    public function test_duplicate_field_names_receive_unique_internal_slugs()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->post(route('internal.fields.store'), $this->fieldData())
            ->assertRedirect(route('internal.fields.index'));
        $this->post(route('internal.fields.store'), $this->fieldData())
            ->assertRedirect(route('internal.fields.index'));

        $this->assertSame(
            ['monitoring', 'monitoring-2'],
            Field::orderBy('id')->pluck('slug')->all()
        );
    }

    public function test_deactivating_field_preserves_jobs_and_records_audit()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $field = $this->createField();
        $job = $this->createJob($field);
        $this->actingAs($admin);

        $this->patch(route('internal.fields.status', $field), ['is_active' => '0'])
            ->assertRedirect(route('internal.fields.index'))
            ->assertSessionHas('success');

        $this->assertFalse($field->fresh()->is_active);
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'field_id' => $field->id,
            'status' => 'open',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'field.status_updated',
            'model_type' => Field::class,
            'model_id' => $field->id,
        ]);

        $this->patch(route('internal.fields.status', $field), ['is_active' => '1'])
            ->assertRedirect(route('internal.fields.index'));
        $this->assertTrue($field->fresh()->is_active);
        $this->assertSame(2, AuditLog::where('action', 'field.status_updated')->count());
    }

    public function test_field_management_routes_require_internal_authentication()
    {
        $field = $this->createField();

        $this->get(route('internal.fields.create'))
            ->assertRedirect('/akses-internal');
        $this->put(route('internal.fields.update', $field), $this->fieldData())
            ->assertRedirect('/akses-internal');
        $this->patch(route('internal.fields.status', $field), ['is_active' => '0'])
            ->assertRedirect('/akses-internal');
    }
}
