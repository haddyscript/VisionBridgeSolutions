<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Website Care Plans are now optional. A care_plan_agreements row with a
 * NULL maintenance_plan_id records that the client chose "No Care Plan —
 * pay per revision" during onboarding (see Project::hasDeclinedCarePlan()).
 * Additive only — existing rows keep their plan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('care_plan_agreements', function (Blueprint $table) {
            $table->foreignId('maintenance_plan_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('care_plan_agreements', function (Blueprint $table) {
            $table->foreignId('maintenance_plan_id')->nullable(false)->change();
        });
    }
};
