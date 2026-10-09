<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->string('numero_facture')->nullable()->unique()->after('idfacture');
            $table->decimal('taux_tva', 5, 2)->default(0)->after('montant_total');
            $table->decimal('montant_ht', 10, 2)->nullable()->after('taux_tva');
            $table->decimal('montant_tva', 10, 2)->default(0)->after('montant_ht');
        });

        foreach (DB::table('factures')->orderBy('idfacture')->get() as $facture) {
            DB::table('factures')->where('idfacture', $facture->idfacture)->update([
                'numero_facture' => 'FAC-' . date('Y', strtotime($facture->date_facture)) . '-' . str_pad((string) $facture->idfacture, 6, '0', STR_PAD_LEFT),
                'montant_ht' => $facture->montant_total,
                'montant_tva' => 0,
                'taux_tva' => 0,
            ]);
        }

        Schema::create('parametres_facturation', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->decimal('taux_tva', 5, 2)->default(0);
            $table->timestamps();
        });

        DB::table('parametres_facturation')->insert([
            'id' => 1,
            'taux_tva' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres_facturation');

        Schema::table('factures', function (Blueprint $table) {
            $table->dropUnique(['numero_facture']);
            $table->dropColumn(['numero_facture', 'taux_tva', 'montant_ht', 'montant_tva']);
        });
    }
};
