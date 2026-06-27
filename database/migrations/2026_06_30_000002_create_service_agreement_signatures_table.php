<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per signed agreement. Stores enough evidence (drawn signature
     * image, IP, user agent, a hash of the exact agreement text) to stand
     * behind it later, plus the generated PDF that gets emailed out.
     */
    public function up(): void
    {
        Schema::create('service_agreement_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_agreement_template_id')->constrained();
            $table->string('signer_name');
            $table->string('signature_image_path');
            $table->string('agreement_hash', 64);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('signed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_agreement_signatures');
    }
};
