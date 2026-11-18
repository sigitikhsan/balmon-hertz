<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Jobs\SendApplicationStatusEmail;
use App\Models\AuditLog;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NotificationManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $logsQuery = EmailLog::query()->with('application')->latest();

        if ($search) {
            $logsQuery->where(function ($query) use ($search) {
                $query->where('to_email', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%');
            });
        }

        if ($status !== null && $status !== '') {
            $logsQuery->where('status', $status);
        }

        $emailLogs = $logsQuery->paginate(10)->appends($request->query());
        $totalEmails = EmailLog::count();
        $sentEmails = EmailLog::where('status', 'sent')->count();
        $failedEmails = EmailLog::where('status', 'failed')->count();
        $pendingEmails = EmailLog::where('status', 'pending')->count();
        $templates = EmailTemplate::latest()->get();

        return view('internal.notifications.index', compact(
            'emailLogs',
            'templates',
            'totalEmails',
            'sentEmails',
            'failedEmails',
            'pendingEmails',
            'search',
            'status'
        ));
    }

    public function retry(Request $request, EmailLog $emailLog)
    {
        $emailLogId = DB::transaction(function () use ($emailLog, $request) {
            $lockedEmailLog = EmailLog::whereKey($emailLog->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedEmailLog->status !== 'failed') {
                throw ValidationException::withMessages([
                    'email' => 'Hanya email gagal yang dapat dikirim ulang.',
                ]);
            }

            $application = $lockedEmailLog->application;
            $history = $lockedEmailLog->statusHistory;
            $latestHistoryId = $application
                ? $application->histories()->latest('id')->value('id')
                : null;

            if (! $application || ! $history
                || (int) $latestHistoryId !== (int) $history->id
                || $application->status !== $history->to_status) {
                throw ValidationException::withMessages([
                    'email' => 'Status lamaran telah berubah. Email lama tidak dapat dikirim ulang.',
                ]);
            }

            $lockedEmailLog->update([
                'status' => 'pending',
                'attempts' => 0,
                'error_message' => null,
                'sent_at' => null,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'email.retry_requested',
                'model_type' => EmailLog::class,
                'model_id' => $lockedEmailLog->id,
                'old_values' => ['status' => 'failed'],
                'new_values' => ['status' => 'pending'],
                'ip_address' => $request->ip(),
            ]);

            return $lockedEmailLog->id;
        });

        try {
            SendApplicationStatusEmail::dispatch($emailLogId)->onConnection('database');
        } catch (QueryException $exception) {
            EmailLog::whereKey($emailLogId)->update([
                'status' => 'failed',
                'error_message' => 'Email gagal dimasukkan ke antrean pengiriman.',
            ]);

            return redirect()
                ->route('internal.notifications.index')
                ->with('warning', 'Email gagal masuk antrean. Periksa koneksi antrean lalu coba lagi.');
        }

        return redirect()
            ->route('internal.notifications.index')
            ->with('success', 'Email masuk antrean untuk dikirim ulang.');
    }
}
