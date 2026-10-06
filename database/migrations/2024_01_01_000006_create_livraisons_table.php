<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id('idlivraison');
            $table->date('date_livraison')->nullable();
            $table->enum('statut', ['en_attente', 'en_cours', 'livree'])->default('en_attente');
            $table->unsignedBigInteger('idcommande');
            $table->unsignedBigInteger('idlivreur')->nullable();
            $table->foreign('idcommande')->references('idcommande')->on('commandes')->onDelete('cascade');
            $table->foreign('idlivreur')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livraisons');
    }
};
