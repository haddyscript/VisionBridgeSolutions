<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Which audiences see this announcement — any of: client, team, developer.
            $table->json('audiences')->nullable()->after('body');
        });

        // Existing announcements were global — keep them visible to everyone.
        DB::table('announcements')->update([
            'audiences' => json_encode(['client', 'team', 'developer']),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('audiences');
        });
    }
};
