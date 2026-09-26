<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per public "Free Website Check" run. It only becomes a
        // lead once the visitor adds their name + email to get the full
        // report (email not null).
        Schema::create('website_checks', function (Blueprint $table) {
            $table->id();
            $table->string('token', 40)->unique();
            $table->string('url', 500);
            $table->string('final_url', 500)->nullable();
            $table->unsignedTinyInteger('score');
            $table->json('checks');
            $table->string('name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('organization')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('status', 20)->default('new');
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('report_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_checks');
    }
};
