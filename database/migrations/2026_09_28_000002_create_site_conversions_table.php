<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_conversions', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 40)->nullable()->index();
            $table->string('type', 40)->index();
            $table->nullableMorphs('subject');
            $table->string('landing_page', 250)->nullable();
            $table->string('last_page', 250)->nullable();
            $table->string('source', 250)->nullable();
            $table->string('campaign', 150)->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_conversions');
    }
};
