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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('departement_id')->constrained('departements')->onDelete('cascade');
            $table->integer('initial_quantity')->default(0); // Quantité initiale
            $table->integer('quantity')->default(0);
            $table->string('status')->default('active'); // Statut du stock
            $table->foreignId('created_by')->nullable()->constrained('users'); // Utilisateur qui a créé
            $table->foreignId('updated_by')->nullable()->constrained('users'); // Utilisateur qui a mis à jour
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
