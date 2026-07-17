<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tracks when (and by whom) an already-paid payout record was edited
     * after the fact — editing a settled financial record is restricted to
     * super admins, and this is the audit trail for that.
     */
    public function up(): void
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->timestamp('edited_at')->nullable()->after('paid_at');
            $table->foreignId('edited_by_id')->nullable()->after('edited_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('edited_by_id');
            $table->dropColumn('edited_at');
        });
    }
};
