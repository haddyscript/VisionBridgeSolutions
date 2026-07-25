<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            // One reaction slot per message, not a per-user pivot table —
            // there are only ever two viewers of a project's chat (the
            // client, and the team collectively), same reasoning already
            // used for hidden_for_client_at/hidden_for_admin_at. Either
            // side can react to any message (their own or the other's);
            // whoever reacts most recently is the one shown.
            $table->string('reaction', 8)->nullable()->after('hidden_for_admin_at');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn('reaction');
        });
    }
};
