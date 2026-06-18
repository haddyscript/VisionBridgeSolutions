<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->unsignedInteger('amount');
            $table->string('currency', 3)->default('usd');
            $table->string('interval')->default('month');
            $table->enum('status', ['pending', 'active', 'past_due', 'canceled'])->default('pending');
            $table->string('stripe_checkout_session_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
