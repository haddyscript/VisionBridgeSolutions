<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // The quoted total project fee — setting this for the first time
            // auto-creates the initial 50% deposit payment request.
            $table->unsignedInteger('total_price')->nullable()->after('progress_override');
        });

        Schema::table('payments', function (Blueprint $table) {
            // Distinguishes the 50/50 split project payments from ad-hoc one-off
            // payment requests, so we can reliably tell "has the deposit/final
            // payment already been created" instead of matching on description text.
            $table->string('kind')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('total_price');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('kind');
        });
    }
};
