<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Free-text, admin-set banner shown to the client on their
            // Overview page (e.g. "Payment Received — your project is now
            // in development"). Deliberately manual rather than triggered
            // automatically by a payment/status event, so the admin decides
            // exactly when and what it says.
            $table->string('status_message')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('status_message');
        });
    }
};
