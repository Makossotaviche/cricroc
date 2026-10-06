<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'numero_piece_identite')) {
                $table->string('numero_piece_identite')->nullable()->after('prenoms');
            }

            $columnsToDrop = array_filter([
                Schema::hasColumn('clients', 'raison_sociale') ? 'raison_sociale' : null,
                Schema::hasColumn('clients', 'categorie') ? 'categorie' : null,
                Schema::hasColumn('clients', 'limite_credit') ? 'limite_credit' : null,
                Schema::hasColumn('clients', 'condition_paiement') ? 'condition_paiement' : null,
                Schema::hasColumn('clients', 'remise_defaut') ? 'remise_defaut' : null,
                Schema::hasColumn('clients', 'notes') ? 'notes' : null,
            ]);

            if ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'numero_piece_identite')) {
                $table->dropColumn('numero_piece_identite');
            }

            if (! Schema::hasColumn('clients', 'raison_sociale')) {
                $table->string('raison_sociale')->nullable()->after('prenoms');
            }

            if (! Schema::hasColumn('clients', 'categorie')) {
                $table->string('categorie')->nullable()->after('commune');
            }

            if (! Schema::hasColumn('clients', 'limite_credit')) {
                $table->unsignedInteger('limite_credit')->nullable()->after('categorie');
            }

            if (! Schema::hasColumn('clients', 'condition_paiement')) {
                $table->string('condition_paiement')->nullable()->after('limite_credit');
            }

            if (! Schema::hasColumn('clients', 'remise_defaut')) {
                $table->unsignedTinyInteger('remise_defaut')->default(0)->after('condition_paiement');
            }

            if (! Schema::hasColumn('clients', 'notes')) {
                $table->text('notes')->nullable()->after('remise_defaut');
            }
        });
    }
};
