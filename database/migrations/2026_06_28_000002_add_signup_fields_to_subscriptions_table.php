<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Set when this subscription came from the public Website Care Plan
            // self-checkout flow (as opposed to an admin manually creating one for
            // an already-onboarded client) — also drives which tier's features/
            // FaithStack compensation apply.
            $table->foreignId('maintenance_plan_id')->nullable()->after('project_id')
                ->constrained()->nullOnDelete();
            $table->string('client_phone')->nullable()->after('description');
            $table->string('domain')->nullable()->after('client_phone');
            $table->string('hosting_provider')->nullable()->after('domain');
            $table->text('notes')->nullable()->after('hosting_provider');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('maintenance_plan_id');
            $table->dropColumn(['client_phone', 'domain', 'hosting_provider', 'notes']);
        });
    }
};
