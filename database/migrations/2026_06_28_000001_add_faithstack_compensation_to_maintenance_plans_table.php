<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_plans', function (Blueprint $table) {
            // What VisionBridge pays FaithStack per billing cycle for this plan tier — see partnership agreement.
            $table->unsignedInteger('faithstack_compensation')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_plans', function (Blueprint $table) {
            $table->dropColumn('faithstack_compensation');
        });
    }
};
