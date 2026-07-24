<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->timestamp('edited_at')->nullable()->after('body');
            // "Deleted for everyone" — a tombstone. The original body stays in
            // the row (matches this app's general habit of never truly
            // destroying records — see support tickets/refund requests), but
            // every view must check this before ever rendering `body`.
            $table->timestamp('deleted_at')->nullable()->after('edited_at');
            // "Deleted for me" — hides the row from one side's own thread
            // only. There are only ever two viewers of a project's chat (the
            // client, and the team collectively), so two nullable timestamps
            // are enough rather than a per-user pivot table.
            $table->timestamp('hidden_for_client_at')->nullable()->after('deleted_at');
            $table->timestamp('hidden_for_admin_at')->nullable()->after('hidden_for_client_at');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['edited_at', 'deleted_at', 'hidden_for_client_at', 'hidden_for_admin_at']);
        });
    }
};
