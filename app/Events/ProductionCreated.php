<?php

/**
 * Fichier : app/Events/ProductionCreated.php
 * Description : Événement déclenché quand une production est créée.
 */

namespace App\Events;

use App\Models\Production;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductionCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Production $production
    ) {}
}
