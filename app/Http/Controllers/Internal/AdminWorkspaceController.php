<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Job;

class AdminWorkspaceController extends Controller
{
    public function verifications()
    {
        $applications = Application::with('job')
            ->whereIn('status', [
                Application::STATUS_MENUNGGU,
                Application::STATUS_PERBAIKAN,
            ])
            ->latest('submitted_at')
            ->paginate(10);

        $waitingCount = Application::where('status', Application::STATUS_MENUNGGU)->count();
        $revisionCount = Application::where('status', Application::STATUS_PERBAIKAN)->count();

        return view('internal.verifications.index', compact(
            'applications',
            'waitingCount',
            'revisionCount'
        ));
    }

    public function verifyApplication(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $data = $request->validate([
            'status' => ['required', 'in:' . Application::STATUS_DITERIMA . ',' . Application::STATUS_DITOLAK . ',' . Application::STATUS_PERBAIKAN],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $oldStatus = $application->status;

        $application->status = $data['status'];
        $application->rejection_reason = $data['status'] === Application::STATUS_DITOLAK ? $data['note'] : null;
        $application->decided_at = now();
        $application->decided_by = Auth::id();
        $application->save();

        // Create history entry
        $application->histories()->create([
            'from_status' => $oldStatus,
            'to_status' => $application->status,
            'note' => $data['note'] ?? null,
            'changed_by' => Auth::id(),
        ]);

        // Create audit log entry
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'application.status_updated',
            'model_type' => Application::class,
            'model_id' => $application->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $application->status],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('internal.verifications.index')
            ->with('success', 'Status lamaran berhasil diperbarui.');
    }

    public function placements()
    {
        $participants = Application::with(['job.field', 'job.period'])
            ->where('status', Application::STATUS_DITERIMA)
            ->latest('decided_at')
            ->paginate(10);

        return view('internal.placements.index', compact('participants'));
    }

    public function statuses()
    {
        $applications = Application::with('job')
            ->latest('submitted_at')
            ->paginate(10);

        $statusCounts = Application::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('internal.statuses.index', compact('applications', 'statusCounts'));
    }

    public function reports()
    {
        $statusCounts = Application::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $jobStats = Job::withCount('applications')
            ->with(['field', 'period'])
            ->orderBy('title')
            ->get();

        return view('internal.reports.index', compact('statusCounts', 'jobStats'));
    }
}
