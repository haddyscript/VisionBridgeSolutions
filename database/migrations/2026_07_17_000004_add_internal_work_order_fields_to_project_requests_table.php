<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lets an admin create a ProjectRequest directly (an internal work order
     * like research/feasibility work) instead of only ever being submitted by
     * a client through the portal. created_by_admin_id is null for every
     * client-submitted request and set for admin-created ones, so the two
     * can be told apart in the UI without a separate table.
     */
    public function up(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->string('priority')->nullable()->after('description');
            $table->date('due_date')->nullable()->after('priority');
            $table->foreignId('created_by_admin_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by_admin_id');
            $table->dropColumn(['priority', 'due_date']);
        });
    }
};
