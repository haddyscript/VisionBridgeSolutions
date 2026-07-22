<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->text('completion_note')->nullable()->after('closed_reason');
        });
    }

    public function down(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn('completion_note');
        });
    }
};
