<?php

/**
 * Fichier : app/Http/Controllers/ClientController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{

    /**
     * Affiche la liste des clients.
     *
     * Cette méthode récupère tous les clients, compte le nombre de commandes
     * associées à chacun et les affiche dans une liste paginée.
     */
    public function index(): View
    {
        $clients = Client::withCount('commandes')->latest()->paginate(15);
        return view('clients.index', compact('clients'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau client.
     */
    public function create(): View
    {
        $nextCode = $this->nextClientCode();
        return view('clients.create', compact('nextCode'));
    }

    /**
     * Valide et enregistre un nouveau client en base de données.
     *
     * Les champs obligatoires sont le nom et les champs facultatifs sont le
     * contact et l'adresse.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateClient($request);
        $validated['code_client'] = $validated['code_client'] ?: $this->nextClientCode();
        $validated['date_enregistrement'] = $validated['date_enregistrement'] ?: now()->toDateString();

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un client.
     *
     * On charge les commandes du client ainsi que les factures et livraisons
     * liées pour permettre un affichage complet du dossier client.
     */
    public function show(Client $client): View
    {
        $client->load(['commandes.facture', 'commandes.livraison']);
        return view('clients.show', compact('client'));
    }

    /**
     * Affiche le formulaire d'édition d'un client existant.
     */
    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Met à jour les informations d'un client.
     *
     * Les règles de validation sont les mêmes que pour la création.
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $this->validateClient($request, $client);
        $validated['code_client'] = $validated['code_client'] ?: $client->code_client ?: $this->nextClientCode();

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client modifié avec succès.');
    }

    /**
     * Supprime un client si aucune commande n'est associée.
     *
     * La suppression est bloquée pour éviter de supprimer l'historique
     * des commandes liées à ce client.
     */
    public function destroy(Client $client): RedirectResponse
    {
        if ($client->commandes()->exists()) {
            return back()->with('error', 'Impossible de supprimer un client ayant des commandes.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé.');
    }

    /**
     * Export CSV des clients.
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="clients.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['idclient', 'code_client', 'type_client', 'nom', 'prenoms', 'numero_piece_identite', 'contact', 'telephone_secondaire', 'email', 'whatsapp', 'adresse', 'pays', 'ville', 'commune', 'commandes_count']);
            foreach (Client::withCount('commandes')->cursor() as $c) {
                fputcsv($handle, [$c->idclient, $c->code_client, $c->type_client, $c->nom, $c->prenoms, $c->numero_piece_identite, $c->contact, $c->telephone_secondaire, $c->email, $c->whatsapp, $c->adresse, $c->pays, $c->ville, $c->commune, $c->commandes_count]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function validateClient(Request $request, ?Client $client = null): array
    {
        $clientId = $client?->idclient;

        return $request->validate([
            'type_client' => 'required|string|in:Particulier,Societe',
            'nom' => 'required|string|max:255',
            'prenoms' => 'nullable|string|max:255',
            'numero_piece_identite' => 'nullable|string|max:100',
            'code_client' => 'nullable|string|max:50|unique:clients,code_client,' . $clientId . ',idclient',
            'date_enregistrement' => 'nullable|date',
            'contact' => 'required|string|max:100',
            'telephone_secondaire' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:100',
            'adresse' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:100',
            'ville' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:150',
        ]);
    }

    private function nextClientCode(): string
    {
        $nextId = ((int) Client::max('idclient')) + 1;

        return 'CLT-' . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }
}
