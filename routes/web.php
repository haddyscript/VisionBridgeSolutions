<?php

use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\CalendarController as AdminCalendarController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DeveloperController as AdminDeveloperController;
use App\Http\Controllers\Admin\EmailTemplateController as AdminEmailTemplateController;
use App\Http\Controllers\Admin\IntakeSubmissionController as AdminIntakeSubmissionController;
use App\Http\Controllers\Admin\MaintenancePlanController as AdminMaintenancePlanController;
use App\Http\Controllers\Admin\MilestoneController as AdminMilestoneController;
use App\Http\Controllers\Admin\OnboardingPreviewController as AdminOnboardingPreviewController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ProjectRequestController as AdminProjectRequestController;
use App\Http\Controllers\Admin\RefundRequestController as AdminRefundRequestController;
use App\Http\Controllers\Admin\RecommendationController as AdminRecommendationController;
use App\Http\Controllers\Admin\SatisfactionSurveyController as AdminSatisfactionSurveyController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\ServiceAgreementController as AdminServiceAgreementController;
use App\Http\Controllers\Admin\PartnerPayoutController as AdminPartnerPayoutController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\UploadApprovalController;
use App\Http\Controllers\Admin\WorkOrderController as AdminWorkOrderController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CarePlanSignupController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\DatabaseResetController;
use App\Http\Controllers\DeployerController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\IntakeController;
use App\Models\MaintenancePlan;
use App\Http\Controllers\Portal\AccountController as PortalAccountController;
use App\Http\Controllers\Portal\AnnouncementController as PortalAnnouncementController;
use App\Http\Controllers\Portal\AssistantController as PortalAssistantController;
use App\Http\Controllers\Portal\CarePlanAgreementController as PortalCarePlanAgreementController;
use App\Http\Controllers\Portal\CategoryController;
use App\Http\Controllers\Portal\ConsultationController as PortalConsultationController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DocumentController as PortalDocumentController;
use App\Http\Controllers\Portal\FaqFeedbackController;
use App\Http\Controllers\Portal\NotificationController as PortalNotificationController;
use App\Http\Controllers\Portal\TourController as PortalTourController;
use App\Http\Controllers\Portal\PaymentController as PortalPaymentController;
use App\Http\Controllers\Portal\RefundRequestController as PortalRefundRequestController;
use App\Http\Controllers\Portal\ProjectQuestionnaireController as PortalProjectQuestionnaireController;
use App\Http\Controllers\Portal\ProjectRequestController as PortalProjectRequestController;
use App\Http\Controllers\Portal\ProjectReviewController as PortalProjectReviewController;
use App\Http\Controllers\Portal\SatisfactionSurveyController as PortalSatisfactionSurveyController;
use App\Http\Controllers\Portal\SearchController as PortalSearchController;
use App\Http\Controllers\Portal\ServiceAgreementController as PortalServiceAgreementController;
use App\Http\Controllers\Portal\TwoFactorController as PortalTwoFactorController;
use App\Http\Controllers\Portal\WebsiteTypeController as PortalWebsiteTypeController;
use App\Http\Controllers\Portal\SubscriptionController as PortalSubscriptionController;
use App\Http\Controllers\Portal\SuspendedController as PortalSuspendedController;
use App\Http\Controllers\Portal\UploadController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'carePlans' => MaintenancePlan::orderBy('sort_order')->get(),
    ]);
})->name('home');

Route::get('/onboarding/start', function () {
    if (auth()->check()) {
        return redirect()->route('portal.dashboard');
    }
    return view('onboarding.welcome');
})->name('onboarding.welcome');

Route::get('/get-started', [IntakeController::class, 'create'])->name('intake.create');
Route::post('/get-started', [IntakeController::class, 'store'])->name('intake.store');

Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

Route::get('/book-consultation', [ConsultationController::class, 'create'])->name('consultation.create');
Route::post('/book-consultation', [ConsultationController::class, 'store'])->name('consultation.store');

Route::get('/care-plans/get-started/confirmation', [CarePlanSignupController::class, 'confirmation'])->name('care-plan-signup.confirmation');
Route::get('/care-plans/check-email', [CarePlanSignupController::class, 'checkEmail'])->name('care-plan-signup.check-email');
Route::get('/care-plans/{maintenancePlan}/get-started', [CarePlanSignupController::class, 'create'])->name('care-plan-signup.create');
Route::post('/care-plans/{maintenancePlan}/get-started', [CarePlanSignupController::class, 'store'])->name('care-plan-signup.store');

