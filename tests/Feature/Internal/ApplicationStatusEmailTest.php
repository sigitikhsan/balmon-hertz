<?php

namespace Tests\Feature\Internal;

use App\Jobs\SendApplicationStatusEmail;
use App\Mail\ApplicationStatusNotification;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\AuditLog;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Field;
use App\Models\Job;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use RuntimeException;
use Tests\TestCase;

class ApplicationStatusEmailTest extends TestCase
{
    use RefreshDatabase;

    private function buildApplication(): Application
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
            'status' => Application::STATUS_MENUNGGU,
        ]);
    }

    private function addStatusHistory(Application $application, string $status, ?string $note = null): ApplicationStatusHistory
    {
        $application->update(['status' => $status]);

        return $application->histories()->create([
            'from_status' => Application::STATUS_MENUNGGU,
            'to_status' => $status,
            'note' => $note,
        ]);
    }

    private function addTemplate(string $key): EmailTemplate
    {
        return EmailTemplate::create([
            'key' => $key,
            'subject' => 'Lamaran :application_no - :status',
            'body' => "Halo :nama,\n:job_title\n:note",
            'is_active' => true,
        ]);
    }

    private function addEmailLog(Application $application, ApplicationStatusHistory $history, string $status = 'pending'): EmailLog
    {
        return EmailLog::create([
            'application_id' => $application->id,
            'application_status_history_id' => $history->id,
            'to_email' => $application->email,
            'channel' => 'email',
            'subject' => 'Notifikasi status lamaran',
            'body' => '',
            'status' => $status,
            'attempts' => 0,
        ]);
    }

    public function test_revision_note_is_required_and_decision_email_is_queued_after_status_commit()
    {
        Queue::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $application = $this->buildApplication();
        $this->actingAs($admin);

        $this->post(route('internal.applications.status', $application), [
            'status' => Application::STATUS_PERBAIKAN,
        ])->assertSessionHasErrors('note');

        $this->assertSame(Application::STATUS_MENUNGGU, $application->fresh()->status);
        $this->assertSame(0, EmailLog::count());

        $this->post(route('internal.applications.status', $application), [
            'status' => Application::STATUS_PERBAIKAN,
            'note' => 'Mohon unggah dokumen yang terbaca.',
        ])->assertRedirect(route('internal.applications.show', $application));

        $this->assertSame(Application::STATUS_PERBAIKAN, $application->fresh()->status);
        $this->assertDatabaseHas('email_logs', [
            'application_id' => $application->id,
            'to_email' => $application->email,
            'status' => 'pending',
        ]);
        Queue::assertPushed(SendApplicationStatusEmail::class);
    }

    public function test_email_job_renders_revision_template_and_records_successful_delivery()
    {
        Mail::fake();
        $application = $this->buildApplication();
        $history = $this->addStatusHistory(
            $application,
            Application::STATUS_PERBAIKAN,
            'Mohon perbaiki CV.'
        );
        $this->addTemplate(EmailTemplate::NEED_REVISION);
        $emailLog = $this->addEmailLog($application, $history);

        (new SendApplicationStatusEmail($emailLog->id))->handle();

        $emailLog->refresh();
        $this->assertSame('sent', $emailLog->status);
        $this->assertEquals(1, $emailLog->attempts);
        $this->assertNotNull($emailLog->sent_at);
        $this->assertSame('Lamaran MH-2026-0001 - perlu perbaikan', $emailLog->subject);
        $this->assertStringContainsString('Mohon perbaiki CV.', $emailLog->body);

        Mail::assertSent(ApplicationStatusNotification::class, function ($mail) use ($application) {
            return $mail->hasTo($application->email)
                && $mail->emailSubject === 'Lamaran MH-2026-0001 - perlu perbaikan'
                && strpos($mail->body, 'Mohon perbaiki CV.') !== false;
        });
    }

    public function test_rejection_email_includes_the_admin_reason()
    {
        Mail::fake();
        $application = $this->buildApplication();
        $history = $this->addStatusHistory(
            $application,
            Application::STATUS_DITOLAK,
            'Persyaratan jurusan belum terpenuhi.'
        );
        $this->addTemplate(EmailTemplate::REJECTED);
        $emailLog = $this->addEmailLog($application, $history);

        (new SendApplicationStatusEmail($emailLog->id))->handle();

        Mail::assertSent(ApplicationStatusNotification::class, function ($mail) {
            return strpos($mail->body, 'Persyaratan jurusan belum terpenuhi.') !== false;
        });
    }

    public function test_obsolete_status_email_is_not_sent()
    {
        Mail::fake();
        $application = $this->buildApplication();
        $history = $this->addStatusHistory($application, Application::STATUS_DITERIMA);
        $this->addTemplate(EmailTemplate::ACCEPTED);
        $emailLog = $this->addEmailLog($application, $history);

        $application->histories()->create([
            'from_status' => Application::STATUS_DITERIMA,
            'to_status' => Application::STATUS_DITOLAK,
            'note' => 'Status berikutnya.',
        ]);
        $application->update(['status' => Application::STATUS_DITOLAK]);

        (new SendApplicationStatusEmail($emailLog->id))->handle();

        $this->assertSame('superseded', $emailLog->fresh()->status);
        Mail::assertNothingSent();
    }

    public function test_admin_can_retry_failed_email_for_current_application_status()
    {
        Queue::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $application = $this->buildApplication();
        $history = $this->addStatusHistory($application, Application::STATUS_DITERIMA);
        $emailLog = $this->addEmailLog($application, $history, 'failed');
        $emailLog->update([
            'attempts' => 3,
            'error_message' => 'Pengiriman gagal setelah tiga percobaan.',
        ]);
        $this->actingAs($admin);

        $this->post(route('internal.notifications.retry', $emailLog))
            ->assertRedirect(route('internal.notifications.index'));

        $this->assertSame('pending', $emailLog->fresh()->status);
        $this->assertEquals(0, $emailLog->fresh()->attempts);
        $this->assertNull($emailLog->fresh()->error_message);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'email.retry_requested',
            'model_type' => EmailLog::class,
            'model_id' => $emailLog->id,
        ]);
        Queue::assertPushed(SendApplicationStatusEmail::class);
    }

    public function test_admin_can_see_failed_email_and_retry_action()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $application = $this->buildApplication();
        $history = $this->addStatusHistory($application, Application::STATUS_DITERIMA);
        $emailLog = $this->addEmailLog($application, $history, 'failed');
        $this->actingAs($admin);

        $this->get(route('internal.notifications.index'))
            ->assertOk()
            ->assertSee('Kirim ulang')
            ->assertSee(route('internal.notifications.retry', $emailLog));
    }

    public function test_email_job_marks_delivery_failed_after_exhausted_attempts_without_reverting_status()
    {
        $application = $this->buildApplication();
        $history = $this->addStatusHistory($application, Application::STATUS_DITERIMA);
        $this->addTemplate(EmailTemplate::ACCEPTED);
        $emailLog = $this->addEmailLog($application, $history);
        $job = new SendApplicationStatusEmail($emailLog->id);

        $job->failed(new RuntimeException('SMTP unavailable'));

        $this->assertSame('failed', $emailLog->fresh()->status);
        $this->assertSame(Application::STATUS_DITERIMA, $application->fresh()->status);
        $this->assertSame('Pengiriman gagal setelah tiga percobaan.', $emailLog->fresh()->error_message);
    }
}
