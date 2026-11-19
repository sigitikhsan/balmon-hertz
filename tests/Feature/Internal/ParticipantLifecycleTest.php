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

class ParticipantLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private function createParticipantApplication(string $status, ?string $participantStatus = null): Application
    {
        $period = Period::create([
            'name' => 'Gelombang 1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => 2,
            'is_active' => true,
        ]);
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
            'description' => 'Deskripsi lowongan.',
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
            'status' => $status,
            'participant_status' => $participantStatus,
        ]);
    }

    public function test_admin_can_progress_participant_through_the_linear_lifecycle()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $application = $this->createParticipantApplication(
            Application::STATUS_DITERIMA,
            Application::PARTICIPANT_STATUS_BELUM_MULAI
        );
        $this->actingAs($admin);

        $this->get(route('internal.participants.index'))
            ->assertOk()
            ->assertSee('Belum Mulai')
            ->assertSee(route('internal.participants.status', $application), false);

        $this->patch(route('internal.participants.status', $application), [
            'participant_status' => Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG,
        ])->assertRedirect(route('internal.participants.index'))
            ->assertSessionHas('success');

        $application->refresh();
        $this->assertSame(Application::STATUS_DITERIMA, $application->status);
        $this->assertSame(
            Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG,
            $application->participant_status
        );

        $this->patch(route('internal.participants.status', $application), [
            'participant_status' => Application::PARTICIPANT_STATUS_SELESAI,
        ])->assertRedirect(route('internal.participants.index'));

        $this->assertSame(
            Application::PARTICIPANT_STATUS_SELESAI,
            $application->fresh()->participant_status
        );
        $this->assertSame(2, AuditLog::where('action', 'participant.status_updated')->count());
        $this->assertSame(
            Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG,
            AuditLog::where('action', 'participant.status_updated')->latest('id')->first()->old_values['participant_status']
        );
    }

    public function test_admin_cannot_skip_a_participant_lifecycle_step()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $application = $this->createParticipantApplication(
            Application::STATUS_DITERIMA,
            Application::PARTICIPANT_STATUS_BELUM_MULAI
        );
        $this->actingAs($admin);

        $this->patch(route('internal.participants.status', $application), [
            'participant_status' => Application::PARTICIPANT_STATUS_SELESAI,
        ])->assertSessionHasErrors('participant_status');

        $this->assertSame(
            Application::PARTICIPANT_STATUS_BELUM_MULAI,
            $application->fresh()->participant_status
        );
        $this->assertSame(0, AuditLog::where('action', 'participant.status_updated')->count());
    }

    public function test_only_accepted_applications_can_have_participant_status_updated()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $application = $this->createParticipantApplication(Application::STATUS_MENUNGGU);
        $this->actingAs($admin);

        $this->patch(route('internal.participants.status', $application), [
            'participant_status' => Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG,
        ])->assertSessionHasErrors('participant_status');

        $this->assertSame(Application::STATUS_MENUNGGU, $application->fresh()->status);
        $this->assertNull($application->fresh()->participant_status);
        $this->assertSame(0, AuditLog::where('action', 'participant.status_updated')->count());
    }

    public function test_completed_participant_still_uses_the_accepted_quota()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $period = Period::create([
            'name' => 'Gelombang 1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => 1,
            'is_active' => true,
        ]);
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
            'quota' => 1,
            'description' => 'Deskripsi lowongan.',
            'status' => 'open',
        ]);
        $accepted = Application::create([
            'application_no' => 'MH-2026-0001',
            'job_id' => $job->id,
            'nama' => 'Peserta Selesai',
            'email' => 'selesai@example.test',
            'no_hp' => '081234567890',
            'ttl_tempat' => 'Jakarta',
            'ttl_tanggal' => '2000-01-01',
            'cv_path' => 'lamaran/cv/selesai.pdf',
            'surat_path' => 'lamaran/surat/selesai.pdf',
            'status' => Application::STATUS_DITERIMA,
            'participant_status' => Application::PARTICIPANT_STATUS_SELESAI,
        ]);
        $pending = Application::create([
            'application_no' => 'MH-2026-0002',
            'job_id' => $job->id,
            'nama' => 'Peserta Baru',
            'email' => 'baru@example.test',
            'no_hp' => '081234567890',
            'ttl_tempat' => 'Jakarta',
            'ttl_tanggal' => '2000-01-01',
            'cv_path' => 'lamaran/cv/baru.pdf',
            'surat_path' => 'lamaran/surat/baru.pdf',
            'status' => Application::STATUS_MENUNGGU,
        ]);
        $this->actingAs($admin);

        $this->post(route('internal.applications.status', $pending), [
            'status' => Application::STATUS_DITERIMA,
        ])->assertSessionHasErrors('status');

        $this->assertSame(Application::STATUS_DITERIMA, $accepted->fresh()->status);
        $this->assertSame(Application::STATUS_MENUNGGU, $pending->fresh()->status);
        $this->assertSame(1, Application::where('job_id', $job->id)
            ->where('status', Application::STATUS_DITERIMA)
            ->count());
    }

    public function test_guests_cannot_update_participant_lifecycle_status()
    {
        $application = $this->createParticipantApplication(
            Application::STATUS_DITERIMA,
            Application::PARTICIPANT_STATUS_BELUM_MULAI
        );

        $this->patch(route('internal.participants.status', $application), [
            'participant_status' => Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG,
        ])->assertRedirect('/akses-internal');
    }
}
