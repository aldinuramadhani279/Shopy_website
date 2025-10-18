<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled']);
            $table->enum('payment_method', ['bank_transfer', 'cod', 'ewallet']);
            $table->enum('payment_status', ['unpaid', 'paid', 'failed']);
            $table->decimal('total_amount', 15, 2);
            $table->json('shipping_address');
            $table->text('notes')->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};