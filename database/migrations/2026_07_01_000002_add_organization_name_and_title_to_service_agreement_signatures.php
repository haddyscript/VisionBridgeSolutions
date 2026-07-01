<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_agreement_signatures', function (Blueprint $table) {
            $table->string('organization_name')->nullable()->after('signer_name');
            $table->string('title')->nullable()->after('organization_name');
        });
    }

    public function down(): void
    {
        Schema::table('service_agreement_signatures', function (Blueprint $table) {
            $table->dropColumn(['organization_name', 'title']);
        });
    }
};
