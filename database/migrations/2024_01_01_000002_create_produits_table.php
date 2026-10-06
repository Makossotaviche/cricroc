<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id('idproduit');
            $table->string('nom');
            $table->enum('type', ['matiere_premiere', 'produit_fini', 'emballage']);
            $table->decimal('prix_unitaire', 10, 2)->default(0);
            $table->string('unite')->default('unité'); // kg, litre, unité, rouleau...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
