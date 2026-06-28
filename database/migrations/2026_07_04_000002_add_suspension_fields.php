<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tracks when a recurring Care Plan subscription first went past_due
     * (so a grace period can be measured from it) and when a project was
     * suspended for non-payment (so the portal can block access until the
     * outstanding balance is cleared and Stripe confirms the plan is active
     * again).
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('past_due_at')->nullable()->after('status');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->timestamp('suspended_at')->nullable()->after('client_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('past_due_at');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('suspended_at');
        });
    }
};
