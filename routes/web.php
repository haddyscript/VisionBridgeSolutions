<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\IntakeSubmissionController as AdminIntakeSubmissionController;
use App\Http\Controllers\Admin\MaintenancePlanController as AdminMaintenancePlanController;
use App\Http\Controllers\Admin\MilestoneController as AdminMilestoneController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\UploadApprovalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\IntakeController;
use App\Models\MaintenancePlan;
use App\Http\Controllers\Portal\CategoryController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\PaymentController as PortalPaymentController;
use App\Http\Controllers\Portal\SubscriptionController as PortalSubscriptionController;
use App\Http\Controllers\Portal\UploadController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'carePlans' => MaintenancePlan::orderBy('sort_order')->get(),
    ]);
})->name('home');

Route::get('/get-started', [IntakeController::class, 'create'])->name('intake.create');
Route::post('/get-started', [IntakeController::class, 'store'])->name('intake.store');

Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::patch('/theme', [ThemeController::class, 'update'])->name('theme.update');

    Route::get('/portal', DashboardController::class)->name('portal.dashboard');
    Route::get('/portal/files/{category}', [CategoryController::class, 'show'])->name('portal.category');
    Route::post('/portal/projects/{project}/uploads', [UploadController::class, 'store'])->name('portal.uploads.store');
    Route::delete('/portal/uploads/{upload}', [UploadController::class, 'destroy'])->name('portal.uploads.destroy');

    Route::get('/portal/payments', [PortalPaymentController::class, 'index'])->name('portal.payments.index');
    Route::post('/portal/payments/{payment}/checkout', [PortalPaymentController::class, 'checkout'])->name('portal.payments.checkout');

    Route::post('/portal/subscriptions/{subscription}/checkout', [PortalSubscriptionController::class, 'checkout'])->name('portal.subscriptions.checkout');
    Route::get('/portal/billing-portal', [PortalSubscriptionController::class, 'billingPortal'])->name('portal.billing-portal');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::patch('/contact-messages/{contactMessage}/read', [AdminContactMessageController::class, 'toggleRead'])->name('contact-messages.toggle-read');

    Route::get('/intake-submissions', [AdminIntakeSubmissionController::class, 'index'])->name('intake-submissions.index');
    Route::get('/intake-submissions/{intakeSubmission}', [AdminIntakeSubmissionController::class, 'show'])->name('intake-submissions.show');
    Route::patch('/intake-submissions/{intakeSubmission}', [AdminIntakeSubmissionController::class, 'update'])->name('intake-submissions.update');

    Route::get('/projects/{project}', [AdminProjectController::class, 'show'])->name('projects.show');
    Route::patch('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');

    Route::post('/projects/{project}/milestones', [AdminMilestoneController::class, 'store'])->name('milestones.store');
    Route::patch('/milestones/{milestone}', [AdminMilestoneController::class, 'update'])->name('milestones.update');
    Route::delete('/milestones/{milestone}', [AdminMilestoneController::class, 'destroy'])->name('milestones.destroy');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/projects/{project}/payments', [AdminPaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{payment}', [AdminPaymentController::class, 'destroy'])->name('payments.destroy');

    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/projects/{project}/subscriptions', [AdminSubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::delete('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    Route::patch('/uploads/{upload}/approve', [UploadApprovalController::class, 'toggle'])->name('uploads.approve');

    Route::get('/care-plans', [AdminMaintenancePlanController::class, 'index'])->name('care-plans.index');
    Route::post('/care-plans', [AdminMaintenancePlanController::class, 'store'])->name('care-plans.store');
    Route::patch('/care-plans/{carePlan}', [AdminMaintenancePlanController::class, 'update'])->name('care-plans.update');
    Route::delete('/care-plans/{carePlan}', [AdminMaintenancePlanController::class, 'destroy'])->name('care-plans.destroy');
});
