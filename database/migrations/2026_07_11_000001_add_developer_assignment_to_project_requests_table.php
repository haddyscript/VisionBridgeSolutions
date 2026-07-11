<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->foreignId('assigned_developer_id')->nullable()->after('admin_notes')->constrained('users')->nullOnDelete();
            $table->string('developer_status')->nullable()->after('assigned_developer_id');
        });
    }

    public function down(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_developer_id');
            $table->dropColumn('developer_status');
        });
    }
};
