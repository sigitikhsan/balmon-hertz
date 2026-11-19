<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\EmailLog;
use App\Models\Job;
use App\Models\Period;
use App\Jobs\SendApplicationStatusEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApplicationManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $applicationsQuery = Application::with('job')
            ->latest('submitted_at');

        if ($search) {
            $applicationsQuery->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('application_no', 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $applicationsQuery->where('status', $status);
        }

        $applications = $applicationsQuery->paginate(10)->appends($request->query());

        $totalApplications = Application::count();

        $waitingApplications = Application::where('status', 'menunggu')->count();

        $acceptedApplications = Application::where('status', 'diterima')->count();

        $rejectedApplications = Application::where('status', 'ditolak')->count();

        return view('internal.applications.index', compact(
            'applications',
            'totalApplications',
            'waitingApplications',
            'acceptedApplications',
            'rejectedApplications',
            'search',
            'status'
        ));
    }

    public function show(Application $application)
    {
        $application->load('job.field');
        $histories = $application->histories()
            ->with('changer')
            ->latest()
            ->limit(20)
            ->get();

        return view('internal.applications.show', [
            'application' => $application,
            'histories' => $histories,
            'allowedStatuses' => $this->allowedTransitions($application->status),
        ]);
    }

    public function updateStatus(Request $request, Application $application)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                Application::STATUS_DITERIMA,
                Application::STATUS_DITOLAK,
                Application::STATUS_PERBAIKAN,
            ])],
            'note' => ['nullable', 'string', 'max:2000', 'required_if:status,ditolak,perlu_perbaikan'],
        ]);

        $periodId = $data['status'] === Application::STATUS_DITERIMA
            ? Job::whereKey($application->job_id)->value('period_id')
            : null;

        $emailLogId = DB::transaction(function () use ($data, $application, $request, $periodId) {
            $lockedApplication = Application::whereKey($application->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $oldStatus = $lockedApplication->status;

            if (! in_array($data['status'], $this->allowedTransitions($oldStatus), true)) {
                throw ValidationException::withMessages([
                    'status' => 'Transisi status lamaran tersebut tidak diperbolehkan.',
                ]);
            }

            if ($data['status'] === Application::STATUS_DITERIMA) {
                $period = Period::whereKey($periodId)
                    ->lockForUpdate()
                    ->firstOrFail();
                $job = Job::whereKey($lockedApplication->job_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $job->period_id !== (int) $period->id) {
                    throw ValidationException::withMessages([
                        'status' => 'Periode lowongan berubah. Muat ulang halaman lalu coba lagi.',
                    ]);
                }

                $acceptedForJob = Application::where('job_id', $job->id)
                    ->where('status', Application::STATUS_DITERIMA)
                    ->lockForUpdate()
                    ->get(['id'])
                    ->count();

                if ($acceptedForJob >= $job->quota) {
                    throw ValidationException::withMessages([
                        'status' => 'Kuota lowongan sudah terpenuhi; lamaran tidak dapat diterima.',
                    ]);
                }

                $acceptedForPeriod = Application::where('status', Application::STATUS_DITERIMA)
                    ->whereHas('job', function ($query) use ($period) {
                        $query->where('period_id', $period->id);
                    })
                    ->lockForUpdate()
                    ->get(['applications.id'])
                    ->count();

                if ($acceptedForPeriod >= $period->quota) {
                    throw ValidationException::withMessages([
                        'status' => 'Kuota periode sudah terpenuhi; lamaran tidak dapat diterima.',
                    ]);
                }
            }

            $lockedApplication->status = $data['status'];
            if ($data['status'] === Application::STATUS_DITERIMA) {
                $lockedApplication->participant_status = Application::PARTICIPANT_STATUS_BELUM_MULAI;
            }
            $lockedApplication->rejection_reason = $data['status'] === Application::STATUS_DITOLAK
                ? $data['note']
                : null;
            $isFinalStatus = in_array($data['status'], [
                Application::STATUS_DITERIMA,
                Application::STATUS_DITOLAK,
            ], true);
            $lockedApplication->decided_at = $isFinalStatus ? now() : null;
            $lockedApplication->decided_by = $isFinalStatus ? Auth::id() : null;
            $lockedApplication->save();

            $history = $lockedApplication->histories()->create([
                'from_status' => $oldStatus,
                'to_status' => $data['status'],
                'note' => $data['note'] ?? null,
                'changed_by' => Auth::id(),
            ]);

            $emailLog = EmailLog::create([
                'application_id' => $lockedApplication->id,
                'application_status_history_id' => $history->id,
                'to_email' => $lockedApplication->email,
                'channel' => 'email',
                'subject' => 'Notifikasi status lamaran',
                'body' => '',
                'status' => 'pending',
                'attempts' => 0,
            ]);

            $auditNewValues = ['status' => $data['status']];
            if ($data['status'] === Application::STATUS_DITERIMA) {
                $auditNewValues['participant_status'] = $lockedApplication->participant_status;
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'application.status_updated',
                'model_type' => Application::class,
                'model_id' => $lockedApplication->id,
                'old_values' => ['status' => $oldStatus],
                'new_values' => $auditNewValues,
                'ip_address' => $request->ip(),
            ]);

            return $emailLog->id;
        });

        try {
            SendApplicationStatusEmail::dispatch($emailLogId)->onConnection('database');
        } catch (QueryException $exception) {
            EmailLog::whereKey($emailLogId)->update([
                'status' => 'failed',
                'error_message' => 'Email gagal dimasukkan ke antrean pengiriman.',
            ]);

            return redirect()
                ->route('internal.applications.show', $application)
                ->with('warning', 'Status tersimpan, tetapi email gagal masuk antrean. Coba kirim ulang dari menu Notifikasi & Email.');
        }

        return redirect()
            ->route('internal.applications.show', $application)
            ->with('success', 'Status lamaran tersimpan dan email masuk antrean pengiriman.');
    }

    public function viewDocument(Application $application, string $document)
    {
        return $this->documentResponse($application, $document, false);
    }

    public function downloadDocument(Application $application, string $document)
    {
        return $this->documentResponse($application, $document, true);
    }

    private function documentResponse(Application $application, string $document, bool $download)
    {
        $paths = [
            'cv' => $application->cv_path,
            'surat' => $application->surat_path,
        ];

        abort_unless(array_key_exists($document, $paths), 404);

        $path = $paths[$document];
        abort_unless($path && Storage::disk('private')->exists($path), 404);

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $filename = $application->application_no . '-' . $document . '.' . $extension;
        $headers = ['X-Content-Type-Options' => 'nosniff'];
        $disk = Storage::disk('private');

        return $download
            ? $disk->download($path, $filename, $headers)
            : $disk->response($path, $filename, $headers);
    }

    private function allowedTransitions(string $status): array
    {
        if ($status === Application::STATUS_MENUNGGU) {
            return [
                Application::STATUS_DITERIMA,
                Application::STATUS_DITOLAK,
                Application::STATUS_PERBAIKAN,
            ];
        }

        if ($status === Application::STATUS_PERBAIKAN) {
            return [
                Application::STATUS_DITERIMA,
                Application::STATUS_DITOLAK,
            ];
        }

        return [];
    }
}
