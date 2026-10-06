<?php

/**
 * Fichier : app/Models/User.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'      => 'datetime',
        'password'               => 'hashed',
    ];

    // --- Helpers de rôle ---

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgentCommercial(): bool
    {
        return in_array($this->role, ['admin', 'agent_commercial']);
    }

    public function isGestionnaireStock(): bool
    {
        return in_array($this->role, ['admin', 'gestionnaire_stock']);
    }

    public function isResponsableProduction(): bool
    {
        return in_array($this->role, ['admin', 'responsable_production']);
    }

    public function isLivreur(): bool
    {
        return in_array($this->role, ['admin', 'livreur']);
    }

    public function getRoleLibelleAttribute(): string
    {
        return match($this->role) {
            'admin'                   => 'Administrateur',
            'agent_commercial'        => 'Agent Commercial',
            'gestionnaire_stock'      => 'Gestionnaire de Stock',
            'responsable_production'  => 'Responsable de Production',
            'livreur'                 => 'Livreur',
            default                   => $this->role,
        };
    }

    public function livraisons(): HasMany
    {
        return $this->hasMany(Livraison::class, 'idlivreur');
    }
}