Route::match(['get', 'post'], '/deployer', [DeployerController::class, 'deploy'])->name('deployer');
Route::get('/migrate', [DeployerController::class, 'migrate'])->name('deployer.migrate');
Route::get('/reset-database', [DatabaseResetController::class, 'reset'])->name('database.reset');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::get('/two-factor-challenge', [TwoFactorChallengeController::class, 'create'])->name('two-factor.challenge');
Route::post('/two-factor-challenge', [TwoFactorChallengeController::class, 'store'])->middleware('throttle:6,1')->name('two-factor.challenge.store');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::patch('/theme', [ThemeController::class, 'update'])->name('theme.update');

    Route::get('/email/verify', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Reachable regardless of onboarding progress — otherwise the
    // onboarding.complete middleware below would redirect here in a loop.
    // Each controller enforces its own step's prerequisite (e.g. the
    // questionnaire redirects back to the agreement if it isn't signed yet).
    Route::get('/portal/website-type', [PortalWebsiteTypeController::class, 'show'])->name('portal.website-type.show');
    Route::post('/portal/website-type', [PortalWebsiteTypeController::class, 'store'])->name('portal.website-type.store');

    Route::get('/portal/care-plan-agreement', [PortalCarePlanAgreementController::class, 'show'])->name('portal.care-plan-agreement.show');
    Route::post('/portal/care-plan-agreement', [PortalCarePlanAgreementController::class, 'store'])->name('portal.care-plan-agreement.store');

    Route::get('/portal/agreement/summary', [PortalServiceAgreementController::class, 'summary'])->name('portal.agreement.summary');
    Route::post('/portal/agreement/summary', [PortalServiceAgreementController::class, 'confirmSummary'])->name('portal.agreement.summary.confirm');

    Route::get('/portal/agreement', [PortalServiceAgreementController::class, 'show'])->name('portal.agreement.show');
    Route::post('/portal/agreement', [PortalServiceAgreementController::class, 'store'])->name('portal.agreement.store');
    Route::get('/portal/agreement/{signature}/download', [PortalServiceAgreementController::class, 'download'])->name('portal.agreement.download');
    Route::get('/portal/agreement/{signature}/preview', [PortalServiceAgreementController::class, 'preview'])->name('portal.agreement.preview');
    Route::get('/portal/agreement/{signature}/filled', [PortalServiceAgreementController::class, 'viewFilled'])->name('portal.agreement.filled');
    Route::get('/portal/agreement/templates/{serviceAgreementTemplate}/view', [PortalServiceAgreementController::class, 'viewTemplate'])->name('portal.agreement.view-template');

    Route::get('/portal/questionnaire', [PortalProjectQuestionnaireController::class, 'show'])->name('portal.questionnaire.show');
    Route::post('/portal/questionnaire', [PortalProjectQuestionnaireController::class, 'store'])->name('portal.questionnaire.store');

    // The notification bell renders on every portal page (layouts.portal),
    // including onboarding pages, so this must be reachable regardless of
    // onboarding progress too.
    Route::post('/portal/notifications/read', [PortalNotificationController::class, 'markRead'])->name('portal.notifications.read');
    Route::post('/portal/notifications/{notification}/read', [PortalNotificationController::class, 'markOneRead'])->name('portal.notifications.read-one');
    Route::get('/portal/notifications', [PortalNotificationController::class, 'index'])->name('portal.notifications.index');
    Route::post('/portal/tour/complete', [PortalTourController::class, 'complete'])->name('portal.tour.complete');

    // Reachable while impersonating (i.e. authenticated *as the client*, not
    // an admin) — this is how an admin viewing-as-client gets back to their
    // own session, so it deliberately sits outside the admin-only route group.
    Route::post('/impersonate/stop', [ImpersonationController::class, 'stop'])->name('impersonate.stop');
});

