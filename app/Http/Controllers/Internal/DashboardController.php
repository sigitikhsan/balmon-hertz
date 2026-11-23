<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\Field;
use App\Models\Period;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jobCount = Job::open()->count();

        $totalApplications = Application::count();
        $acceptedCount = Application::where('status', Application::STATUS_DITERIMA)->count();
        $pendingCount = Application::where('status', Application::STATUS_MENUNGGU)->count();
        $rejectedCount = Application::where('status', Application::STATUS_DITOLAK)->count();

        // Participant status counts from applications table
        $participantStatusCounts = Application::selectRaw('participant_status, count(*) as total')
            ->whereNotNull('participant_status')
            ->groupBy('participant_status')
            ->pluck('total', 'participant_status')
            ->map(fn ($total) => (int) $total);

        $statusCounts = array_merge([
            Application::PARTICIPANT_STATUS_BELUM_MULAI => 0,
            Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG => 0,
            Application::PARTICIPANT_STATUS_SELESAI => 0,
        ], $participantStatusCounts->all());

        $activePeriodCount = Period::where('is_active', true)->count();
        $fieldCount = Field::count();

        $latestApplications = Application::with('job.field', 'job')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('internal.dashboard', compact(
            'jobCount',
            'totalApplications',
            'acceptedCount',
            'pendingCount',
            'rejectedCount',
            'statusCounts',
            'latestApplications',
            'activePeriodCount',
            'fieldCount'
        ));
    }
}