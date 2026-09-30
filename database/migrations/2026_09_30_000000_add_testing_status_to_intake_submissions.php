<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE intake_submissions MODIFY status ENUM('new','contacted','converted','reviewing','follow_up','proposal_sent','negotiating','approved','on_hold','not_interested','lost','testing') NOT NULL DEFAULT 'new'");
    }

    public function down(): void
    {
        DB::table('intake_submissions')->where('status', 'testing')->update(['status' => 'new']);

        DB::statement("ALTER TABLE intake_submissions MODIFY status ENUM('new','contacted','converted','reviewing','follow_up','proposal_sent','negotiating','approved','on_hold','not_interested','lost') NOT NULL DEFAULT 'new'");
    }
};
