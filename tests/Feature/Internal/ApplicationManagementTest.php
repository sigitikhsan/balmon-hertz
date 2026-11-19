<?php

namespace Tests\Feature\Internal;

use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Field;
use App\Models\Job;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationManagementTest extends TestCase
{
    use RefreshDatabase;

    private function buildApplication(string $status = Application::STATUS_MENUNGGU): Application
    {
        $period = Period::create([
            'name' => 'Gelombang 1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => 10,
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
            'status' => $status,
        ]);
    }

    public function test_admin_can_review_application_and_update_status_with_history_and_audit_log()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $application = $this->buildApplication();

        $this->actingAs($admin)
            ->get(route('internal.applications.show', $application))
            ->assertOk()
            ->assertSee('Dokumen Lamaran')
            ->assertSee(route('internal.applications.documents.view', [$application, 'cv']));

        $this->post(route('internal.applications.status', $application), [
            'status' => Application::STATUS_PERBAIKAN,
            'note' => 'Mohon unggah dokumen yang lebih jelas.',
        ])->assertRedirect(route('internal.applications.show', $application));

        $application->refresh();
        $this->assertSame(Application::STATUS_PERBAIKAN, $application->status);
        $this->assertNull($application->decided_by);
        $this->assertSame('Mohon unggah dokumen yang lebih jelas.', $application->histories()->first()->note);
        $this->assertSame('menunggu', $application->histories()->first()->from_status);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'application.status_updated',
            'model_type' => Application::class,
            'model_id' => $application->id,
        ]);

        $this->post(route('internal.applications.status', $application), [
            'status' => Application::STATUS_DITERIMA,
        ])->assertRedirect(route('internal.applications.show', $application));

        $this->assertSame(Application::STATUS_DITERIMA, $application->fresh()->status);
        $this->assertSame(
            Application::PARTICIPANT_STATUS_BELUM_MULAI,
            $application->fresh()->participant_status
        );
        $this->assertEquals($admin->id, $application->fresh()->decided_by);
        $this->assertSame(2, $application->histories()->count());
        $this->assertSame(2, AuditLog::where('model_id', $application->id)->count());
    }

    public function test_rejection_requires_a_reason_and_does_not_change_the_application()
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $application = $this->buildApplication();

        $this->post(route('internal.applications.status', $application), [
            'status' => Application::STATUS_DITOLAK,
        ])->assertSessionHasErrors('note');

        $this->assertSame(Application::STATUS_MENUNGGU, $application->fresh()->status);
        $this->assertSame(0, $application->histories()->count());
        $this->assertSame(0, AuditLog::count());
    }

    public function test_rejection_reason_is_saved_with_the_final_decision()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $application = $this->buildApplication();
        $this->actingAs($admin);

        $this->post(route('internal.applications.status', $application), [
            'status' => Application::STATUS_DITOLAK,
            'note' => 'Dokumen tidak memenuhi persyaratan.',
        ])->assertRedirect(route('internal.applications.show', $application));

        $application->refresh();
        $this->assertSame(Application::STATUS_DITOLAK, $application->status);
        $this->assertSame('Dokumen tidak memenuhi persyaratan.', $application->rejection_reason);
        $this->assertEquals($admin->id, $application->decided_by);
        $this->assertSame('Dokumen tidak memenuhi persyaratan.', $application->histories()->first()->note);
    }

    public function test_terminal_application_status_cannot_be_changed()
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $application = $this->buildApplication(Application::STATUS_DITERIMA);

        $this->post(route('internal.applications.status', $application), [
            'status' => Application::STATUS_DITOLAK,
            'note' => 'Perubahan yang tidak diperbolehkan.',
        ])->assertSessionHasErrors('status');

        $this->assertSame(Application::STATUS_DITERIMA, $application->fresh()->status);
        $this->assertSame(0, $application->histories()->count());
        $this->assertSame(0, AuditLog::count());
    }

    public function test_admin_can_view_and_download_private_application_documents()
    {
        Storage::fake('private');
        Storage::disk('private')->put('lamaran/cv/cv.pdf', 'cv contents');
        Storage::disk('private')->put('lamaran/surat/surat.pdf', 'letter contents');
        $application = $this->buildApplication();
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $viewResponse = $this->get(route('internal.applications.documents.view', [$application, 'cv']))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringContainsString('inline', $viewResponse->headers->get('Content-Disposition'));

        $downloadResponse = $this->get(route('internal.applications.documents.download', [$application, 'surat']))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringContainsString('attachment', $downloadResponse->headers->get('Content-Disposition'));
    }

    public function test_guests_cannot_access_private_application_documents()
    {
        $application = $this->buildApplication();

        $this->get(route('internal.applications.documents.view', [$application, 'cv']))
            ->assertRedirect('/akses-internal');
    }

    public function test_unknown_application_document_is_not_found()
    {
        $application = $this->buildApplication();
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('internal.applications.documents.view', [$application, 'passport']))
            ->assertNotFound();
    }
}
