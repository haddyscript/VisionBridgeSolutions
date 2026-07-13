<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Optional metadata shown in the banner's header strip above the
            // formatted body — e.g. "VisionBridge Solutions & FaithStack".
            $table->text('subtitle')->nullable()->after('title');

            // Free-text time label (e.g. "9:00–10:00 PM (Philippine Time)")
            // rather than a strict time column, since it may span a range
            // and include a timezone note.
            $table->date('event_date')->nullable()->after('subtitle');
            $table->string('event_time', 100)->nullable()->after('event_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'event_date', 'event_time']);
        });
    }
};
