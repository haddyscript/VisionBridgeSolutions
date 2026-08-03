<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Set by the onboarding Care Plan payment-method step (SetupIntent,
            // usage: off_session) before any Stripe Subscription exists — kept
            // separate from stripe_subscription_id, which is only set once the
            // subscription is actually created and billing starts.
            $table->string('stripe_payment_method_id')->nullable()->after('stripe_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('stripe_payment_method_id');
        });
    }
};
