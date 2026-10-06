<?php

/**
 * Fichier : app/Http/Controllers/NotificationController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers;

use App\Models\NotificationInterne;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Affiche la boîte de réception de l'utilisateur connecté.
     *
     * Récupère toutes les notifications triées par date, les renvoie à la
     * vue et marque automatiquement comme lues celles qui ne le sont pas.
     */
    public function index(): View
    {
        $notifications = NotificationInterne::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        // Marquer toutes comme lues à l'ouverture de la boîte
        NotificationInterne::where('user_id', auth()->id())
            ->where('lue', false)
            ->update(['lue' => true]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marque une notification spécifique comme lue et redirige vers son lien.
     *
     * Cette action s'assure que l'utilisateur a bien le droit d'accéder à la
     * notification avant de la mettre à jour.
     */
    public function lire(NotificationInterne $notification): RedirectResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['lue' => true]);

        return redirect($notification->lien ?? route('notifications.index'));
    }

    /**
     * Marque toutes les notifications non lues de l'utilisateur comme lues.
     */
    public function toutLire(): RedirectResponse
    {
        NotificationInterne::where('user_id', auth()->id())
            ->where('lue', false)
            ->update(['lue' => true]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Supprime une notification de l'utilisateur.
     *
     * L'utilisateur ne peut supprimer que ses propres notifications.
     */
    public function destroy(NotificationInterne $notification): RedirectResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }

    /**
     * Supprime toutes les notifications déjà lues de l'utilisateur.
     */
    public function viderLues(): RedirectResponse
    {
        NotificationInterne::where('user_id', auth()->id())
            ->where('lue', true)
            ->delete();

        return back()->with('success', 'Notifications lues supprimées.');
    }

    /**
     * Fournit un compteur JSON des notifications non lues.
     *
     * Cette API est généralement utilisée pour mettre à jour la topbar en
     * temps réel avec le nombre de notifications non lues et les plus récentes.
     */
    public function compteur(): JsonResponse
    {
        $count = NotificationInterne::where('user_id', auth()->id())
            ->where('lue', false)
            ->count();

        $recentes = NotificationInterne::where('user_id', auth()->id())
            ->where('lue', false)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'count'   => $count,
            'recentes'=> $recentes,
        ]);
    }
}
