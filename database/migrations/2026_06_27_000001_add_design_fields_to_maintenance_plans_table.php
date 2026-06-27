<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_plans', function (Blueprint $table) {
            $table->string('tagline')->nullable()->after('name');
            $table->string('description')->nullable()->after('tagline');
            $table->string('icon')->nullable()->after('badge');
            $table->string('response_time')->nullable()->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_plans', function (Blueprint $table) {
            $table->dropColumn(['tagline', 'description', 'icon', 'response_time']);
        });
    }
};
