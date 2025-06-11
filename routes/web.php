<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EvaluationPeriodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SktController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ReportController;

// Authentication Routes
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'can:admin'])->group(function () {
    // Evaluation Periods
    Route::resource('evaluation-periods', EvaluationPeriodController::class);
    
    // Users
    Route::resource('users', UserController::class);
    Route::post('users/{user}/assign-evaluators', [UserController::class, 'assignEvaluators'])
        ->name('users.assign-evaluators');
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/evaluation/{period}', [ReportController::class, 'generateEvaluationReport'])
        ->name('reports.evaluation');
    Route::get('reports/skt/{period}', [ReportController::class, 'generateSktReport'])
        ->name('reports.skt');
    Route::get('reports/individual/{evaluation}', [ReportController::class, 'generateIndividualReport'])
        ->name('reports.individual');
});

// SKT Routes
Route::middleware('auth')->group(function () {
    Route::resource('skt', SktController::class);
    Route::post('skt/{skt}/submit-awal', [SktController::class, 'submitAwal'])->name('skt.submit-awal');
    Route::post('skt/{skt}/approve-awal', [SktController::class, 'approveAwal'])->name('skt.approve-awal');
    Route::post('skt/{skt}/submit-pertengahan', [SktController::class, 'submitPertengahan'])->name('skt.submit-pertengahan');
    Route::post('skt/{skt}/approve-pertengahan', [SktController::class, 'approvePertengahan'])->name('skt.approve-pertengahan');
    Route::post('skt/{skt}/submit-akhir', [SktController::class, 'submitAkhir'])->name('skt.submit-akhir');
    Route::post('skt/{skt}/reopen', [SktController::class, 'reopen'])->name('skt.reopen');
});

// Evaluation Routes
Route::middleware('auth')->group(function () {
    Route::resource('evaluations', EvaluationController::class);
    Route::get('evaluations/{evaluation}/{step?}', [EvaluationController::class, 'show'])
        ->name('evaluations.show')
        ->where('step', '[I|II|III|IV|V|VI|VII|VIII|IX]+');
    Route::put('evaluations/{evaluation}/update-section', [EvaluationController::class, 'updateSection'])
         ->name('evaluations.update-section');
    Route::put('evaluations/{evaluation}/update-scores', [EvaluationController::class, 'updateScores'])
         ->name('evaluations.update-scores');
    Route::post('evaluations/{evaluation}/submit-pyd', [EvaluationController::class, 'submitPYD'])
         ->name('evaluations.submit-pyd');
    Route::post('evaluations/{evaluation}/submit-ppp', [EvaluationController::class, 'submitPPP'])
         ->name('evaluations.submit-ppp');
    Route::post('evaluations/{evaluation}/submit-ppk', [EvaluationController::class, 'submitPPK'])
         ->name('evaluations.submit-ppk');
    Route::post('evaluations/{evaluation}/reopen', [EvaluationController::class, 'reopen'])
         ->name('evaluations.reopen');
    Route::post('evaluations/{evaluation}/reassign', [EvaluationController::class, 'reassign'])
         ->name('evaluations.reassign');
});

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/skt', [ReportController::class, 'generateSktReport'])->name('reports.skt');
    Route::get('/evaluation', [ReportController::class, 'generateEvaluationReport'])->name('reports.evaluation');
    Route::get('/individual', [ReportController::class, 'generateIndividualReport'])->name('reports.individual');
});