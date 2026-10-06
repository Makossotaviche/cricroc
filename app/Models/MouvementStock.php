<?php

/**
 * Fichier : app/Models/MouvementStock.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    protected $fillable = [
        'type',
        'quantite',
        'motif',
        'idstock',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'idstock');
    }
}
