<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

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
}