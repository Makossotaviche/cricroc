<?php

/**
 * Fichier : app/Models/NotificationInterne.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationInterne extends Model
{
    protected $table = 'notifications_internes';

    protected $fillable = [
        'user_id',
        'titre',
        'message',
        'icone',
        'couleur',
        'lien',
        'lue',
    ];

    protected $casts = [
        'lue' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers statiques pour créer facilement des notifications ──

    public static function envoyer(
        int    $userId,
        string $titre,
        string $message,
        string $icone   = 'bi-bell',
        string $couleur = 'primary',
        string $lien    = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'titre'   => $titre,
            'message' => $message,
            'icone'   => $icone,
            'couleur' => $couleur,
            'lien'    => $lien,
        ]);
    }

    /**
     * Envoyer à tous les utilisateurs d'un rôle donné.
     */
    public static function envoyerAuRole(
        string $role,
        string $titre,
        string $message,
        string $icone   = 'bi-bell',
        string $couleur = 'primary',
        string $lien    = null
    ): void {
        $users = User::where('role', $role)
            ->orWhere('role', 'admin')
            ->get();

        foreach ($users as $user) {
            self::envoyer($user->id, $titre, $message, $icone, $couleur, $lien);
        }
    }
}
