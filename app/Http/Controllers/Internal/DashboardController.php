<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jobCount = Job::open()->count();

        return view('internal.dashboard', compact('jobCount'));
    }
}
