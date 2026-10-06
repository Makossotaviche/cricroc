<?php

/**
 * Fichier : app/Models/Produit.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produit extends Model
{
    protected $primaryKey = 'idproduit';

    protected $fillable = [
        'nom',
        'type',
        'prix_unitaire',
        'unite',
    ];

    // Labels lisibles pour les types de produits.
    // Cela permet d'afficher des noms humains dans les vues et les rapports.
    public function getTypeLibelleAttribute(): string
    {
        return match($this->type) {
            'matiere_premiere'  => 'Matière Première',
            'produit_fini'      => 'Produit Fini',
            'emballage'         => 'Emballage',
            default             => $this->type,
        };
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class, 'idproduit');
    }

    public function lignesCommande(): HasMany
    {
        return $this->hasMany(LigneCommande::class, 'idproduit');
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class, 'idproduit');
    }
}
