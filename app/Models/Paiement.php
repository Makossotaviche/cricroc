<?php

/**
 * Fichier : app/Models/Paiement.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $primaryKey = 'idpaiement';

    protected $fillable = [
        'montant',
        'date_paiement',
        'mode',
        'idfacture',
    ];

    protected $casts = [
        'date_paiement' => 'date',
    ];

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class, 'idfacture');
    }
}
