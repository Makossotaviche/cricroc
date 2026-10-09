<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id('idcommande');
            $table->date('date_commande');
            $table->unsignedBigInteger('idclient');
            $table->enum('statut', ['en_attente', 'validee', 'rejetee'])->default('en_attente');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->foreign('idclient')->references('idclient')->on('clients')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('lignes_commande', function (Blueprint $table) {
            $table->id('idligne');
            $table->unsignedInteger('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->unsignedBigInteger('idcommande');
            $table->unsignedBigInteger('idproduit');
            $table->foreign('idcommande')->references('idcommande')->on('commandes')->onDelete('cascade');
            $table->foreign('idproduit')->references('idproduit')->on('produits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_commande');
        Schema::dropIfExists('commandes');
    }
};
