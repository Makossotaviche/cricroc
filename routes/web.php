<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\LivraisonController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Routes protégées (utilisateur authentifié)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Trend data (JSON) pour graphique
    Route::get('/dashboard/trends/sales', [DashboardController::class, 'trendSales'])->name('dashboard.trends.sales');

    // Clients
    Route::resource('clients', ClientController::class);
    Route::get('/exports/clients', [ClientController::class, 'exportCsv'])->name('export.clients');

    // Produits
    Route::resource('produits', ProduitController::class);
    Route::get('/exports/produits', [ProduitController::class, 'exportCsv'])->name('export.produits');

    // Stock
    Route::get('/stock',                       [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/{stock}/mouvements',    [StockController::class, 'mouvements'])->name('stock.mouvements');
    Route::post('/stock/{stock}/entree',       [StockController::class, 'entree'])->name('stock.entree');
    Route::post('/stock/{stock}/sortie',       [StockController::class, 'sortie'])->name('stock.sortie');
    Route::patch('/stock/{stock}/seuil',       [StockController::class, 'updateSeuil'])->name('stock.seuil');
    Route::post('/stock/{stock}/inventaire',   [StockController::class, 'inventaire'])->name('stock.inventaire');
    Route::get('/exports/stock', [StockController::class, 'exportExcel'])->name('export.stock');
    Route::get('/exports/stock/mouvements', [StockController::class, 'exportMouvementsExcel'])->name('export.stock.mouvements');

    // Commandes
    Route::resource('commandes', CommandeController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/commandes/{commande}/valider', [CommandeController::class, 'valider'])->name('commandes.valider');
    Route::post('/commandes/{commande}/rejeter', [CommandeController::class, 'rejeter'])->name('commandes.rejeter');

    // Factures
    Route::get('/factures',                         [FactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/{facture}/pdf',            [FactureController::class, 'pdf'])->name('factures.pdf');
    Route::get('/factures/{facture}',               [FactureController::class, 'show'])->name('factures.show');
    Route::get('/factures/{facture}/imprimer',      [FactureController::class, 'imprimer'])->name('factures.imprimer');
    Route::patch('/factures/parametres/tva', [FactureController::class, 'updateTva'])->name('factures.tva');
    Route::post('/factures/{facture}/paiement',     [FactureController::class, 'enregistrerPaiement'])->name('factures.paiement');
    Route::get('/exports/factures', [FactureController::class, 'exportCsv'])->name('export.factures');

    // Livraisons
    Route::resource('livraisons', LivraisonController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('/livraisons/{livraison}/statut',  [LivraisonController::class, 'updateStatut'])->name('livraisons.statut');

    // Production
    Route::resource('production', ProductionController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/exports/production', [ProductionController::class, 'exportCsv'])->name('export.production');

    // Utilisateurs (admin seulement)
    Route::resource('users', UserController::class);

    // Notifications
    Route::get('/notifications',              [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/compteur',     [NotificationController::class, 'compteur'])->name('notifications.compteur');
    Route::post('/notifications/tout-lire',   [NotificationController::class, 'toutLire'])->name('notifications.toutLire');
    Route::post('/notifications/vider-lues',  [NotificationController::class, 'viderLues'])->name('notifications.viderLues');
    Route::get('/notifications/{notification}/lire', [NotificationController::class, 'lire'])->name('notifications.lire');
    Route::delete('/notifications/{notification}',   [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
