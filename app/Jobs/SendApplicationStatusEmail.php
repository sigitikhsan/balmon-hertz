<?php

namespace App\Jobs;

use App\Mail\ApplicationStatusNotification;
use App\Models\Application;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class SendApplicationStatusEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    private $emailLogId;

    public function __construct(int $emailLogId)
    {
        $this->emailLogId = $emailLogId;
    }

    public function backoff()
    {
        return [60, 300];
    }

    public function handle()
    {
        $emailLog = EmailLog::with(['application', 'statusHistory'])
            ->findOrFail($this->emailLogId);

        if ($emailLog->status !== 'pending') {
            return;
        }

        $application = $emailLog->application;
        $history = $emailLog->statusHistory;

        if (! $application || ! $history) {
            throw new RuntimeException('Email lamaran tidak memiliki data status yang valid.');
        }

        $latestHistoryId = $application->histories()->latest('id')->value('id');
        if ((int) $latestHistoryId !== (int) $history->id || $application->status !== $history->to_status) {
            $emailLog->update([
                'status' => 'superseded',
                'error_message' => null,
            ]);

            return;
        }

        $templateKey = $this->templateKey($history->to_status);
        $template = EmailTemplate::where('key', $templateKey)
            ->where('is_active', true)
            ->first();

        if (! $template) {
            throw new RuntimeException('Template email aktif untuk status lamaran tidak ditemukan.');
        }

        $values = [
            ':nama' => $application->nama,
            ':application_no' => $application->application_no,
            ':job_title' => $application->job->title ?? '-',
            ':status' => $this->statusLabel($history->to_status),
            ':note' => $history->note ?? '-',
        ];
        $subject = strtr($template->subject, $values);
        $body = strtr($template->body, $values);

        $emailLog->update([
            'subject' => $subject,
            'body' => $body,
            'attempts' => $emailLog->attempts + 1,
            'error_message' => null,
        ]);

        Mail::to($emailLog->to_email)->send(new ApplicationStatusNotification($subject, $body));

        $emailLog->update([
            'status' => 'sent',
            'sent_at' => now(),
            'error_message' => null,
        ]);
    }

    public function failed(Throwable $exception)
    {
        report($exception);

        EmailLog::whereKey($this->emailLogId)
            ->where('status', 'pending')
            ->update([
                'status' => 'failed',
                'error_message' => 'Pengiriman gagal setelah tiga percobaan.',
            ]);
    }

    private function templateKey(string $status): string
    {
        $keys = [
            Application::STATUS_DITERIMA => EmailTemplate::ACCEPTED,
            Application::STATUS_DITOLAK => EmailTemplate::REJECTED,
            Application::STATUS_PERBAIKAN => EmailTemplate::NEED_REVISION,
        ];

        if (! isset($keys[$status])) {
            throw new RuntimeException('Status lamaran tidak memiliki template email.');
        }

        return $keys[$status];
    }

    private function statusLabel(string $status): string
    {
        $labels = [
            Application::STATUS_DITERIMA => 'diterima',
            Application::STATUS_DITOLAK => 'ditolak',
            Application::STATUS_PERBAIKAN => 'perlu perbaikan',
        ];

        return $labels[$status] ?? $status;
    }
}
