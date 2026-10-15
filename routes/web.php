<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // TODO: ganti dengan data real 4 job/divisi Balmon (Batch 0.1). Nilai di bawah placeholder internal satu instansi.
    $latestInternships = collect([
        ['company_name' => 'Balmon Hertz', 'location' => 'Kantor Balmon', 'title' => 'Asisten Monitoring Spektrum', 'type' => 'WFO', 'duration' => '3 Bulan'],
        ['company_name' => 'Balmon Hertz', 'location' => 'Kantor Balmon', 'title' => 'Asisten Teknik Pengukuran', 'type' => 'WFO', 'duration' => '3 Bulan'],
        ['company_name' => 'Balmon Hertz', 'location' => 'Kantor Balmon', 'title' => 'Asisten Administrasi', 'type' => 'WFO', 'duration' => '3 Bulan'],
        ['company_name' => 'Balmon Hertz', 'location' => 'Kantor Balmon', 'title' => 'Asisten Layanan Informasi', 'type' => 'WFO', 'duration' => '3 Bulan'],
    ])->map(fn ($item) => (object) $item);

    return view('welcome', compact('latestInternships'));
});

Route::get('/login', fn () => 'Halaman login (placeholder)')->name('login');
Route::get('/register', fn () => 'Halaman register (placeholder)')->name('register');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/apply/{job:slug}', [ApplicationController::class, 'create'])->name('apply.create');
Route::post('/apply/{job:slug}', [ApplicationController::class, 'store'])->middleware('throttle:10,1')->name('apply.store');
Route::get('/status', [ApplicationController::class, 'statusForm'])->name('status.form');
Route::get('/status/result', [ApplicationController::class, 'statusResult'])->name('status.result');
