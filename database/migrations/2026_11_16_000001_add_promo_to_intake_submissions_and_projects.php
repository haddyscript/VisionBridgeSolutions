<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * $149 Limited-Time Landing Page promotion (2026-11-14 work order) — records
 * which promo (if any) a lead came in on, and carries it onto the project so
 * the portal can show the promo package/price consistently. Additive and
 * nullable only: existing rows are untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intake_submissions', function (Blueprint $table) {
            $table->string('promo', 50)->nullable()->after('services');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('promo', 50)->nullable()->after('website_type');
        });
    }

    public function down(): void
    {
        Schema::table('intake_submissions', function (Blueprint $table) {
            $table->dropColumn('promo');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('promo');
        });
    }
};
