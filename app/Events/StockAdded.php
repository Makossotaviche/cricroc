<?php

/**
 * Fichier : app/Events/StockAdded.php
 * Description : Événement déclenché quand une entrée de stock est enregistrée.
 */

namespace App\Events;

use App\Models\Stock;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAdded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Stock $stock,
        public float $quantite,
        public string $motif = ''
    ) {}
}
