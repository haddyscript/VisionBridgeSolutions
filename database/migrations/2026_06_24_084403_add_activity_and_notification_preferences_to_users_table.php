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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('activity_last_read_at')->nullable();
            $table->boolean('notify_on_replies')->default(true);
            $table->boolean('notify_on_consultations')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['activity_last_read_at', 'notify_on_replies', 'notify_on_consultations']);
        });
    }
};
