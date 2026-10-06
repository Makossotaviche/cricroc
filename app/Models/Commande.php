<?php

/**
 * Fichier : app/Models/Commande.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    protected $primaryKey = 'idcommande';

    protected $fillable = [
        'date_commande',
        'idclient',
        'statut',
        'montant_total',
    ];

    protected $casts = [
        'date_commande' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'idclient');
    }


    public function lignes(): HasMany
    {
        return $this->hasMany(LigneCommande::class, 'idcommande');
    }


    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class, 'idcommande');
    }

    public function livraison(): HasOne
    {
        return $this->hasOne(Livraison::class, 'idcommande');
    }

    /**
     * Recalcule et sauvegarde le montant total de la commande.
     */
    public function recalculerTotal(): void
    {
        $this->montant_total = $this->lignes->sum(fn($l) => $l->quantite * $l->prix_unitaire);
        $this->save();
    }

    /**
     * Valide la commande et génère automatiquement la facture associée.
     */
    public function valider(): Facture
    {
        $this->statut = 'validee';
        $this->save();

        return $this->facture()->firstOrCreate([
            'date_facture'  => now()->toDateString(),
            'montant_total' => $this->montant_total,
        ]);
    }

    public function getStatutLibelleAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'validee'    => 'Validée',
            'rejetee'    => 'Rejetée',
            default      => $this->statut,
        };
    }

    public function getStatutClassAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'warning',
            'validee'    => 'success',
            'rejetee'    => 'danger',
            default      => 'secondary',
        };
    }
}
