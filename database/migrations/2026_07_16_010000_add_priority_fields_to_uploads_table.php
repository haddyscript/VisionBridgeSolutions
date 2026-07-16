<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->string('priority')->default('medium')->after('status');
            $table->date('estimated_completion_date')->nullable()->after('priority');
            $table->string('closed_reason', 1000)->nullable()->after('estimated_completion_date');
        });
    }

    public function down(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn(['priority', 'estimated_completion_date', 'closed_reason']);
        });
    }
};
