<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Field;
use App\Models\Job;
use App\Models\Period;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationFlowTest extends TestCase
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
            'status' => 'open',
        ], $overrides));
    }

    public function test_landing_page_loads()
    {
        $this->get('/')->assertOk();
    }

    public function test_jobs_index_empty_state()
    {
        $this->get('/jobs')->assertOk()->assertSee('Belum ada lowongan dibuka');
    }

    public function test_jobs_index_lists_open_job()
    {
        $this->seedJob();

        $this->get('/jobs')->assertOk()->assertSee('Asisten Monitoring');
    }

    public function test_apply_form_open_and_closed()
    {
        $open = $this->seedJob();
        $closed = $this->seedJob(['slug' => 'closed-job', 'title' => 'Closed', 'status' => 'closed']);

        $this->get('/apply/' . $open->slug)->assertOk();
        $this->get('/apply/' . $closed->slug)->assertNotFound();
    }

    public function test_store_application_creates_record_and_history()
    {
        Storage::fake('private');
        $job = $this->seedJob();

        $response = $this->post('/apply/' . $job->slug, [
            'nama' => 'Budi Santoso',
            'email' => 'budi@mail.com',
            'no_hp' => '081234567890',
            'ttl_tempat' => 'Jakarta',
            'ttl_tanggal' => '2000-01-01',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
            'surat' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ]);

        $application = Application::where('email', 'budi@mail.com')->first();
        $this->assertNotNull($application);
        $this->assertMatchesRegularExpression('/^MH-\d{4}-\d{4}$/', $application->application_no);
        $this->assertEquals('menunggu', $application->status);
        $this->assertEquals(1, $application->histories()->count());
        $response->assertRedirect(route('status.result', ['no' => $application->application_no]));

        Storage::disk('private')->assertExists($application->cv_path);
        Storage::disk('private')->assertExists($application->surat_path);
    }

    public function test_duplicate_email_rejected()
    {
        Storage::fake('private');
        $job = $this->seedJob();

        $payload = [
            'nama' => 'Budi Santoso',
            'email' => 'budi@mail.com',
            'no_hp' => '081234567890',
            'ttl_tempat' => 'Jakarta',
            'ttl_tanggal' => '2000-01-01',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
            'surat' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ];

        $this->post('/apply/' . $job->slug, $payload)->assertRedirect();
        $this->post('/apply/' . $job->slug, $payload)->assertSessionHasErrors('email');
        $this->assertEquals(1, Application::where('email', 'budi@mail.com')->count());
    }

    public function test_invalid_file_type_rejected()
    {
        Storage::fake('private');
        $job = $this->seedJob();

        $this->post('/apply/' . $job->slug, [
            'nama' => 'Budi Santoso',
            'email' => 'budi@mail.com',
            'no_hp' => '081234567890',
            'ttl_tempat' => 'Jakarta',
            'ttl_tanggal' => '2000-01-01',
            'cv' => UploadedFile::fake()->create('cv.exe', 100, 'application/x-msdownload'),
            'surat' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('cv');

        $this->assertEquals(0, Application::count());
    }

    public function test_status_result_masks_identity()
    {
        Storage::fake('private');
        $job = $this->seedJob();

        $this->post('/apply/' . $job->slug, [
            'nama' => 'Budi Santoso',
            'email' => 'budi@mail.com',
            'no_hp' => '081234567890',
            'ttl_tempat' => 'Jakarta',
            'ttl_tanggal' => '2000-01-01',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
            'surat' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ]);

        $application = Application::first();

        $response = $this->get('/status/result?no=' . $application->application_no);
        $response->assertOk();
        $response->assertSee('B***');
        $response->assertDontSee('Budi Santoso');
        $response->assertDontSee('budi@mail.com');
    }

    public function test_status_result_not_found()
    {
        $this->get('/status/result?no=MH-2026-9999')
            ->assertOk()
            ->assertSee('Data tidak ditemukan');
    }
}
