<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id('idproduction');
            $table->date('date_production');
            $table->unsignedInteger('quantite_produite');
            $table->unsignedBigInteger('idproduit'); // produit fini fabriqué
            $table->foreign('idproduit')->references('idproduit')->on('produits')->onDelete('cascade');
            $table->timestamps();
        });

        // Matières premières consommées lors d'une production
        Schema::create('consommations_production', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idproduction');
            $table->unsignedBigInteger('idproduit'); // matière première consommée
            $table->unsignedInteger('quantite_consommee');
            $table->foreign('idproduction')->references('idproduction')->on('productions')->onDelete('cascade');
            $table->foreign('idproduit')->references('idproduit')->on('produits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consommations_production');
        Schema::dropIfExists('productions');
    }
};
