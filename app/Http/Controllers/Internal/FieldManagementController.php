<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $fieldsQuery = Field::withCount('jobs')
            ->latest();

        if ($search) {
            $fieldsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($status !== null && $status !== '') {
            $fieldsQuery->where('is_active', $status);
        }

        $fields = $fieldsQuery->paginate(10)
            ->appends($request->query());

        $totalFields = Field::count();
        $activeFields = Field::where('is_active', true)->count();
        $inactiveFields = Field::where('is_active', false)->count();
        $totalJobs = Field::withCount('jobs')->get()->sum('jobs_count');

        return view('internal.fields.index', compact(
            'fields',
            'totalFields',
            'activeFields',
            'inactiveFields',
            'totalJobs',
            'search',
            'status'
        ));
    }
}