Route::middleware(['auth', 'verified', 'project.not-suspended', 'onboarding.complete'])->group(function () {
    Route::get('/portal/suspended', [PortalSuspendedController::class, 'show'])->name('portal.suspended');

    Route::get('/portal', DashboardController::class)->name('portal.dashboard');
    Route::get('/portal/documents', [PortalDocumentController::class, 'index'])->name('portal.documents.index');
    Route::get('/portal/project-requests', [PortalProjectRequestController::class, 'show'])->name('portal.project-requests.show');
    Route::post('/portal/project-requests', [PortalProjectRequestController::class, 'store'])->name('portal.project-requests.store');
    Route::get('/portal/consultation', [PortalConsultationController::class, 'create'])->name('portal.consultation.create');
    Route::post('/portal/consultation', [PortalConsultationController::class, 'store'])->name('portal.consultation.store');
    Route::get('/portal/files/{category}', [CategoryController::class, 'show'])->name('portal.category');
    Route::get('/portal/files/{category}/download', [CategoryController::class, 'downloadAll'])->name('portal.category.download');
    Route::post('/portal/projects/{project}/uploads', [UploadController::class, 'store'])->name('portal.uploads.store');
    Route::post('/portal/uploads/{upload}/reply', [UploadController::class, 'reply'])->name('portal.uploads.reply');
    Route::post('/portal/uploads/{upload}/read', [UploadController::class, 'markRead'])->name('portal.uploads.read');
    Route::delete('/portal/uploads/{upload}', [UploadController::class, 'destroy'])->name('portal.uploads.destroy');

    Route::get('/portal/payments', [PortalPaymentController::class, 'index'])->name('portal.payments.index');
    Route::post('/portal/payments/{payment}/checkout', [PortalPaymentController::class, 'checkout'])->name('portal.payments.checkout');
    Route::get('/portal/payments/{payment}/receipt', [PortalPaymentController::class, 'receipt'])->name('portal.payments.receipt');
    Route::post('/portal/payments/{payment}/refund-request', [PortalRefundRequestController::class, 'store'])->name('portal.payments.refund-request');
    Route::get('/portal/payments-statement', [PortalPaymentController::class, 'statement'])->name('portal.payments.statement');

    // GET allowed too so the "set up your billing" link in emails can take the
    // client straight to Stripe Checkout without an intermediate confirm page.
    Route::match(['get', 'post'], '/portal/subscriptions/{subscription}/checkout', [PortalSubscriptionController::class, 'checkout'])->name('portal.subscriptions.checkout');
    Route::post('/portal/subscriptions/{subscription}/confirm', [PortalSubscriptionController::class, 'confirm'])->name('portal.subscriptions.confirm');
    Route::post('/portal/subscriptions/{subscription}/refresh', [PortalSubscriptionController::class, 'refresh'])->name('portal.subscriptions.refresh');
    Route::get('/portal/subscription-payments/{subscriptionPayment}/receipt', [PortalSubscriptionController::class, 'receipt'])->name('portal.subscription-payments.receipt');
    Route::get('/portal/billing', [PortalSubscriptionController::class, 'manageBilling'])->name('portal.billing.show');
    Route::post('/portal/subscriptions/{subscription}/payment-method', [PortalSubscriptionController::class, 'updatePaymentMethod'])->name('portal.subscriptions.update-payment-method');
    Route::post('/portal/subscriptions/{subscription}/cancel', [PortalSubscriptionController::class, 'cancelPlan'])->name('portal.subscriptions.cancel');
    Route::post('/portal/subscriptions/{subscription}/restart', [PortalSubscriptionController::class, 'restartPlan'])->name('portal.subscriptions.restart');
    Route::post('/portal/subscriptions/{subscription}/change-plan', [PortalSubscriptionController::class, 'changePlan'])->name('portal.subscriptions.change-plan');

    Route::get('/portal/account', [PortalAccountController::class, 'index'])->name('portal.account.index');
    Route::patch('/portal/account/profile', [PortalAccountController::class, 'updateProfile'])->name('portal.account.profile.update');
    Route::patch('/portal/account/business-info', [PortalAccountController::class, 'updateBusinessInfo'])->name('portal.account.business-info.update');
    Route::patch('/portal/account/password', [PortalAccountController::class, 'updatePassword'])->name('portal.account.password.update');
    Route::patch('/portal/account/notifications', [PortalAccountController::class, 'updateNotifications'])->name('portal.account.notifications.update');
    Route::post('/portal/account/logout-other-devices', [PortalAccountController::class, 'logoutOtherDevices'])->name('portal.account.logout-other-devices');
    Route::post('/portal/account/closure-request', [PortalAccountController::class, 'requestClosure'])->name('portal.account.closure-request');

    Route::get('/portal/two-factor', [PortalTwoFactorController::class, 'show'])->name('portal.two-factor.show');
    Route::post('/portal/two-factor/confirm', [PortalTwoFactorController::class, 'confirm'])->name('portal.two-factor.confirm');
    Route::post('/portal/two-factor/disable', [PortalTwoFactorController::class, 'disable'])->name('portal.two-factor.disable');
    Route::post('/portal/two-factor/recovery-codes', [PortalTwoFactorController::class, 'regenerateRecoveryCodes'])->name('portal.two-factor.recovery-codes');

    Route::view('/portal/faq', 'portal.faq')->name('portal.faq');
    Route::post('/portal/faq/feedback', [FaqFeedbackController::class, 'store'])->name('portal.faq.feedback');

    Route::post('/portal/review/approve', [PortalProjectReviewController::class, 'approve'])->name('portal.review.approve');
    Route::post('/portal/review/cancel', [PortalProjectReviewController::class, 'cancel'])->name('portal.review.cancel');

    Route::post('/portal/announcements/{announcement}/dismiss', [PortalAnnouncementController::class, 'dismiss'])->name('portal.announcements.dismiss');
    Route::get('/portal/search', [PortalSearchController::class, 'index'])->name('portal.search');

    Route::get('/portal/assistant', [PortalAssistantController::class, 'show'])->name('portal.assistant.show');
    Route::post('/portal/assistant', [PortalAssistantController::class, 'send'])->name('portal.assistant.send');

    Route::get('/portal/survey', [PortalSatisfactionSurveyController::class, 'show'])->name('portal.survey.show');
    Route::post('/portal/survey', [PortalSatisfactionSurveyController::class, 'store'])->name('portal.survey.store');
});

