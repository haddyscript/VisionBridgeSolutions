<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replaces the single receipt_path column with a one-to-many table so a
     * payout can carry multiple proof-of-payment files (e.g. a bank transfer
     * screenshot + a GCash confirmation) instead of just one. Existing
     * receipt_path values are carried over before the column is dropped, so
     * no already-uploaded receipt is lost.
     */
    public function up(): void
    {
        Schema::create('partner_payout_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_payout_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->timestamps();
        });

        DB::table('partner_payouts')->whereNotNull('receipt_path')->get()->each(function ($payout) {
            DB::table('partner_payout_receipts')->insert([
                'partner_payout_id' => $payout->id,
                'path' => $payout->receipt_path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->dropColumn('receipt_path');
        });
    }

    public function down(): void
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->string('receipt_path')->nullable()->after('notes');
        });

        DB::table('partner_payout_receipts')->orderBy('id')->get()->each(function ($receipt) {
            DB::table('partner_payouts')->where('id', $receipt->partner_payout_id)->update([
                'receipt_path' => $receipt->path,
            ]);
        });

        Schema::dropIfExists('partner_payout_receipts');
    }
};
