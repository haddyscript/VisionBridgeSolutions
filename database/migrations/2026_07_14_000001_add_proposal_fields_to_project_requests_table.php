<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->string('proposal_status')->nullable()->after('status');
            $table->unsignedInteger('estimated_value')->nullable()->after('proposal_status');
            $table->foreignId('recommended_care_plan_id')->nullable()->after('estimated_value')->constrained('maintenance_plans')->nullOnDelete();
            $table->string('proposal_document_path')->nullable()->after('recommended_care_plan_id');
            $table->string('proposal_document_original_name')->nullable()->after('proposal_document_path');
        });
    }

    public function down(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recommended_care_plan_id');
            $table->dropColumn(['proposal_status', 'estimated_value', 'proposal_document_path', 'proposal_document_original_name']);
        });
    }
};
