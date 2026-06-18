<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->unsignedInteger('amount');
            $table->string('currency', 3)->default('usd');
            $table->enum('status', ['pending', 'paid', 'failed', 'canceled'])->default('pending');
            $table->string('stripe_checkout_session_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
