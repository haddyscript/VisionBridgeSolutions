<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Versioned so a signature always stays tied to the exact text the client
     * agreed to, even if the template is edited later.
     */
    public function up(): void
    {
        Schema::create('service_agreement_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('version');
            $table->string('title');
            $table->longText('body');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_agreement_templates');
    }
};
