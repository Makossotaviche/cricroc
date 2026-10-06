<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id('idfacture');
            $table->date('date_facture');
            $table->decimal('montant_total', 10, 2);
            $table->unsignedBigInteger('idcommande');
            $table->foreign('idcommande')->references('idcommande')->on('commandes')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('paiements', function (Blueprint $table) {
            $table->id('idpaiement');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->enum('mode', ['especes', 'mobile_money', 'virement'])->default('especes');
            $table->unsignedBigInteger('idfacture');
            $table->foreign('idfacture')->references('idfacture')->on('factures')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
        Schema::dropIfExists('factures');
    }
};