Route::middleware(['auth', 'admin', 'admin-page-access'])->prefix('admin')->name('admin.')->group(function () {

    // ─── Dashboard ───────────────────────────────────────────────────────────
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    // ─── Announcements ───────────────────────────────────────────────────────
    Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
    // Dismissing a banner you can see shouldn't require the "Announcements"
    // management permission, so this route opts out of admin-page-access.
    Route::post('/announcements/{announcement}/dismiss', [AdminAnnouncementController::class, 'dismiss'])->name('announcements.dismiss')->withoutMiddleware('admin-page-access');
    Route::patch('/announcements/{announcement}', [AdminAnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // ─── Clients ─────────────────────────────────────────────────────────────
    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::patch('/clients/{client}', [AdminClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [AdminClientController::class, 'destroy'])->name('clients.destroy');
    Route::post('/clients/{client}/impersonate', [AdminClientController::class, 'impersonate'])->name('clients.impersonate');

    // ─── Calendar ────────────────────────────────────────────────────────────
    Route::get('/calendar', [AdminCalendarController::class, 'index'])->name('calendar');
    Route::post('/calendar/events', [AdminCalendarController::class, 'store'])->name('calendar.events.store');
    Route::delete('/calendar/events/{calendarEvent}', [AdminCalendarController::class, 'destroy'])->name('calendar.events.destroy');

    // ─── Inbox ───────────────────────────────────────────────────────────────
    Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::patch('/contact-messages/{contactMessage}/read', [AdminContactMessageController::class, 'toggleRead'])->name('contact-messages.toggle-read');

    Route::get('/consultations', [AdminConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/{consultation}', [AdminConsultationController::class, 'show'])->name('consultations.show');
    Route::patch('/consultations/{consultation}', [AdminConsultationController::class, 'update'])->name('consultations.update');
    Route::post('/consultations/{consultation}/notify', [AdminConsultationController::class, 'notifyClient'])->name('consultations.notify');
    Route::patch('/consultations/{consultation}/read', [AdminConsultationController::class, 'toggleRead'])->name('consultations.toggle-read');
    Route::delete('/consultations/{consultation}', [AdminConsultationController::class, 'destroy'])->name('consultations.destroy');

    Route::get('/intake-submissions', [AdminIntakeSubmissionController::class, 'index'])->name('intake-submissions.index');
    Route::get('/intake-submissions/{intakeSubmission}', [AdminIntakeSubmissionController::class, 'show'])->name('intake-submissions.show');
    Route::patch('/intake-submissions/{intakeSubmission}', [AdminIntakeSubmissionController::class, 'update'])->name('intake-submissions.update');
    Route::post('/intake-submissions/{intakeSubmission}/convert', [AdminIntakeSubmissionController::class, 'convert'])->name('intake-submissions.convert');
    Route::post('/intake-submissions/{intakeSubmission}/resend-welcome-email', [AdminIntakeSubmissionController::class, 'resendWelcomeEmail'])->name('intake-submissions.resend-welcome-email');

    Route::get('/work-orders', [AdminWorkOrderController::class, 'index'])->name('work-orders.index');
    Route::get('/developers', [AdminDeveloperController::class, 'index'])->name('developers.index');

    Route::get('/project-requests', [AdminProjectRequestController::class, 'index'])->name('project-requests.index');
    Route::get('/project-requests/{projectRequest}', [AdminProjectRequestController::class, 'show'])->name('project-requests.show');
    Route::patch('/project-requests/{projectRequest}', [AdminProjectRequestController::class, 'update'])->name('project-requests.update');
    Route::patch('/project-requests/{projectRequest}/assign-developer', [AdminProjectRequestController::class, 'assignDeveloper'])->name('project-requests.assign-developer');
    Route::patch('/project-requests/{projectRequest}/developer-status', [AdminProjectRequestController::class, 'updateDeveloperStatus'])->name('project-requests.developer-status');

    Route::get('/refund-requests', [AdminRefundRequestController::class, 'index'])->name('refund-requests.index');
    Route::patch('/refund-requests/{refundRequest}', [AdminRefundRequestController::class, 'update'])->name('refund-requests.update');

    // ─── Projects & Milestones ───────────────────────────────────────────────
    Route::get('/projects/{project}', [AdminProjectController::class, 'show'])->name('projects.show');
    Route::patch('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');
    Route::post('/projects/{project}/reset-client-password', [AdminProjectController::class, 'resetClientPassword'])->name('projects.reset-client-password');
    Route::post('/projects/{project}/restore-access', [AdminProjectController::class, 'restoreAccess'])->name('projects.restore-access');
    Route::get('/projects/{project}/onboarding-preview/{step?}', [AdminOnboardingPreviewController::class, 'show'])->name('projects.onboarding-preview');

    Route::post('/projects/{project}/milestones', [AdminMilestoneController::class, 'store'])->name('milestones.store');
    Route::patch('/milestones/{milestone}', [AdminMilestoneController::class, 'update'])->name('milestones.update');
    Route::delete('/milestones/{milestone}', [AdminMilestoneController::class, 'destroy'])->name('milestones.destroy');

    // ─── Files, Content & Revisions ──────────────────────────────────────────
    Route::patch('/uploads/{upload}/approve', [UploadApprovalController::class, 'toggle'])->name('uploads.approve');
    Route::patch('/uploads/{upload}/status', [UploadApprovalController::class, 'updateStatus'])->name('uploads.status');
    Route::patch('/uploads/{upload}/reply', [UploadApprovalController::class, 'reply'])->name('uploads.reply');
    Route::patch('/uploads/{upload}/dev-instructions', [UploadApprovalController::class, 'updateDevInstructions'])->name('uploads.dev-instructions');
    Route::post('/uploads/{upload}/read', [UploadApprovalController::class, 'markRead'])->name('uploads.read');
    Route::patch('/uploads/{upload}/assign-developer', [UploadApprovalController::class, 'assignDeveloper'])->name('uploads.assign-developer');
    Route::patch('/uploads/{upload}/developer-status', [UploadApprovalController::class, 'updateDeveloperStatus'])->name('uploads.developer-status');

    Route::get('/satisfaction-surveys', [AdminSatisfactionSurveyController::class, 'index'])->name('satisfaction-surveys.index');
    Route::patch('/satisfaction-surveys/{satisfactionSurvey}/archive', [AdminSatisfactionSurveyController::class, 'archive'])->name('satisfaction-surveys.archive');
    Route::patch('/satisfaction-surveys/{satisfactionSurvey}/feature', [AdminSatisfactionSurveyController::class, 'feature'])->name('satisfaction-surveys.feature');
    Route::delete('/satisfaction-surveys/{satisfactionSurvey}', [AdminSatisfactionSurveyController::class, 'destroy'])->name('satisfaction-surveys.destroy');

    Route::get('/recommendations', [AdminRecommendationController::class, 'index'])->name('recommendations.index');
    Route::post('/projects/{project}/recommendations', [AdminRecommendationController::class, 'store'])->name('recommendations.store');
    Route::patch('/recommendations/{recommendation}', [AdminRecommendationController::class, 'update'])->name('recommendations.update');

    // ─── Payments & Billing ──────────────────────────────────────────────────
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/projects/{project}/payments', [AdminPaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{payment}', [AdminPaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('/payments/{payment}/sync', [AdminPaymentController::class, 'sync'])->name('payments.sync');

    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/projects/{project}/subscriptions', [AdminSubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::delete('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    Route::post('/subscriptions/{subscription}/sync', [AdminSubscriptionController::class, 'sync'])->name('subscriptions.sync');

    Route::get('/partner-payouts', [AdminPartnerPayoutController::class, 'index'])->name('partner-payouts.index');
    Route::post('/partner-payouts/rate', [AdminPartnerPayoutController::class, 'setRate'])->name('partner-payouts.set-rate');
    Route::post('/partner-payouts/recalculate', [AdminPartnerPayoutController::class, 'recalculateAll'])->name('partner-payouts.recalculate');
    Route::patch('/partner-payouts/{partnerPayout}', [AdminPartnerPayoutController::class, 'update'])->name('partner-payouts.update');

    // ─── Settings ────────────────────────────────────────────────────────────
    Route::get('/care-plans', [AdminMaintenancePlanController::class, 'index'])->name('care-plans.index');
    Route::post('/care-plans', [AdminMaintenancePlanController::class, 'store'])->name('care-plans.store');
    Route::patch('/care-plans/{carePlan}', [AdminMaintenancePlanController::class, 'update'])->name('care-plans.update');
    Route::delete('/care-plans/{carePlan}', [AdminMaintenancePlanController::class, 'destroy'])->name('care-plans.destroy');

    Route::get('/service-agreement', [AdminServiceAgreementController::class, 'index'])->name('service-agreement.index');
    Route::post('/service-agreement', [AdminServiceAgreementController::class, 'store'])->name('service-agreement.store');
    Route::post('/service-agreement/{serviceAgreementSignature}/resend', [AdminServiceAgreementController::class, 'resend'])->name('service-agreement.resend');
    Route::get('/service-agreement/templates/{serviceAgreementTemplate}/download', [AdminServiceAgreementController::class, 'downloadTemplate'])->name('service-agreement.templates.download');
    Route::get('/service-agreement/templates/{serviceAgreementTemplate}/view', [AdminServiceAgreementController::class, 'viewTemplate'])->name('service-agreement.templates.view');

    Route::get('/team', [AdminTeamController::class, 'index'])->name('team.index');
    Route::patch('/team/profile', [AdminTeamController::class, 'updateProfile'])->name('team.profile.update');
    Route::patch('/team/password', [AdminTeamController::class, 'updatePassword'])->name('team.password.update');

    Route::middleware('super-admin')->group(function () {
        Route::post('/team', [AdminTeamController::class, 'store'])->name('team.store');
        Route::patch('/team/{user}/super-admin', [AdminTeamController::class, 'toggleSuperAdmin'])->name('team.toggle-super-admin');
        Route::patch('/team/{user}/permissions', [AdminTeamController::class, 'updatePermissions'])->name('team.permissions.update');
        Route::patch('/team/{user}/job-title', [AdminTeamController::class, 'updateJobTitle'])->name('team.job-title.update');
        Route::delete('/team/{user}', [AdminTeamController::class, 'destroy'])->name('team.destroy');
    });

    Route::middleware('owner')->group(function () {
        Route::patch('/team/{user}/active', [AdminTeamController::class, 'toggleActive'])->name('team.toggle-active');
        Route::post('/team/{user}/impersonate', [AdminTeamController::class, 'impersonate'])->name('team.impersonate');
    });

    Route::get('/email-templates', [AdminEmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('/email-templates/{template}/preview', [AdminEmailTemplateController::class, 'preview'])->name('email-templates.preview');

    Route::view('/faq', 'admin.faq')->name('faq');
});
