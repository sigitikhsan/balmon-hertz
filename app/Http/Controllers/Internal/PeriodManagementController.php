<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Period;

class PeriodManagementController extends Controller
{
    public function index()
    {
        $periods = Period::with([
            'jobs.applications' => function ($query) {
                $query->where('status', 'diterima');
            },
        ])
            ->withCount('jobs')
            ->latest('start_date')
            ->paginate(10);

        $totalPeriods = Period::count();

        $activePeriods = Period::where('is_active', true)->count();

        $totalQuota = Period::sum('quota');

        $totalAccepted = Period::whereHas('jobs.applications', function ($query) {
            $query->where('status', 'diterima');
        })->withCount([
            'jobs as accepted_applications_count' => function ($query) {
                $query->join('applications', 'jobs.id', '=', 'applications.job_id')
                    ->where('applications.status', 'diterima');
            },
        ])->get()->sum('accepted_applications_count');

        return view('internal.periods.index', compact(
            'periods',
            'totalPeriods',
            'activePeriods',
            'totalQuota',
            'totalAccepted'
        ));
    }
}