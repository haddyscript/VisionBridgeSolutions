<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Purely descriptive/organizational — deliberately separate from
            // `kind` ('deposit'/'final'), which drives real automated
            // behavior (StripeWebhookController::maybeAutoLaunchProject).
            // A category label should never be able to accidentally trigger
            // that logic.
            $table->string('category')->nullable()->after('kind');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
