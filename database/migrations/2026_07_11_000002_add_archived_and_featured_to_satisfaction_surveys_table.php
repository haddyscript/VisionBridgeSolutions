<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('satisfaction_surveys', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('feedback');
            $table->timestamp('featured_at')->nullable()->after('archived_at');
        });
    }

    public function down(): void
    {
        Schema::table('satisfaction_surveys', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'featured_at']);
        });
    }
};
