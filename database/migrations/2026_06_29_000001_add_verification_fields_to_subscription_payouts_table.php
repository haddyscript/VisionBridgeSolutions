<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds the 7-day hold workflow: a payout starts 'pending', auto-promotes
     * to 'ready' once 7 clean days pass (see VerifyCarePlanPayouts command),
     * or gets bumped to 'flagged' if Stripe reports a dispute/refund on the
     * underlying invoice during the window (see StripeWebhookController).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE subscription_payouts MODIFY status ENUM('pending', 'ready', 'flagged', 'paid') NOT NULL DEFAULT 'pending'");

        Schema::table('subscription_payouts', function (Blueprint $table) {
            $table->timestamp('ready_at')->nullable()->after('status');
            $table->timestamp('flagged_at')->nullable()->after('ready_at');
            $table->string('flag_reason')->nullable()->after('flagged_at');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_payouts', function (Blueprint $table) {
            $table->dropColumn(['ready_at', 'flagged_at', 'flag_reason']);
        });

        DB::statement("ALTER TABLE subscription_payouts MODIFY status ENUM('pending', 'paid') NOT NULL DEFAULT 'pending'");
    }
};
