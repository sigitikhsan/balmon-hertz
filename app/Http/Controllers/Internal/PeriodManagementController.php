<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Job;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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


    public function create()
    {
        return view('internal.periods.form', ['period' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedPeriod($request);
        $data['is_active'] = (bool) $data['is_active'];

        $period = DB::transaction(function () use ($data, $request) {
            $period = Period::create($data);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'period.created',
                'model_type' => Period::class,
                'model_id' => $period->id,
                'new_values' => $this->periodValues($period),
                'ip_address' => $request->ip(),
            ]);

            return $period;
        });

        return redirect()
            ->route('internal.periods.index')
            ->with('success', 'Periode berhasil dibuat.');
    }

    public function edit(Period $period)
    {
        return view('internal.periods.form', compact('period'));
    }

    public function update(Request $request, Period $period)
    {
        $data = $this->validatedPeriod($request);
        $data['is_active'] = (bool) $data['is_active'];

        DB::transaction(function () use ($data, $period, $request) {
            $lockedPeriod = Period::whereKey($period->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $oldValues = $this->periodValues($lockedPeriod);

            $allocatedQuota = Job::where('period_id', $lockedPeriod->id)->sum('quota');
            $acceptedCount = Application::where('status', Application::STATUS_DITERIMA)
                ->whereHas('job', function ($query) use ($lockedPeriod) {
                    $query->where('period_id', $lockedPeriod->id);
                })
                ->lockForUpdate()
                ->get(['applications.id'])
                ->count();

            if ($data['quota'] < $allocatedQuota || $data['quota'] < $acceptedCount) {
                throw ValidationException::withMessages([
                    'quota' => 'Kuota periode tidak boleh lebih kecil dari alokasi lowongan atau jumlah peserta yang sudah diterima.',
                ]);
            }

            $lockedPeriod->update($data);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'period.updated',
                'model_type' => Period::class,
                'model_id' => $lockedPeriod->id,
                'old_values' => $oldValues,
                'new_values' => $this->periodValues($lockedPeriod),
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('internal.periods.index')
            ->with('success', 'Periode berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Period $period)
    {
        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);
        $isActive = (bool) $data['is_active'];

        DB::transaction(function () use ($isActive, $period, $request) {
            $lockedPeriod = Period::whereKey($period->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $oldValues = ['is_active' => $lockedPeriod->is_active];
            $lockedPeriod->is_active = $isActive;
            $lockedPeriod->save();

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'period.status_updated',
                'model_type' => Period::class,
                'model_id' => $lockedPeriod->id,
                'old_values' => $oldValues,
                'new_values' => ['is_active' => $lockedPeriod->is_active],
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('internal.periods.index')
            ->with('success', $isActive ? 'Periode berhasil diaktifkan.' : 'Periode berhasil dinonaktifkan.');
    }

    private function validatedPeriod(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'quota' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function periodValues(Period $period): array
    {
        return [
            'name' => $period->name,
            'start_date' => $period->start_date->toDateString(),
            'end_date' => $period->end_date->toDateString(),
            'quota' => $period->quota,
            'is_active' => $period->is_active,
        ];
    }
}