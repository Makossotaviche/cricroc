<?php

/**
 * Fichier : app/Models/Facture.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    protected $primaryKey = 'idfacture';

    protected $fillable = [
        'numero_facture',
        'date_facture',
        'montant_ht',
        'taux_tva',
        'montant_tva',
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

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'idfacture')->orderBy('date_paiement')->orderBy('idpaiement');
    }

    public function montantPaye(): float
    {
        return (float) ($this->relationLoaded('paiements')
            ? $this->paiements->sum('montant')
            : $this->paiements()->sum('montant'));
    }

    public function resteAPayer(): float
    {
        return max(0, (float) $this->montant_total - $this->montantPaye());
    }

    public function estPayee(): bool
    {
        return $this->montantPaye() >= (float) $this->montant_total;
    }
}
