<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_agreement_signatures', function (Blueprint $table) {
            $table->string('filled_pdf_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('service_agreement_signatures', function (Blueprint $table) {
            $table->dropColumn('filled_pdf_path');
        });
    }
};
