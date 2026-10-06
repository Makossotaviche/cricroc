<?php

/**
 * Fichier : app/Models/Client.php
 * Description : composant du projet Cricroc.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $primaryKey = 'idclient';

    protected $fillable = [
        'type_client',
        'nom',
        'prenoms',
        'numero_piece_identite',
        'code_client',
        'date_enregistrement',
        'contact',
        'telephone_secondaire',
        'email',
        'whatsapp',
        'adresse',
        'pays',
        'ville',
        'commune',
    ];

    protected $casts = [
        'date_enregistrement' => 'date',
    ];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'idclient');
    }
}
