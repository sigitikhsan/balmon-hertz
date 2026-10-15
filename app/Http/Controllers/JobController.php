<?php

namespace App\Http\Controllers;

use App\Models\Job;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['field', 'period'])
            ->open()
            ->latest('published_at')
            ->paginate(12);

        $types = Job::open()->select('type')->distinct()->pluck('type');

        return view('jobs.index', compact('jobs', 'types'));
    }
}
