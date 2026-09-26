<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Raw per-page-view log for the public website. Pruned after
        // SiteVisit::RETENTION_DAYS by `visits:rollup` — the monthly totals
        // below are what survive long-term.
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 40)->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('browser', 40)->nullable();
            $table->string('platform', 40)->nullable();
            $table->string('device', 20)->nullable();
            $table->string('path', 255);
            $table->string('referrer_host', 255)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->index();
        });

        // One permanent row per calendar month, kept forever even after the
        // raw site_visits rows for that month are pruned.
        Schema::create('site_visit_monthly_stats', function (Blueprint $table) {
            $table->id();
            $table->date('month')->unique();
            $table->unsignedInteger('page_views')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->json('browsers')->nullable();
            $table->json('devices')->nullable();
            $table->json('top_pages')->nullable();
            $table->json('top_referrers')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visit_monthly_stats');
        Schema::dropIfExists('site_visits');
    }
};
