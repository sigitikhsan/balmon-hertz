<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ParticipantManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $participantsQuery = Application::with([
            'job.field',
            'job.period',
        ])
            ->where('status', Application::STATUS_DITERIMA)
            ->latest('decided_at');

        if ($search) {
            $participantsQuery->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('application_no', 'like', '%' . $search . '%')
                    ->orWhereHas('job', function ($jobQuery) use ($search) {
                        $jobQuery->where('title', 'like', '%' . $search . '%');
                    });
            });
        }

        $participants = $participantsQuery
            ->paginate(10)
            ->appends($request->query());

        $totalParticipants = Application::where(
            'status',
            Application::STATUS_DITERIMA
        )->count();

        return view('internal.participants.index', compact(
            'participants',
            'totalParticipants',
            'search'
        ));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $data = $request->validate([
            'participant_status' => ['required', Rule::in([
                Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG,
                Application::PARTICIPANT_STATUS_SELESAI,
            ])],
        ]);

        DB::transaction(function () use ($data, $application, $request) {
            $lockedApplication = Application::whereKey($application->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedApplication->status !== Application::STATUS_DITERIMA) {
                throw ValidationException::withMessages([
                    'participant_status' => 'Hanya peserta dengan lamaran berstatus diterima yang dapat diperbarui.',
                ]);
            }

            $allowedNextStatuses = [
                Application::PARTICIPANT_STATUS_BELUM_MULAI => Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG,
                Application::PARTICIPANT_STATUS_SEDANG_BERLANGSUNG => Application::PARTICIPANT_STATUS_SELESAI,
            ];
            $currentStatus = $lockedApplication->participant_status;

            if (! isset($allowedNextStatuses[$currentStatus])
                || $data['participant_status'] !== $allowedNextStatuses[$currentStatus]) {
                throw ValidationException::withMessages([
                    'participant_status' => 'Perubahan status peserta harus mengikuti urutan belum mulai, sedang berlangsung, lalu selesai.',
                ]);
            }

            $lockedApplication->participant_status = $data['participant_status'];
            $lockedApplication->save();

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'participant.status_updated',
                'model_type' => Application::class,
                'model_id' => $lockedApplication->id,
                'old_values' => ['participant_status' => $currentStatus],
                'new_values' => ['participant_status' => $lockedApplication->participant_status],
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('internal.participants.index')
            ->with('success', 'Status peserta berhasil diperbarui.');
    }
}
