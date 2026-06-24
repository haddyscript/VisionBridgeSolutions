<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->string('status')->default('open')->after('approved_at');
        });

        DB::table('uploads')
            ->whereIn('category', ['content', 'revision'])
            ->whereNotNull('approved_at')
            ->update(['status' => 'addressed']);
    }

    public function down(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
