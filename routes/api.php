<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TechnicalTaskController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| TalentFlow API Routes
|--------------------------------------------------------------------------
*/

// Public Authentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Public Job listings and details
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{job}', [JobController::class, 'show']);
Route::get('/skills', [SkillController::class, 'index']);

// Protected Routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // High-speed workspace bootstrap
    Route::get('/workspace/bootstrap', [WorkspaceController::class, 'bootstrap']);

    // Auth endpoints
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Resume Management
    Route::post('/resumes/upload', [ResumeController::class, 'upload']);
    Route::get('/resumes/{resume}', [ResumeController::class, 'show']);
    Route::post('/resumes/{resume}/process', [ResumeController::class, 'process']);

    // Job Application
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'apply']);

    // Applications & Hiring Pipeline
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);
    Route::get('/applications/{application}/history', [ApplicationController::class, 'history']);

    // Recruiter / Admin Job Management
    Route::middleware('role:recruiter,admin')->group(function () {
        Route::post('/jobs', [JobController::class, 'store']);
        Route::put('/jobs/{job}', [JobController::class, 'update']);
        Route::delete('/jobs/{job}', [JobController::class, 'destroy']);

        Route::post('/skills', [SkillController::class, 'store']);

        // Pipeline stage progression & scoring
        Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus']);
        Route::post('/applications/{application}/score', [ApplicationController::class, 'recalculateScore']);

        // Schedule interview & assign tasks from application
        Route::post('/applications/{application}/interviews', [InterviewController::class, 'schedule']);
        Route::post('/applications/{application}/technical-tasks', [TechnicalTaskController::class, 'assign']);

        // Candidate directory
        Route::get('/candidates', [CandidateController::class, 'index']);
        Route::get('/candidates/{candidate}', [CandidateController::class, 'show']);

        // Dashboard Analytics
        Route::get('/dashboard/analytics', [DashboardController::class, 'analytics']);
    });

    // Interviews
    Route::get('/interviews', [InterviewController::class, 'index']);
    Route::get('/interviews/{interview}', [InterviewController::class, 'show']);
    Route::middleware('role:recruiter,admin')->group(function () {
        Route::put('/interviews/{interview}', [InterviewController::class, 'update']);
        Route::patch('/interviews/{interview}/cancel', [InterviewController::class, 'cancel']);
        Route::patch('/interviews/{interview}/complete', [InterviewController::class, 'complete']);
    });

    // Technical Tasks
    Route::get('/technical-tasks', [TechnicalTaskController::class, 'index']);
    Route::get('/technical-tasks/{task}', [TechnicalTaskController::class, 'show']);
    Route::patch('/technical-tasks/{task}/start', [TechnicalTaskController::class, 'start']);
    Route::post('/technical-tasks/{task}/submit', [TechnicalTaskController::class, 'submit']);
    Route::middleware('role:recruiter,admin')->group(function () {
        Route::post('/technical-tasks/{task}/review', [TechnicalTaskController::class, 'review']);
    });

    // In-app Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});
