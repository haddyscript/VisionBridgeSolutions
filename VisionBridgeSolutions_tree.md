```
VisionBridgeSolutions/
├── .claude/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── BackfillSubscriptionPeriodEnds.php
│   │       ├── CancelDuplicateCarePlanSubscriptions.php
│   │       ├── RetryFailedPayments.php
│   │       ├── SendRenewalReminders.php
│   │       ├── SuspendOverdueProjects.php
│   │       └── VerifyPartnerPayouts.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AnnouncementController.php
│   │   │   │   ├── CalendarController.php
│   │   │   │   ├── ClientController.php
│   │   │   │   ├── ConsultationController.php
│   │   │   │   ├── ContactMessageController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── DeveloperController.php
│   │   │   │   ├── EmailTemplateController.php
│   │   │   │   ├── IntakeSubmissionController.php
│   │   │   │   ├── MaintenancePlanController.php
│   │   │   │   ├── MilestoneController.php
│   │   │   │   ├── OnboardingPreviewController.php
│   │   │   │   ├── PartnerPayoutController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   ├── ProjectController.php
│   │   │   │   ├── ProjectRequestController.php
│   │   │   │   ├── RecommendationController.php
│   │   │   │   ├── RefundRequestController.php
│   │   │   │   ├── SatisfactionSurveyController.php
│   │   │   │   ├── ServiceAgreementController.php
│   │   │   │   ├── SubscriptionController.php
│   │   │   │   ├── TeamController.php
│   │   │   │   ├── UploadApprovalController.php
│   │   │   │   └── WorkOrderController.php
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── EmailVerificationNotificationController.php
│   │   │   │   ├── EmailVerificationPromptController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   ├── TwoFactorChallengeController.php
│   │   │   │   └── VerifyEmailController.php
│   │   │   ├── Portal/
│   │   │   │   ├── AccountController.php
│   │   │   │   ├── AnnouncementController.php
│   │   │   │   ├── AssistantController.php
│   │   │   │   ├── CarePlanAgreementController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── ConsultationController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── DocumentController.php
│   │   │   │   ├── FaqFeedbackController.php
│   │   │   │   ├── NotificationController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   ├── ProjectQuestionnaireController.php
│   │   │   │   ├── ProjectRequestController.php
│   │   │   │   ├── ProjectReviewController.php
│   │   │   │   ├── RefundRequestController.php
│   │   │   │   ├── SatisfactionSurveyController.php
│   │   │   │   ├── SearchController.php
│   │   │   │   ├── ServiceAgreementController.php
│   │   │   │   ├── SubscriptionController.php
│   │   │   │   ├── SuspendedController.php
│   │   │   │   ├── TourController.php
│   │   │   │   ├── TwoFactorController.php
│   │   │   │   ├── UploadController.php
│   │   │   │   └── WebsiteTypeController.php
│   │   │   ├── CarePlanSignupController.php
│   │   │   ├── ConsultationController.php
│   │   │   ├── ContactMessageController.php
│   │   │   ├── Controller.php
│   │   │   ├── DatabaseResetController.php
│   │   │   ├── DeployerController.php
│   │   │   ├── ImpersonationController.php
│   │   │   ├── IntakeController.php
│   │   │   ├── StripeWebhookController.php
│   │   │   └── ThemeController.php
│   │   └── Middleware/
│   │       ├── EnsureOnboardingComplete.php
│   │       ├── EnsureProjectNotSuspended.php
│   │       ├── EnsureUserCanAccessAdminPage.php
│   │       ├── EnsureUserIsAdmin.php
│   │       ├── EnsureUserIsOwner.php
│   │       ├── EnsureUserIsSuperAdmin.php
│   │       └── UpdateLastSeen.php
│   ├── Mail/
│   │   ├── AccountEmailChangedMail.php
│   │   ├── AccountPasswordChangedMail.php
│   │   ├── AdminPaymentNotificationMail.php
│   │   ├── ClientReplyMail.php
│   │   ├── ConsultationCancelledMail.php
│   │   ├── ConsultationConfirmedMail.php
│   │   ├── ConsultationReceivedMail.php
│   │   ├── ConsultationRescheduledMail.php
│   │   ├── FaithStackNewClientMail.php
│   │   ├── IntakeConfirmationMail.php
│   │   ├── InvoiceSentMail.php
│   │   ├── NewClientRegistrationMail.php
│   │   ├── NewClientUploadMail.php
│   │   ├── NewConsultationMail.php
│   │   ├── NewContactMessageMail.php
│   │   ├── NewIntakeSubmissionMail.php
│   │   ├── NewProjectRequestMail.php
│   │   ├── NewRefundRequestMail.php
│   │   ├── PaymentFailedMail.php
│   │   ├── PaymentReceiptMail.php
│   │   ├── ProjectApprovedMail.php
│   │   ├── ProjectCanceledMail.php
│   │   ├── ProjectLaunchedMail.php
│   │   ├── ProjectQuoteReadyMail.php
│   │   ├── ProjectRestoredMail.php
│   │   ├── ProjectSuspendedMail.php
│   │   ├── QuestionnaireCompletedMail.php
│   │   ├── RefundRequestApprovedMail.php
│   │   ├── RefundRequestDeclinedMail.php
│   │   ├── ServiceAgreementSignedMail.php
│   │   ├── SubscriptionCreatedMail.php
│   │   ├── SubscriptionReceiptMail.php
│   │   ├── SubscriptionRenewalReminderMail.php
│   │   ├── SubscriptionStatusAlertMail.php
│   │   ├── SystemAlertMail.php
│   │   ├── UploadRepliedMail.php
│   │   ├── WelcomeClientMail.php
│   │   ├── WorkOrderAssignedMail.php
│   │   ├── WorkOrderInstructionsMail.php
│   │   └── WorkOrderInternalUpdateMail.php
│   ├── Models/
│   │   ├── AdminPagePermission.php
│   │   ├── Announcement.php
│   │   ├── AnnouncementDismissal.php
│   │   ├── AppSetting.php
│   │   ├── AssistantConversation.php
│   │   ├── AssistantMessage.php
│   │   ├── CalendarEvent.php
│   │   ├── CarePlanAgreement.php
│   │   ├── ClientNotification.php
│   │   ├── Consultation.php
│   │   ├── ContactMessage.php
│   │   ├── FaqFeedback.php
│   │   ├── IntakeFile.php
│   │   ├── IntakeSubmission.php
│   │   ├── LoginActivity.php
│   │   ├── MaintenancePlan.php
│   │   ├── Milestone.php
│   │   ├── PartnerPayout.php
│   │   ├── Payment.php
│   │   ├── Project.php
│   │   ├── ProjectQuestionnaire.php
│   │   ├── ProjectRequest.php
│   │   ├── Recommendation.php
│   │   ├── RefundRequest.php
│   │   ├── SatisfactionSurvey.php
│   │   ├── ServiceAgreementSignature.php
│   │   ├── ServiceAgreementTemplate.php
│   │   ├── Subscription.php
│   │   ├── SubscriptionPayment.php
│   │   ├── Upload.php
│   │   ├── UploadAttachment.php
│   │   ├── UploadReply.php
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   ├── Services/
│   │   ├── AgreementPdfFiller.php
│   │   ├── AssistantService.php
│   │   ├── PaymentReconciler.php
│   │   ├── SubscriptionReconciler.php
│   │   └── TwoFactorAuthenticator.php
│   └── Support/
│       ├── AdminPermissions.php
│       ├── AssetVersion.php
│       └── EmailPreviewStub.php
├── bootstrap/
│   ├── cache/
│   │   ├── .gitignore
│   │   ├── packages.php
│   │   └── services.php
│   ├── app.php
│   └── providers.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── dial_codes.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_06_18_000001_add_role_to_users_table.php
│   │   ├── 2026_06_18_000002_create_projects_table.php
│   │   ├── 2026_06_18_000003_create_milestones_table.php
│   │   ├── 2026_06_18_000004_create_uploads_table.php
│   │   ├── 2026_06_18_000005_add_size_to_uploads_table.php
│   │   ├── 2026_06_18_000006_create_intake_submissions_table.php
│   │   ├── 2026_06_18_000007_create_intake_files_table.php
│   │   ├── 2026_06_18_000008_add_approved_at_to_uploads_table.php
│   │   ├── 2026_06_19_000001_create_contact_messages_table.php
│   │   ├── 2026_06_19_000002_create_payments_table.php
│   │   ├── 2026_06_19_000003_add_stripe_customer_id_to_users_table.php
│   │   ├── 2026_06_19_000004_create_subscriptions_table.php
│   │   ├── 2026_06_19_000005_create_maintenance_plans_table.php
│   │   ├── 2026_06_19_000006_add_read_at_to_contact_messages_table.php
│   │   ├── 2026_06_19_000007_add_theme_to_users_table.php
│   │   ├── 2026_06_19_000008_add_project_id_to_intake_submissions_table.php
│   │   ├── 2026_06_23_000001_create_consultations_table.php
│   │   ├── 2026_06_23_000002_add_status_and_admin_notes_to_consultations_table.php
│   │   ├── 2026_06_23_000003_add_meeting_link_to_consultations_table.php
│   │   ├── 2026_06_23_000004_add_dates_to_milestones_table.php
│   │   ├── 2026_06_23_000005_add_admin_reply_to_uploads_table.php
│   │   ├── 2026_06_23_000006_add_preview_url_to_projects_table.php
│   │   ├── 2026_06_23_000007_create_calendar_events_table.php
│   │   ├── 2026_06_23_000008_add_stripe_receipt_url_to_payments_table.php
│   │   ├── 2026_06_23_000009_create_faq_feedback_table.php
│   │   ├── 2026_06_23_000010_add_cancel_at_period_end_to_subscriptions_table.php
│   │   ├── 2026_06_24_000001_add_progress_override_to_projects_table.php
│   │   ├── 2026_06_24_000002_create_upload_replies_table.php
│   │   ├── 2026_06_24_000003_add_status_to_uploads_table.php
│   │   ├── 2026_06_24_072552_add_last_seen_at_to_users_table.php
│   │   ├── 2026_06_24_084403_add_activity_and_notification_preferences_to_users_table.php
│   │   ├── 2026_06_25_053715_add_country_to_consultations_table.php
│   │   ├── 2026_06_25_060354_add_timezone_to_consultations_table.php
│   │   ├── 2026_06_27_000001_add_design_fields_to_maintenance_plans_table.php
│   │   ├── 2026_06_28_000001_add_faithstack_compensation_to_maintenance_plans_table.php
│   │   ├── 2026_06_28_000002_add_signup_fields_to_subscriptions_table.php
│   │   ├── 2026_06_28_000003_create_subscription_payouts_table.php
│   │   ├── 2026_06_29_000001_add_verification_fields_to_subscription_payouts_table.php
│   │   ├── 2026_06_29_000001_expand_upload_status_values.php
│   │   ├── 2026_06_29_000002_add_dev_instructions_to_uploads_table.php
│   │   ├── 2026_06_29_000003_create_project_requests_table.php
│   │   ├── 2026_06_29_000004_create_recommendations_table.php
│   │   ├── 2026_06_29_000005_create_subscription_payments_table.php
│   │   ├── 2026_06_30_000001_create_service_agreement_templates_table.php
│   │   ├── 2026_06_30_000002_add_pdf_path_to_service_agreement_templates_table.php
│   │   ├── 2026_06_30_000002_create_service_agreement_signatures_table.php
│   │   ├── 2026_07_01_000001_add_onboarding_step_to_users_table.php
│   │   ├── 2026_07_01_000001_create_project_questionnaires_table.php
│   │   ├── 2026_07_01_000002_add_organization_name_and_title_to_service_agreement_signatures.php
│   │   ├── 2026_07_01_000003_add_website_type_to_projects_table.php
│   │   ├── 2026_07_02_000001_add_total_price_and_payment_kind.php
│   │   ├── 2026_07_02_000002_generalize_subscription_payouts_to_partner_payouts.php
│   │   ├── 2026_07_03_000001_add_review_and_refund_fields.php
│   │   ├── 2026_07_03_062654_create_client_notifications_table.php
│   │   ├── 2026_07_03_064735_add_tour_completed_at_to_users_table.php
│   │   ├── 2026_07_03_065944_create_announcements_table.php
│   │   ├── 2026_07_03_065945_create_announcement_dismissals_table.php
│   │   ├── 2026_07_03_070500_create_satisfaction_surveys_table.php
│   │   ├── 2026_07_03_071500_add_two_factor_columns_to_users_table.php
│   │   ├── 2026_07_04_000001_create_care_plan_agreements_table.php
│   │   ├── 2026_07_04_000002_add_suspension_fields.php
│   │   ├── 2026_07_05_000001_add_welcomed_at_to_users_table.php
│   │   ├── 2026_07_05_000002_add_phone_to_users_table.php
│   │   ├── 2026_07_05_000003_create_login_activities_table.php
│   │   ├── 2026_07_05_000004_create_app_settings_table.php
│   │   ├── 2026_07_05_000005_add_filled_pdf_path_to_service_agreement_signatures_table.php
│   │   ├── 2026_07_06_000001_add_renewal_reminder_period_end_to_subscriptions_table.php
│   │   ├── 2026_07_06_000002_add_stripe_price_id_to_maintenance_plans_table.php
│   │   ├── 2026_07_07_000001_create_refund_requests_table.php
│   │   ├── 2026_07_08_000001_add_timezone_to_payments_table.php
│   │   ├── 2026_07_08_000002_add_timezone_to_subscriptions_table.php
│   │   ├── 2026_07_08_000003_add_impersonator_id_to_login_activities_table.php
│   │   ├── 2026_07_09_000001_add_read_at_to_upload_replies_table.php
│   │   ├── 2026_07_09_000002_add_is_super_admin_to_users_table.php
│   │   ├── 2026_07_09_000003_create_admin_page_permissions_table.php
│   │   ├── 2026_07_09_000004_add_is_active_to_users_table.php
│   │   ├── 2026_07_09_000005_create_assistant_conversations_table.php
│   │   ├── 2026_07_09_000006_create_assistant_messages_table.php
│   │   ├── 2026_07_10_000000_add_job_title_to_users_table.php
│   │   ├── 2026_07_10_000001_add_referrals_to_users_table.php
│   │   ├── 2026_07_11_000000_add_developer_assignment_to_uploads_table.php
│   │   ├── 2026_07_11_000001_add_developer_assignment_to_project_requests_table.php
│   │   ├── 2026_07_11_000002_add_archived_and_featured_to_satisfaction_surveys_table.php
│   │   ├── 2026_07_11_000003_add_attachment_to_project_requests_table.php
│   │   ├── 2026_07_11_000004_create_upload_attachments_table.php
│   │   ├── 2026_07_13_100000_add_audiences_to_announcements_table.php
│   │   └── 2026_07_13_120000_add_metadata_to_announcements_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── MaintenancePlanSeeder.php
│   │   └── ServiceAgreementTemplateSeeder.php
│   ├── .gitignore
│   └── database.sqlite
├── docker/
│   └── php/
│       └── uploads.ini
├── nginx/
│   └── default.conf
├── public/
│   ├── client-uploads/
│   │   ├── projects/
│   │   │   └── 1/
│   │   │       ├── document/
│   │   │       ├── image/
│   │   │       ├── logo/
│   │   │       └── revision/
│   │   └── .gitignore
│   ├── image/
│   │   ├── logo/
│   │   │   ├── vbs-logo-v3.jpeg
│   │   │   ├── vbs-logo.png
│   │   │   ├── visionbridgesolutions-logo-tagline.png
│   │   │   └── visionbridgesolutions-logo.png
│   │   ├── marketing/
│   │   │   └── JDGM-marketing.jpeg
│   │   ├── bridge-background-image-v2.png
│   │   ├── bridge-effects.png
│   │   ├── Call_us.png
│   │   ├── care-plan.jpeg
│   │   ├── Church_website_development.jpeg
│   │   ├── Client_Ownership.png
│   │   ├── Custom_Solutions.png
│   │   ├── Custom_Website_Development.jpeg
│   │   ├── Email_us.png
│   │   ├── Faith_Base_Values.png
│   │   ├── faithstack-thumbnail.png
│   │   ├── Fast_Reliable.png
│   │   ├── founder-enhance-removebg.png
│   │   ├── founder-enhance.png
│   │   ├── founder.jpeg
│   │   ├── Free_Consultation.png
│   │   ├── Growth_Focused.png
│   │   ├── Hosting_Management.jpeg
│   │   ├── johnnydavisglobalmission.png
│   │   ├── johnnydavisministries.png
│   │   ├── Landing_Page_Development.jpeg
│   │   ├── Login_LeftSide_Image.png
│   │   ├── logo-v2.png
│   │   ├── Long_Term_Stability.png
│   │   ├── mascot-hi.png
│   │   ├── mascut-hide.png
│   │   ├── mascut-smile.png
│   │   ├── Ministry_Website_Development.jpeg
│   │   ├── Mobile_First_Design.png
│   │   ├── Nonprofit_Website_Development.jpeg
│   │   ├── Our_Mission.png
│   │   ├── Our_Vision.png
│   │   ├── Ownership_First.png
│   │   ├── parallax-bg1.webp
│   │   ├── parallax-bg2-enhance.png
│   │   ├── parallax-bg2.jpeg
│   │   ├── parallax-bg3-enhance.png
│   │   ├── parallax-bg3.jpg
│   │   ├── parallax-bg4-enhance.png
│   │   ├── parallax-bg5-enhance.png
│   │   ├── parallax-bg6-enhance.png
│   │   ├── parallax-bg7-enhance.png
│   │   ├── Partnership_Approach.png
│   │   ├── Professional_Support.png
│   │   ├── Small_Business_Website_Development.jpeg
│   │   ├── vbs-logo-v2.png
│   │   ├── VisionBridge_Solutions_1.jpeg
│   │   ├── vission-bridge-htumbnail.png
│   │   ├── Website_Consulting.jpeg
│   │   ├── Website_Maintenance_Services.jpeg
│   │   └── Website_Redesign_Services.jpeg
│   ├── videos/
│   │   ├── VisionBridge_Solutions_welcome_v.mp4
│   │   └── Web_development_company_hero_video.mp4
│   ├── .htaccess
│   ├── favicon.ico
│   ├── index.php
│   ├── mobile-design.css
│   ├── mobile-design.js
│   └── robots.txt
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/
│       │   ├── announcements/
│       │   │   ├── history.blade.php
│       │   │   └── index.blade.php
│       │   ├── calendar/
│       │   │   └── index.blade.php
│       │   ├── care-plans/
│       │   │   ├── _preview.blade.php
│       │   │   └── index.blade.php
│       │   ├── clients/
│       │   │   └── index.blade.php
│       │   ├── consultations/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── contact-messages/
│       │   │   └── index.blade.php
│       │   ├── developers/
│       │   │   ├── _item-row.blade.php
│       │   │   └── index.blade.php
│       │   ├── email-templates/
│       │   │   ├── index.blade.php
│       │   │   └── render-error.blade.php
│       │   ├── intake-submissions/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── partner-payouts/
│       │   │   └── index.blade.php
│       │   ├── payments/
│       │   │   └── index.blade.php
│       │   ├── project-requests/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── projects/
│       │   │   ├── onboarding-steps/
│       │   │   │   ├── 1-questionnaire.blade.php
│       │   │   │   ├── 2-website-type.blade.php
│       │   │   │   ├── 3-care-plan.blade.php
│       │   │   │   ├── 4-agreement-summary.blade.php
│       │   │   │   └── 5-agreement.blade.php
│       │   │   ├── _text-thread.blade.php
│       │   │   ├── onboarding-preview.blade.php
│       │   │   └── show.blade.php
│       │   ├── recommendations/
│       │   │   └── index.blade.php
│       │   ├── refund-requests/
│       │   │   └── index.blade.php
│       │   ├── satisfaction-surveys/
│       │   │   └── index.blade.php
│       │   ├── service-agreement/
│       │   │   └── index.blade.php
│       │   ├── subscriptions/
│       │   │   └── index.blade.php
│       │   ├── team/
│       │   │   └── index.blade.php
│       │   ├── work-orders/
│       │   │   └── index.blade.php
│       │   ├── dashboard.blade.php
│       │   └── faq.blade.php
│       ├── auth/
│       │   ├── forgot-password.blade.php
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── reset-password.blade.php
│       │   ├── two-factor-challenge.blade.php
│       │   └── verify-email.blade.php
│       ├── care-plan-signup/
│       │   ├── confirmation.blade.php
│       │   └── create.blade.php
│       ├── emails/
│       │   ├── account-email-changed.blade.php
│       │   ├── account-password-changed.blade.php
│       │   ├── admin-payment-notification.blade.php
│       │   ├── client-reply.blade.php
│       │   ├── consultation-cancelled.blade.php
│       │   ├── consultation-confirmed.blade.php
│       │   ├── consultation-received.blade.php
│       │   ├── consultation-rescheduled.blade.php
│       │   ├── faithstack-new-client.blade.php
│       │   ├── intake-confirmation.blade.php
│       │   ├── invoice-sent.blade.php
│       │   ├── new-client-registration.blade.php
│       │   ├── new-client-upload.blade.php
│       │   ├── new-consultation.blade.php
│       │   ├── new-contact-message.blade.php
│       │   ├── new-intake-submission.blade.php
│       │   ├── new-project-request.blade.php
│       │   ├── new-refund-request.blade.php
│       │   ├── payment-failed.blade.php
│       │   ├── payment-receipt.blade.php
│       │   ├── project-approved.blade.php
│       │   ├── project-canceled.blade.php
│       │   ├── project-launched.blade.php
│       │   ├── project-quote-ready.blade.php
│       │   ├── project-restored.blade.php
│       │   ├── project-suspended.blade.php
│       │   ├── questionnaire-completed.blade.php
│       │   ├── refund-request-approved.blade.php
│       │   ├── refund-request-declined.blade.php
│       │   ├── service-agreement-signed.blade.php
│       │   ├── subscription-created.blade.php
│       │   ├── subscription-receipt.blade.php
│       │   ├── subscription-renewal-reminder.blade.php
│       │   ├── subscription-status-alert.blade.php
│       │   ├── system-alert.blade.php
│       │   ├── upload-replied.blade.php
│       │   ├── welcome-client.blade.php
│       │   ├── work-order-assigned.blade.php
│       │   ├── work-order-instructions.blade.php
│       │   └── work-order-internal-update.blade.php
│       ├── errors/
│       │   ├── 404.blade.php
│       │   └── maintenance.blade.php
│       ├── intake/
│       │   └── create.blade.php
│       ├── layouts/
│       │   ├── admin.blade.php
│       │   ├── app.blade.php
│       │   ├── auth.blade.php
│       │   └── portal.blade.php
│       ├── onboarding/
│       │   └── welcome.blade.php
│       ├── partials/
│       │   ├── announcement-banner.blade.php
│       │   ├── announcement-prose-styles.blade.php
│       │   └── getting-started.blade.php
│       ├── pdfs/
│       │   ├── service-agreement-certificate.blade.php
│       │   └── service-agreement.blade.php
│       ├── portal/
│       │   ├── announcements/
│       │   │   └── index.blade.php
│       │   ├── partials/
│       │   │   ├── assistant-widget.blade.php
│       │   │   ├── file-upload-section.blade.php
│       │   │   ├── onboarding-progress.blade.php
│       │   │   ├── subscription-card.blade.php
│       │   │   └── text-submission-section.blade.php
│       │   ├── account.blade.php
│       │   ├── agreement-summary.blade.php
│       │   ├── agreement.blade.php
│       │   ├── care-plan-agreement.blade.php
│       │   ├── category.blade.php
│       │   ├── consultation.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── documents.blade.php
│       │   ├── faq.blade.php
│       │   ├── notifications.blade.php
│       │   ├── payment-checkout.blade.php
│       │   ├── payment-receipt.blade.php
│       │   ├── payments.blade.php
│       │   ├── project-request.blade.php
│       │   ├── questionnaire.blade.php
│       │   ├── subscription-billing.blade.php
│       │   ├── subscription-checkout.blade.php
│       │   ├── subscription-receipt.blade.php
│       │   ├── survey.blade.php
│       │   ├── suspended.blade.php
│       │   ├── two-factor.blade.php
│       │   └── website-type.blade.php
│       ├── consultation.blade.php
│       ├── home.blade.php
│       └── welcome.blade.php
├── routes/
│   ├── console.php
│   └── web.php
├── specs/
│   ├── GAP/
│   │   ├── CARE_PLAN_TIER_CHANGE.md
│   │   ├── COUPON_PROMO_CODE_SUPPORT.md
│   │   └── MULTI_PROJECT_SUPPORT.md
│   ├── LOGIN/
│   │   └── LOGIN_FLOW.md
│   ├── PAYMENT/
│   │   └── PAYMENT_FLOW.md
│   ├── structure/
│   │   └── VisionBridgeSolutions_code_structure.md
│   ├── AGREEMENT-PDF-FILLING.md
│   ├── AI_ASSISTANT_KNOWLEDGE_BASE.md
│   ├── ARTISAN_COMMANDS.md
│   ├── CARE_PLAN_SUBSCRIPTION_FLOW.md
│   ├── INTERACTIVE_PRODUCT_TOUR.md
│   ├── PORTAL_ANNOUNCEMENTS.md
│   ├── PORTAL_GLOBAL_SEARCH.md
│   ├── POST_LAUNCH_SATISFACTION_SURVEY.md
│   ├── SELF_SERVICE_REFUND_REQUEST.md
│   └── TWO_FACTOR_AUTHENTICATION.md
├── storage/
│   ├── app/
│   │   ├── private/
│   │   │   ├── .gitignore
│   │   │   └── CLIENT WEBSITE DEVELOPMENT & WEBSITE CARE PLAN MASTER AGREEMENT-OFFICIAL 6.30.pdf
│   │   ├── public/
│   │   │   └── .gitignore
│   │   └── .gitignore
│   ├── framework/
│   │   ├── cache/
│   │   │   ├── data/
│   │   │   │   └── .gitignore
│   │   │   └── .gitignore
│   │   ├── sessions/
│   │   │   ├── .gitignore
│   │   │   └── UWQURp7eUe3OUmtJG0swLxgpeR1cOrmVxETAYGj5
│   │   ├── testing/
│   │   │   └── .gitignore
│   │   ├── views/
│   │   │   └── .gitignore
│   │   └── .gitignore
│   └── logs/
│       ├── .gitignore
│       └── laravel.log
├── tests/
│   ├── Feature/
│   │   └── ExampleTest.php
│   ├── Unit/
│   │   └── ExampleTest.php
│   └── TestCase.php
├── .dockerignore
├── .editorconfig
├── .env
├── .env.example
├── .gitattributes
├── .gitignore
├── artisan
├── build-set-up.txt
├── claude.md
├── composer.json
├── composer.lock
├── DEPLOYER-SETUP.md
├── docker-compose.yml
├── Dockerfile
├── docket-setup-inside-my-terminal.txt
├── FEATURES.md
├── Maintenance_Plan.txt
├── package.json
├── Phase2.txt
├── phpunit.xml
├── postcss.config.js
├── prompt.txt
├── README.md
├── stripe-webhook-setup.txt
├── tailwind.config.js
├── TODOS.txt
├── USER_GUIDE.md
├── VisionBridgeSolutions_tree.md
└── vite.config.js
```

---
*Generated with [tree2guide](https://github.com/law4percent/tree2guide) by Lawrence Roble ([@law4percent](https://github.com/law4percent)) — open source, MIT licensed. Contributions welcome!*
