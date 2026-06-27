<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per billing cycle that VisionBridge owes FaithStack recurring
     * compensation for. Created automatically when a client's monthly Website
     * Care Plan payment clears; VisionBridge marks it paid manually after
     * sending FaithStack's cut (see partnership agreement — payouts are
     * intentionally manual for now, not an automated Stripe transfer).
     */
    public function up(): void
    {
        Schema::create('subscription_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->string('stripe_invoice_id')->nullable()->unique();
            $table->unsignedInteger('client_amount');
            $table->unsignedInteger('faithstack_amount');
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payouts');
    }
};
