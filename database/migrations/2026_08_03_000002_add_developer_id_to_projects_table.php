<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Manually assigned by an admin whenever there's a go-ahead to
            // start work — not tied to any automated trigger. Nullable since
            // most projects sit unassigned until that happens.
            $table->foreignId('developer_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('developer_id');
        });
    }
};
