<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Records that a client picked and agreed to a Website Care Plan — now
     * required before they can even sign the Service Agreement (a Care Plan
     * is mandatory for every project VisionBridge builds). The plan itself
     * doesn't get billed yet; this just creates a pending Subscription that
     * stays dormant until the project launches.
     */
    public function up(): void
    {
        Schema::create('care_plan_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('maintenance_plan_id')->constrained();
            $table->timestamp('agreed_at');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('care_plan_agreements');
    }
};
