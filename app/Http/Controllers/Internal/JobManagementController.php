<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Field;
use App\Models\Job;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class JobManagementController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['period', 'field'])
            ->withCount([
                'applications as accepted_applications_count' => function ($query) {
                    $query->where('status', Application::STATUS_DITERIMA);
                },
            ])
            ->latest()
            ->paginate(10);

        $totalJobs = Job::count();
        $openJobs = Job::where('status', 'open')->count();
        $totalQuota = Job::sum('quota');

        return view('internal.jobs.index', compact(
            'jobs',
            'totalJobs',
            'openJobs',
            'totalQuota'
        ));
    }

    public function create()
    {
        return view('internal.jobs.form', [
            'job' => null,
            'periods' => Period::orderByDesc('start_date')->get(),
            'fields' => Field::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedJob($request);

        $job = DB::transaction(function () use ($data, $request) {
            $period = Period::whereKey($data['period_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensurePeriodQuota($period, (int) $data['quota']);
            $data['published_at'] = $data['status'] === 'open' ? now() : null;
            $job = Job::create($data);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'job.created',
                'model_type' => Job::class,
                'model_id' => $job->id,
                'new_values' => $this->jobValues($job),
                'ip_address' => $request->ip(),
            ]);

            return $job;
        });

        return redirect()
            ->route('internal.jobs.index')
            ->with('success', 'Lowongan berhasil dibuat.');
    }

    public function edit(Job $job)
    {
        return view('internal.jobs.form', [
            'job' => $job,
            'periods' => Period::orderByDesc('start_date')->get(),
            'fields' => Field::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Job $job)
    {
        $data = $this->validatedJob($request, $job);

        DB::transaction(function () use ($data, $job, $request) {
            $periodIds = collect([$job->period_id, (int) $data['period_id']])
                ->unique()
                ->sort()
                ->values();
            $periods = Period::whereIn('id', $periodIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($periods->count() !== $periodIds->count()) {
                throw ValidationException::withMessages([
                    'period_id' => 'Periode tidak ditemukan. Muat ulang halaman lalu coba lagi.',
                ]);
            }

            $lockedJob = Job::whereKey($job->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (! $periodIds->contains($lockedJob->period_id)) {
                throw ValidationException::withMessages([
                    'period_id' => 'Periode lowongan berubah. Muat ulang halaman lalu coba lagi.',
                ]);
            }

            $acceptedCount = Application::where('job_id', $lockedJob->id)
                ->where('status', Application::STATUS_DITERIMA)
                ->lockForUpdate()
                ->get(['id'])
                ->count();

            if ($data['quota'] < $acceptedCount) {
                throw ValidationException::withMessages([
                    'quota' => 'Kuota lowongan tidak boleh lebih kecil dari jumlah peserta yang sudah diterima.',
                ]);
            }

            $targetPeriod = $periods->get((int) $data['period_id']);
            $this->ensurePeriodQuota($targetPeriod, (int) $data['quota'], $lockedJob->id);

            $oldValues = $this->jobValues($lockedJob);
            $data['published_at'] = $data['status'] === 'open'
                ? ($lockedJob->published_at ?: now())
                : $lockedJob->published_at;
            $lockedJob->update($data);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'job.updated',
                'model_type' => Job::class,
                'model_id' => $lockedJob->id,
                'old_values' => $oldValues,
                'new_values' => $this->jobValues($lockedJob),
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('internal.jobs.index')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    private function validatedJob(Request $request, ?Job $job = null): array
    {
        $slugRule = Rule::unique('jobs', 'slug');
        if ($job) {
            $slugRule->ignore($job->id);
        }

        $data = $request->validate([
            'period_id' => ['required', 'integer', 'exists:periods,id'],
            'field_id' => ['required', 'integer', 'exists:fields,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:255', $slugRule],
            'type' => ['required', 'string', 'max:20'],
            'duration' => ['required', 'string', 'max:50'],
            'quota' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'description' => ['required', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'jurusan' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'open', 'closed'])],
        ]);

        return $data;
    }

    private function ensurePeriodQuota(Period $period, int $quota, ?int $exceptJobId = null): void
    {
        $allocatedQuota = Job::where('period_id', $period->id)
            ->when($exceptJobId, function ($query) use ($exceptJobId) {
                $query->where('id', '<>', $exceptJobId);
            })
            ->sum('quota');

        if ($allocatedQuota + $quota > $period->quota) {
            throw ValidationException::withMessages([
                'quota' => 'Total alokasi lowongan tidak boleh melebihi kuota periode.',
            ]);
        }
    }

    private function jobValues(Job $job): array
    {
        return [
            'period_id' => $job->period_id,
            'field_id' => $job->field_id,
            'title' => $job->title,
            'slug' => $job->slug,
            'type' => $job->type,
            'duration' => $job->duration,
            'quota' => $job->quota,
            'description' => $job->description,
            'responsibilities' => $job->responsibilities,
            'requirements' => $job->requirements,
            'jurusan' => $job->jurusan,
            'status' => $job->status,
        ];
    }
}
