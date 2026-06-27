<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Generalizes the Care-Plan-only payout ledger into one that also covers
     * one-time project payments (the 50/50 deposit split) — every client
     * payment now gets a FaithStack payout row logged, even before the
     * one-time-payment compensation amount is decided (faithstack_amount
     * just stays null/"TBD" until then).
     */
    public function up(): void
    {
        Schema::rename('subscription_payouts', 'partner_payouts');

        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->string('payable_type')->nullable()->after('id');
            $table->unsignedBigInteger('payable_id')->nullable()->after('payable_type');
            $table->string('stripe_payment_intent_id')->nullable()->unique()->after('stripe_invoice_id');
        });

        DB::table('partner_payouts')->whereNotNull('subscription_id')->update([
            'payable_type' => 'App\\Models\\Subscription',
            'payable_id' => DB::raw('subscription_id'),
        ]);

        Schema::table('partner_payouts', function (Blueprint $table) {
            // MySQL doesn't rename constraints when the table itself is renamed,
            // so this is still named after the OLD table — dropForeign(['subscription_id'])
            // would instead guess 'partner_payouts_subscription_id_foreign' and fail.
            $table->dropForeign('subscription_payouts_subscription_id_foreign');
            $table->dropColumn('subscription_id');
            $table->index(['payable_type', 'payable_id']);
        });

        // faithstack_amount must allow null now (one-time payment compensation
        // isn't decided yet) — no doctrine/dbal installed, so raw SQL for MySQL.
        DB::statement('ALTER TABLE partner_payouts MODIFY faithstack_amount INT UNSIGNED NULL');
    }

    public function down(): void
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->foreignId('subscription_id')->nullable()->after('payable_id')->constrained()->cascadeOnDelete();
        });

        DB::table('partner_payouts')->where('payable_type', 'App\\Models\\Subscription')->update([
            'subscription_id' => DB::raw('payable_id'),
        ]);

        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->dropColumn(['payable_type', 'payable_id', 'stripe_payment_intent_id']);
        });

        DB::statement('ALTER TABLE partner_payouts MODIFY faithstack_amount INT UNSIGNED NOT NULL');

        Schema::rename('partner_payouts', 'subscription_payouts');
    }
};
