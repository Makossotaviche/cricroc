<?php

/**
 * Fichier : app/Providers/EventServiceProvider.php
 * Description : Service provider pour enregistrer les événements et listeners.
 */

namespace App\Providers;

use App\Events\StockAdded;
use App\Events\ProductionCreated;
use App\Listeners\SendStockNotification;
use App\Listeners\SendProductionNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Les événements et leurs listeners correspondants.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        StockAdded::class => [
            SendStockNotification::class,
        ],
        ProductionCreated::class => [
            SendProductionNotification::class,
        ],
    ];

    /**
     * Enregistrer les événements pour l'application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Déterminer si les événements et listeners doivent être découverts automatiquement.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
