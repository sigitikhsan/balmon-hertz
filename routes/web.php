<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Internal\DashboardController;
use App\Http\Controllers\Internal\LoginController;
use App\Http\Controllers\JobController;
use App\Models\Job;
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
    $latestInternships = Job::with('field')
        ->open()
        ->latest('published_at')
        ->take(4)
        ->get();

    return view('welcome', compact('latestInternships'));
});

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/apply/{job:slug}', [ApplicationController::class, 'create'])->name('apply.create');
Route::post('/apply/{job:slug}', [ApplicationController::class, 'store'])->middleware('throttle:10,1')->name('apply.store');
Route::get('/status', [ApplicationController::class, 'statusForm'])->name('status.form');
Route::get('/status/result', [ApplicationController::class, 'statusResult'])->name('status.result');

// Internal admin area (akses-internal) – hanya admin & superadmin
Route::prefix('akses-internal')->group(function () {
    // Login form
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('internal.login');
    // Process login with throttle (5 attempts / minute) – middleware defined in controller
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('internal.login.post');
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('internal.logout');

    // Protected dashboard – hanya admin & superadmin
    Route::middleware(['internal.auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('internal.dashboard');
    });
});
