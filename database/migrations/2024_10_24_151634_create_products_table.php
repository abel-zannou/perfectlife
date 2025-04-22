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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departement_id')->constrained()->cascadeOnDelete();
            // $table->string('departement_name');
            $table->string('product_name');
            $table->string('product_image');
            $table->string('price');
            $table->string('special_price');
            $table->string('product_code');
            $table->string('remark');
            $table->string('brand');
            $table->string('star');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
