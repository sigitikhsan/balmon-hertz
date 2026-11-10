<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Job;
use App\Models\Period;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    private static int $seedCounter = 0;

    private function seedJob(array $overrides = []): Job
    {
        self::$seedCounter++;
        $n = self::$seedCounter;
        $period = Period::create([
            'name' => 'Gelombang 1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'quota' => 10,
            'is_active' => true,
        ]);

        $field = Field::create([
            'name' => 'Monitoring ' . $n,
            'slug' => 'monitoring-' . $n,
            'is_active' => true,
        ]);

        return Job::create(array_merge([
            'period_id' => $period->id,
            'field_id' => $field->id,
            'title' => 'Asisten Monitoring',
            'slug' => 'asisten-monitoring-' . $n,
            'type' => 'WFO',
            'duration' => '3 Bulan',
            'quota' => 2,
            'description' => 'Deskripsi.',
            'responsibilities' => 'Membantu pemantauan spektrum frekuensi harian.',
            'requirements' => 'Fresh graduate atau mahasiswa tingkat akhir.',
            'jurusan' => 'Teknik Elektro, Teknik Telekomunikasi',
            'status' => 'open',
        ], $overrides));
    }

    public function test_landing_page_shows_latest_job_card_with_modal()
    {
        $job = $this->seedJob();

        $response = $this->get('/');
        $response->assertOk();

        // Card & modal dirender
        $response->assertSee('Asisten Monitoring');
        $response->assertSee('job-modal-' . $job->id);

        // Section modal
        $response->assertSee('Job Responsibility');
        $response->assertSee("What We're Looking For", false);
        $response->assertSee('Jurusan');
        $response->assertSee('Teknik Elektro, Teknik Telekomunikasi');

        // Tombol daftar di modal mengarah ke form apply tanpa login
        $response->assertSee(route('apply.create', $job->slug));
    }

    public function test_landing_page_only_shows_published_open_jobs()
    {
        $open = $this->seedJob();
        $draft = $this->seedJob(['slug' => 'draft-job', 'title' => 'Draft Job', 'status' => 'draft']);
        $closed = $this->seedJob(['slug' => 'closed-job', 'title' => 'Closed Job', 'status' => 'closed']);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Asisten Monitoring');
        $response->assertDontSee('Draft Job');
        $response->assertDontSee('Closed Job');
    }

    public function test_hero_has_no_register_link()
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertDontSee('Daftar Sekarang');
        $response->assertSee('Lihat Semua Lowongan');
    }
}
