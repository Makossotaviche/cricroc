<?php

/**
 * Fichier : app/Models/Stock.php
 * Description : Modèle Eloquent représentant le stock d'un produit.
 * Ce modèle contient la quantité disponible, le seuil d'alerte et
 * les relations vers le produit associé et les mouvements de stock.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Events\StockAdded;

class Stock extends Model
{
    protected $primaryKey = 'idstock';

    protected $fillable = [
        'quantite_disponible',
        'seuil_alerte',
        'idproduit',
    ];

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'idproduit');
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementStock::class, 'idstock');
    }

    public function estEnRupture(): bool
    {
        return $this->quantite_disponible <= $this->seuil_alerte;
    }

    /**
     * Enregistre une entrée ou sortie et met à jour la quantité disponible.
     */
    public function enregistrerMouvement(string $type, float $quantite, string $motif = ''): MouvementStock
    {
        if ($type === 'sortie' && $this->quantite_disponible < $quantite) {
            throw new \Exception("Stock insuffisant pour le produit : {$this->produit->nom}");
        }

        $this->quantite_disponible = $type === 'entree'
            ? $this->quantite_disponible + $quantite
            : $this->quantite_disponible - $quantite;

        $this->save();

        $mouvement = $this->mouvements()->create([
            'type'     => $type,
            'quantite' => $quantite,
            'motif'    => $motif,
        ]);

        // Dispatcher une notification quand un stock entre
        if ($type === 'entree') {
            StockAdded::dispatch($this, $quantite, $motif);
        }

        return $mouvement;
    }
}
