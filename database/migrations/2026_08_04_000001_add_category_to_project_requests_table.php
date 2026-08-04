<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lets an admin manually tag a ProjectRequest as a plain "Request" or a
     * formal "Proposal" (independent of proposal_status, which only tracks
     * the sales pipeline once a proposal exists) so the two can be told apart
     * and filtered on the index page.
     */
    public function up(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->string('category')->default('request')->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
