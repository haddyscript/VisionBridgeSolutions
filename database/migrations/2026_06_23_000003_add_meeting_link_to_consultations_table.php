<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->string('meeting_link')->nullable()->after('admin_notes');
            $table->timestamp('confirmation_sent_at')->nullable()->after('meeting_link');
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['meeting_link', 'confirmation_sent_at']);
        });
    }
};
