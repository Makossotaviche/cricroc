<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_internes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');          // destinataire
            $table->string('titre');
            $table->text('message');
            $table->string('icone')->default('bi-bell');    // icône Bootstrap Icons
            $table->string('couleur')->default('primary'); // primary, success, danger, warning
            $table->string('lien')->nullable();             // URL de redirection
            $table->boolean('lue')->default(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_internes');
    }
};
