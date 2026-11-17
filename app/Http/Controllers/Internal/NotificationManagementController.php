<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class NotificationManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $logsQuery = EmailLog::query()->with('application')->latest();
        if ($search) {
            $logsQuery->where(function ($query) use ($search) {
                $query->where('to_email', 'like', '%' . $search . '%')->orWhere('subject', 'like', '%' . $search . '%');
            });
        }
        if ($status !== null && $status !== '') {
            $logsQuery->where('status', $status);
        }
        $emailLogs = $logsQuery->paginate(10)->appends($request->query());
        $totalEmails = EmailLog::count();
        $sentEmails = EmailLog::where('status', 'sent')->count();
        $failedEmails = EmailLog::where('status', 'failed')->count();
        $pendingEmails = EmailLog::where('status', 'pending')->count();
        $templates = EmailTemplate::latest()->get();
        return view('internal.notifications.index', compact('emailLogs',             'templates',             'totalEmails',             'sentEmails',             'failedEmails',             'pendingEmails',             'search',             'status'));
    }
}
