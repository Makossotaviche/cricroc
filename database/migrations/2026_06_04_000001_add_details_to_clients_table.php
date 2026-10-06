<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('type_client')->default('Particulier')->after('idclient');
            $table->string('prenoms')->nullable()->after('nom');
            $table->string('numero_piece_identite')->nullable()->after('prenoms');
            $table->string('code_client')->nullable()->unique()->after('numero_piece_identite');
            $table->date('date_enregistrement')->nullable()->after('code_client');
            $table->string('telephone_secondaire')->nullable()->after('contact');
            $table->string('email')->nullable()->after('telephone_secondaire');
            $table->string('whatsapp')->nullable()->after('email');
            $table->string('pays')->nullable()->after('adresse');
            $table->string('ville')->nullable()->after('pays');
            $table->string('commune')->nullable()->after('ville');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['code_client']);
            $table->dropColumn([
                'type_client',
                'prenoms',
                'numero_piece_identite',
                'code_client',
                'date_enregistrement',
                'telephone_secondaire',
                'email',
                'whatsapp',
                'pays',
                'ville',
                'commune',
            ]);
        });
    }
};
