<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('produits')
            ->where('type', 'produit_fini')
            ->update(['unite' => 'ballot']);
    }

    public function down(): void
    {
        // The previous units differed between products, so they cannot be restored here.
    }
};
