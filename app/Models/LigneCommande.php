<?php

/**
 * Fichier : app/Models/LigneCommande.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneCommande extends Model
{
    protected $table = 'lignes_commande';
    protected $primaryKey = 'idligne';

    protected $fillable = [
        'quantite',
        'prix_unitaire',
        'idcommande',
        'idproduit',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'idcommande');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'idproduit');
    }

    public function getSousTotalAttribute(): float
    {
        return $this->quantite * $this->prix_unitaire;
    }
}
