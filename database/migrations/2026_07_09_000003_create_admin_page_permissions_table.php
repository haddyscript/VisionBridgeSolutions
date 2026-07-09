<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('restricted_access')->default(false)->after('is_super_admin');
        });

        Schema::create('admin_page_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('permission_key');
            $table->timestamps();

            $table->unique(['user_id', 'permission_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_page_permissions');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('restricted_access');
        });
    }
};
