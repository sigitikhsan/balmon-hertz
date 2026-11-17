<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

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
}