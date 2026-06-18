<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('organization_type')->nullable();
            $table->text('mission_statement')->nullable();
            $table->text('vision_statement')->nullable();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->json('services')->nullable();
            $table->text('website_requirements')->nullable();
            $table->json('social_links')->nullable();
            $table->enum('status', ['new', 'contacted', 'converted'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_submissions');
    }
};
