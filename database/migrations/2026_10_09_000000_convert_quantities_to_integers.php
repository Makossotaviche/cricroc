<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedInteger('quantite_disponible')->default(0)->change();
            $table->unsignedInteger('seuil_alerte')->default(10)->change();
        });

        Schema::table('mouvements_stock', function (Blueprint $table) {
            $table->unsignedInteger('quantite')->change();
        });

        Schema::table('lignes_commande', function (Blueprint $table) {
            $table->unsignedInteger('quantite')->change();
        });

        Schema::table('productions', function (Blueprint $table) {
            $table->unsignedInteger('quantite_produite')->change();
        });

        Schema::table('consommations_production', function (Blueprint $table) {
            $table->unsignedInteger('quantite_consommee')->change();
        });
    }

    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('quantite_disponible', 10, 2)->default(0)->change();
            $table->decimal('seuil_alerte', 10, 2)->default(10)->change();
        });

        Schema::table('mouvements_stock', function (Blueprint $table) {
            $table->decimal('quantite', 10, 2)->change();
        });

        Schema::table('lignes_commande', function (Blueprint $table) {
            $table->decimal('quantite', 10, 2)->change();
        });

        Schema::table('productions', function (Blueprint $table) {
            $table->decimal('quantite_produite', 10, 2)->change();
        });

        Schema::table('consommations_production', function (Blueprint $table) {
            $table->decimal('quantite_consommee', 10, 2)->change();
        });
    }
};
