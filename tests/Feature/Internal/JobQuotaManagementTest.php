<?php

namespace Tests\Feature\Internal;

use App\Models\Application;
use App\Models\AuditLog;
use App\Models\EmailLog;
use App\Models\Field;
use App\Models\Job;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobQuotaManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createPeriod(int $quota = 10): Period
    {
        return Period::create([
            'name' => 'Gelombang 1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => $quota,
            'is_active' => true,
        ]);
    }

    private function createField(): Field
    {
        return Field::create([
            'name' => 'Monitoring',
            'slug' => 'monitoring',
            'is_active' => true,
        ]);
    }

    private function createJob(Period $period, Field $field, string $slug, int $quota): Job
    {
        return Job::create([
            'period_id' => $period->id,
            'field_id' => $field->id,
            'title' => 'Lowongan ' . $slug,
            'slug' => $slug,
            'type' => 'WFO',
            'duration' => '3 Bulan',
            'quota' => $quota,
            'description' => 'Deskripsi lowongan.',
            'status' => 'open',
        ]);
    }

    private function createApplicantApplication(Job $job, int $number, string $status = Application::STATUS_MENUNGGU): Application
    {
        return Application::create([
            'application_no' => sprintf('MH-2026-%04d', $number),
            'job_id' => $job->id,
            'nama' => 'Peserta ' . $number,
            'email' => 'peserta' . $number . '@example.test',
            'no_hp' => '081234567890',
            'ttl_tempat' => 'Jakarta',
            'ttl_tanggal' => '2000-01-01',
            'cv_path' => 'lamaran/cv/' . $number . '.pdf',
            'surat_path' => 'lamaran/surat/' . $number . '.pdf',
            'status' => $status,
        ]);
    }

    private function jobData(Period $period, Field $field, array $overrides = []): array
    {
        return array_merge([
            'period_id' => $period->id,
            'field_id' => $field->id,
            'title' => 'Asisten Monitoring',
            'slug' => 'asisten-monitoring',
            'type' => 'WFO',
            'duration' => '3 Bulan',
            'quota' => 3,
            'description' => 'Membantu tim monitoring.',
            'responsibilities' => 'Membuat laporan.',
            'requirements' => 'Teliti.',
            'jurusan' => 'Informatika',
            'status' => 'draft',
        ], $overrides);
    }

    public function test_admin_can_create_and_update_audited_job_without_exceeding_period_quota()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = $this->createPeriod(5);
        $field = $this->createField();
        $this->actingAs($admin);

        $this->get(route('internal.jobs.create'))
            ->assertOk()
            ->assertSee('Kuota Lowongan')
            ->assertSee('Pilih status');

        $this->post(route('internal.jobs.store'), $this->jobData($period, $field, [
            'status' => 'open',
        ]))->assertRedirect(route('internal.jobs.index'));

        $job = Job::firstOrFail();
        $this->assertSame('open', $job->status);
        $this->assertNotNull($job->published_at);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'job.created',
            'model_type' => Job::class,
            'model_id' => $job->id,
        ]);

        $this->put(route('internal.jobs.update', $job), $this->jobData($period, $field, [
            'title' => 'Asisten Monitoring Senior',
            'quota' => 4,
            'status' => 'closed',
        ]))->assertRedirect(route('internal.jobs.index'));

        $job->refresh();
        $this->assertSame('Asisten Monitoring Senior', $job->title);
        $this->assertEquals(4, $job->quota);
        $this->assertSame('closed', $job->status);
        $this->assertNotNull($job->published_at);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'job.updated',
            'model_type' => Job::class,
            'model_id' => $job->id,
        ]);

        $this->get(route('internal.jobs.index'))
            ->assertOk()
            ->assertSee('Asisten Monitoring Senior')
            ->assertSee('Periode & Kuota');
    }

    public function test_job_allocation_cannot_exceed_period_quota()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = $this->createPeriod(5);
        $field = $this->createField();
        $this->createJob($period, $field, 'existing-job', 4);
        $this->actingAs($admin);

        $this->post(route('internal.jobs.store'), $this->jobData($period, $field, [
            'slug' => 'over-allocation',
            'quota' => 2,
        ]))->assertSessionHasErrors('quota');

        $this->assertSame(1, Job::count());
        $this->assertSame(0, AuditLog::where('action', 'job.created')->count());
    }

    public function test_period_quota_cannot_be_reduced_below_allocated_job_quotas()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = $this->createPeriod(5);
        $field = $this->createField();
        $this->createJob($period, $field, 'existing-job', 4);
        $this->actingAs($admin);

        $this->put(route('internal.periods.update', $period), [
            'name' => $period->name,
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => 3,
            'is_active' => '1',
        ])->assertSessionHasErrors('quota');

        $this->assertEquals(5, $period->fresh()->quota);
        $this->assertSame(0, AuditLog::where('action', 'period.updated')->count());
    }

    public function test_job_quota_cannot_be_reduced_below_already_accepted_participants()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = $this->createPeriod(5);
        $field = $this->createField();
        $job = $this->createJob($period, $field, 'accepted-job', 3);
        $this->createApplicantApplication($job, 1, Application::STATUS_DITERIMA);
        $this->actingAs($admin);

        $this->put(route('internal.jobs.update', $job), $this->jobData($period, $field, [
            'slug' => $job->slug,
            'quota' => 0,
        ]))->assertSessionHasErrors('quota');

        $this->assertEquals(3, $job->fresh()->quota);
    }

    public function test_application_cannot_be_accepted_after_job_quota_is_full()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = $this->createPeriod(5);
        $field = $this->createField();
        $job = $this->createJob($period, $field, 'full-job', 1);
        $this->createApplicantApplication($job, 1, Application::STATUS_DITERIMA);
        $pending = $this->createApplicantApplication($job, 2);
        $this->actingAs($admin);

        $this->post(route('internal.applications.status', $pending), [
            'status' => Application::STATUS_DITERIMA,
        ])->assertSessionHasErrors('status');

        $this->assertSame(Application::STATUS_MENUNGGU, $pending->fresh()->status);
        $this->assertSame(0, $pending->histories()->count());
        $this->assertSame(0, EmailLog::count());
    }

    public function test_application_cannot_be_accepted_after_period_quota_is_full()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = $this->createPeriod(1);
        $field = $this->createField();
        $acceptedJob = $this->createJob($period, $field, 'first-job', 1);
        $pendingJob = $this->createJob($period, $field, 'second-job', 1);
        $this->createApplicantApplication($acceptedJob, 1, Application::STATUS_DITERIMA);
        $pending = $this->createApplicantApplication($pendingJob, 2);
        $this->actingAs($admin);

        $this->post(route('internal.applications.status', $pending), [
            'status' => Application::STATUS_DITERIMA,
        ])->assertSessionHasErrors('status');

        $this->assertSame(Application::STATUS_MENUNGGU, $pending->fresh()->status);
        $this->assertSame(0, $pending->histories()->count());
        $this->assertSame(0, EmailLog::count());
    }
}
