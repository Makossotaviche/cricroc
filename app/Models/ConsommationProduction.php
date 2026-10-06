<?php

/**
 * Fichier : app/Models/ConsommationProduction.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsommationProduction extends Model
{
    protected $table = 'consommations_production';

    protected $fillable = [
        'idproduction',
        'idproduit',
        'quantite_consommee',
    ];

    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class, 'idproduction');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'idproduit');
    }
}
