<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $action = $request->input('action');

        $logsQuery = AuditLog::with('user')
            ->latest();

        if ($search) {
            $logsQuery->where(function ($query) use ($search) {
                $query->where('action', 'like', '%' . $search . '%')
                    ->orWhere('model_type', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%');
            });
        }

        if ($action) {
            $logsQuery->where('action', $action);
        }

        $auditLogs = $logsQuery->paginate(10)
            ->appends($request->query());

        $totalActivities = AuditLog::count();
        $todayActivities = AuditLog::whereDate('created_at', today())->count();
        $totalUsers = AuditLog::distinct('user_id')
            ->whereNotNull('user_id')
            ->count('user_id');

        $actions = AuditLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('internal.audit-logs.index', compact(
            'auditLogs',
            'totalActivities',
            'todayActivities',
            'totalUsers',
            'actions',
            'search',
            'action'
        ));
    }
}