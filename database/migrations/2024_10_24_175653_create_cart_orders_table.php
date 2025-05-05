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
        Schema::create('cart_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('product_size');
            $table->string('quantity');
            $table->string('unit_price');
            $table->string('total_price');
            $table->string('email');
            $table->string('payment_method');
            $table->text('delivery_address');
            $table->string('city');
            $table->string('delivery_charge');
            $table->string('order_date');
            $table->string('order_time');
            $table->string('order_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_orders');
    }
};
