<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Data-only migration — the `status` column stays a plain string (no
     * doctrine/dbal installed, so we can't alter its DB-level default).
     * The new default ('request_received') is applied in App\Models\Upload's
     * creating() hook instead.
     */
    public function up(): void
    {
        DB::table('uploads')->where('status', 'open')->update(['status' => 'request_received']);
        DB::table('uploads')->where('status', 'addressed')->update(['status' => 'completed']);
    }

    public function down(): void
    {
        DB::table('uploads')->where('status', 'request_received')->update(['status' => 'open']);
        DB::table('uploads')->where('status', 'completed')->update(['status' => 'addressed']);
        DB::table('uploads')->whereIn('status', ['under_review', 'waiting_on_client', 'needs_approval'])->update(['status' => 'open']);
    }
};
