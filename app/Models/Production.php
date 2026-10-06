<?php

/**
 * Fichier : app/Models/Production.php
 * Description : Modèle Eloquent représentant une production. Ce modèle
 * stocke la quantité produite et la date de production pour un produit donné.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Production extends Model
{
    protected $primaryKey = 'idproduction';

    protected $fillable = [
        'date_production',
        'quantite_produite',
        'idproduit',
    ];

    protected $casts = [
        'date_production' => 'date',
    ];

    /**
     * Relation vers le produit fabriqué.
     *
     * Une production appartient à un seul produit.
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'idproduit');
    }

    /**
     * Relation vers les consommations de production.
     *
     * Une production peut consommer plusieurs matières premières ou
     * emballages via la table ConsommationProduction.
     */
    public function consommations(): HasMany
    {
        return $this->hasMany(ConsommationProduction::class, 'idproduction');
    }
}
