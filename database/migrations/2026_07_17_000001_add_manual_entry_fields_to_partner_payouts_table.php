<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Supports payouts with no payable at all (a direct one-time fee like the
     * original VisionBridge build, not tied to any client Payment/Subscription)
     * plus a receipt file for manual/offline transfers (bank, GCash) that never
     * generate a Stripe receipt.
     */
    public function up(): void
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->string('description')->nullable()->after('payable_id');
            $table->string('receipt_path')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->dropColumn(['description', 'receipt_path']);
        });
    }
};
