<?php

/**
 * Fichier : app/Models/Livraison.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Livraison extends Model
{
    protected $primaryKey = 'idlivraison';

    protected $fillable = [
        'date_livraison',
        'statut',
        'idcommande',
        'idlivreur',
    ];

    protected $casts = [
        'date_livraison' => 'date',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'idcommande');
    }

    public function livreur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idlivreur');
    }

    public function getStatutLibelleAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'livree'     => 'Livrée',
            default      => $this->statut,
        };
    }

    public function getStatutClassAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'secondary',
            'en_cours'   => 'warning',
            'livree'     => 'success',
            default      => 'secondary',
        };
    }
}
