<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Livraison;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LivraisonController extends Controller
{
    public function index(): View
    {
        $livraisons = Livraison::with(['commande.client', 'livreur'])->latest()->paginate(20);
        return view('livraisons.index', compact('livraisons'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->isAgentCommercial(), 403);
        $commandes = Commande::with('client')->where('statut', 'validee')
            ->whereDoesntHave('livraison')->latest('date_commande')->get();
        $livreurs = User::where('role', 'livreur')->orderBy('name')->get();
        return view('livraisons.create', compact('commandes', 'livreurs'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isAgentCommercial(), 403);
        $validated = $request->validate([
            'idcommande' => 'required|exists:commandes,idcommande',
            'idlivreur' => 'nullable|exists:users,id',
        ]);

        $livraison = DB::transaction(function () use ($validated) {
            $commande = Commande::whereKey($validated['idcommande'])->lockForUpdate()->firstOrFail();
            if ($commande->statut !== 'validee' || $commande->livraison()->exists()) {
                return null;
            }
            return Livraison::create([
                'idcommande' => $commande->idcommande,
                'idlivreur' => $validated['idlivreur'] ?? null,
                'statut' => 'en_attente',
            ]);
        });

        if (!$livraison) {
            return back()->withInput()->withErrors(['idcommande' => 'Cette commande est déjà livrée ou n’est pas validée.']);
        }
        return redirect()->route('livraisons.show', $livraison)->with('success', 'Livraison créée.');
    }

    public function show(Livraison $livraison): View
    {
        $livraison->load(['commande.client', 'commande.lignes.produit', 'livreur']);
        return view('livraisons.show', compact('livraison'));
    }

    public function updateStatut(Request $request, Livraison $livraison): RedirectResponse
    {
        $validated = $request->validate(['statut' => 'required|in:en_attente,en_cours,livree']);
        $user = $request->user();
        abort_unless($user->isAdmin() || ($user->isLivreur() && $livraison->idlivreur === $user->id), 403);

        if ($livraison->statut === 'livree') {
            return back()->with('error', 'Une livraison terminée ne peut plus être modifiée.');
        }
        $livraison->update([
            'statut' => $validated['statut'],
            'date_livraison' => $validated['statut'] === 'livree' ? now()->toDateString() : null,
        ]);

        return back()->with('success', 'Statut de livraison mis à jour.');
    }
}
