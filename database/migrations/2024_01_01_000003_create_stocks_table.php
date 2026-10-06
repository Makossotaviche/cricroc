<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('idstock');
            $table->decimal('quantite_disponible', 10, 2)->default(0);
            $table->decimal('seuil_alerte', 10, 2)->default(10);
            $table->unsignedBigInteger('idproduit');
            $table->foreign('idproduit')->references('idproduit')->on('produits')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['entree', 'sortie']);
            $table->decimal('quantite', 10, 2);
            $table->string('motif')->nullable(); // production, vente, approvisionnement
            $table->unsignedBigInteger('idstock');
            $table->foreign('idstock')->references('idstock')->on('stocks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
        Schema::dropIfExists('stocks');
    }
};
