<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // IP → country lookup table, filled by `geoip:update` from DB-IP's free
        // country database. Addresses stored as inet_pton() bytes (4 for IPv4,
        // 16 for IPv6), so a byte-wise comparison orders them correctly.
        Schema::create('ip_country_ranges', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_v4');
            $table->binary('ip_from', 16);
            $table->binary('ip_to', 16);
            $table->char('country', 2);
            $table->index(['is_v4', 'ip_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_country_ranges');
    }
};
