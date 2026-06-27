<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The in-portal onboarding questionnaire clients fill out after signing
     * the Service Agreement (replaces the old public /get-started form for
     * this flow). Logo/images/content uploads still go through the existing
     * Project Files feature — this table is just the structured answers.
     */
    public function up(): void
    {
        Schema::create('project_questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('organization_type')->nullable();
            $table->text('mission_statement')->nullable();
            $table->text('vision_statement')->nullable();
            $table->json('services')->nullable();
            $table->text('requested_pages')->nullable();
            $table->string('brand_colors')->nullable();
            $table->json('social_links')->nullable();
            $table->text('additional_notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_questionnaires');
    }
};
