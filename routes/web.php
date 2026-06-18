<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\IntakeSubmissionController as AdminIntakeSubmissionController;
use App\Http\Controllers\Admin\MilestoneController as AdminMilestoneController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\UploadApprovalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\IntakeController;
use App\Http\Controllers\Portal\CategoryController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/get-started', [IntakeController::class, 'create'])->name('intake.create');
Route::post('/get-started', [IntakeController::class, 'store'])->name('intake.store');

Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/portal', DashboardController::class)->name('portal.dashboard');
    Route::get('/portal/files/{category}', [CategoryController::class, 'show'])->name('portal.category');
    Route::post('/portal/projects/{project}/uploads', [UploadController::class, 'store'])->name('portal.uploads.store');
    Route::delete('/portal/uploads/{upload}', [UploadController::class, 'destroy'])->name('portal.uploads.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    Route::get('/intake-submissions', [AdminIntakeSubmissionController::class, 'index'])->name('intake-submissions.index');
    Route::get('/intake-submissions/{intakeSubmission}', [AdminIntakeSubmissionController::class, 'show'])->name('intake-submissions.show');
    Route::patch('/intake-submissions/{intakeSubmission}', [AdminIntakeSubmissionController::class, 'update'])->name('intake-submissions.update');

    Route::get('/projects/{project}', [AdminProjectController::class, 'show'])->name('projects.show');
    Route::patch('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');

    Route::post('/projects/{project}/milestones', [AdminMilestoneController::class, 'store'])->name('milestones.store');
    Route::patch('/milestones/{milestone}', [AdminMilestoneController::class, 'update'])->name('milestones.update');
    Route::delete('/milestones/{milestone}', [AdminMilestoneController::class, 'destroy'])->name('milestones.destroy');

    Route::patch('/uploads/{upload}/approve', [UploadApprovalController::class, 'toggle'])->name('uploads.approve');
});
