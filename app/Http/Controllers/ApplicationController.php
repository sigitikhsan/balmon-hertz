<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function create(Job $job)
    {
        abort_if($job->status !== 'open', 404);

        return view('jobs.apply', compact('job'));
    }

    public function store(Request $request, Job $job)
    {
        abort_if($job->status !== 'open', 404);

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:applications,email',
            'no_hp' => 'required|string|max:20',
            'ttl_tempat' => 'required|string|max:255',
            'ttl_tanggal' => 'required|date',
            'cv' => 'required|file|mimes:pdf,docx|max:5120',
            'surat' => 'required|file|mimes:pdf,docx|max:5120',
        ]);

        $cvPath = $request->file('cv')->store('lamaran/cv', 'private');
        $suratPath = $request->file('surat')->store('lamaran/surat', 'private');

        $application = null;

        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                $application = DB::transaction(function () use ($data, $job, $cvPath, $suratPath) {
            $application = Application::create([
                'application_no' => $this->nextApplicationNo(),
                'job_id' => $job->id,
                'nama' => $data['nama'],
                'email' => $data['email'],
                'no_hp' => $data['no_hp'],
                'ttl_tempat' => $data['ttl_tempat'],
                'ttl_tanggal' => $data['ttl_tanggal'],
                'cv_path' => $cvPath,
                'surat_path' => $suratPath,
                'status' => Application::STATUS_MENUNGGU,
            ]);

            $application->histories()->create([
                'from_status' => null,
                'to_status' => Application::STATUS_MENUNGGU,
                'note' => 'Lamaran masuk via landing page',
            ]);

            return $application;
                });
                break;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($attempt === 2) {
                    throw $e;
                }
            }
        }

        return redirect()
            ->route('status.result', ['no' => $application->application_no])
            ->with('success', 'Lamaran terkirim. Simpan nomor lamaran: ' . $application->application_no);
    }

    public function statusForm()
    {
        return view('jobs.status');
    }

    public function statusResult(Request $request)
    {
        $data = $request->validate([
            'no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $application = null;

        if (! empty($data['no'])) {
            $application = Application::with('job.field')
                ->where('application_no', $data['no'])
                ->first();
        } elseif (! empty($data['email'])) {
            $application = Application::with('job.field')
                ->where('email', $data['email'])
                ->first();
        }

        return view('jobs.status-result', compact('application'));
    }

    private function nextApplicationNo(): string
    {
        $year = date('Y');
        $last = Application::where('application_no', 'like', "MH-{$year}-%")
            ->orderByDesc('id')
            ->first();

        $next = $last ? ((int) substr($last->application_no, -4)) + 1 : 1;

        return sprintf('MH-%s-%04d', $year, $next);
    }
}
