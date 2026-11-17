<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
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
