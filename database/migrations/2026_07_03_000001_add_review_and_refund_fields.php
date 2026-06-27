<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The 7-day client review window: starts when a project's status moves
     * into 'review', ends when the client approves (triggering the final
     * 50% invoice) or cancels within the window (triggering a refund of the
     * deposit, minus Stripe's processing fee).
     */
    public function up(): void
    {
        // No doctrine/dbal installed, so raw SQL for the enum change (same
        // approach used in the earlier subscription_payouts/payments enum migrations).
        DB::statement("ALTER TABLE projects MODIFY status ENUM('onboarding', 'in_progress', 'review', 'launched', 'maintenance', 'canceled') NOT NULL DEFAULT 'onboarding'");
        DB::statement("ALTER TABLE payments MODIFY status ENUM('pending', 'paid', 'failed', 'canceled', 'refunded') NOT NULL DEFAULT 'pending'");

        Schema::table('projects', function (Blueprint $table) {
            $table->timestamp('review_started_at')->nullable()->after('status');
            $table->timestamp('client_approved_at')->nullable()->after('review_started_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedInteger('refunded_amount')->nullable()->after('paid_at');
            $table->timestamp('refunded_at')->nullable()->after('refunded_amount');
            $table->string('stripe_refund_id')->nullable()->after('refunded_at');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['review_started_at', 'client_approved_at']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['refunded_amount', 'refunded_at', 'stripe_refund_id']);
        });

        DB::statement("ALTER TABLE projects MODIFY status ENUM('onboarding', 'in_progress', 'review', 'launched', 'maintenance') NOT NULL DEFAULT 'onboarding'");
        DB::statement("ALTER TABLE payments MODIFY status ENUM('pending', 'paid', 'failed', 'canceled') NOT NULL DEFAULT 'pending'");
    }
};
