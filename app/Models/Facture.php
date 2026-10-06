<?php

/**
 * Fichier : app/Models/Facture.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Facture extends Model
{
    protected $primaryKey = 'idfacture';

    protected $fillable = [
        'date_facture',
        'montant_total',
        'idcommande',
    ];

    protected $casts = [
        'date_facture' => 'date',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'idcommande');
    }

    public function paiement(): HasOne
    {
        return $this->hasOne(Paiement::class, 'idfacture');
    }

    public function estPayee(): bool
    {
        return $this->paiement()->exists();
    }
}
