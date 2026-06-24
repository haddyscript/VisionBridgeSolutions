<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        foreach (DB::table('uploads')->whereNotNull('admin_reply')->get() as $upload) {
            DB::table('upload_replies')->insert([
                'upload_id' => $upload->id,
                'body' => $upload->admin_reply,
                'created_at' => $upload->admin_replied_at ?? now(),
                'updated_at' => $upload->admin_replied_at ?? now(),
            ]);
        }

        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn(['admin_reply', 'admin_replied_at']);
        });
    }

    public function down(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->text('admin_reply')->nullable();
            $table->timestamp('admin_replied_at')->nullable();
        });

        Schema::dropIfExists('upload_replies');
    }
};
