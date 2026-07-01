<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('onboarding_step')->default(1)->after('welcomed_at');
        });

        // Backfill existing users based on how far through the old 3-step flow they got.
        // Admins stay at 1 (they skip onboarding entirely).
        User::where('role', '!=', 'admin')->each(function (User $user) {
            $project = $user->projects()->first();

            if (! $project) {
                return;
            }

            $step = 4; // default: questionnaire not yet done

            if ($project->hasAgreedToCarePlan()) {
                $step = 8; // care plan done → needs to sign agreement
            }

            if ($project->hasSignedCurrentAgreement()) {
                $step = 13; // fully through old flow → portal access
            }

            $user->update(['onboarding_step' => $step]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('onboarding_step');
        });
    }
};
