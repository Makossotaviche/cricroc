<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametreFacturation extends Model
{
    protected $table = 'parametres_facturation';

    protected $fillable = ['taux_tva'];

    public static function tauxTvaActuel(): float
    {
        return (float) static::query()->firstOrCreate(
            ['id' => 1],
            ['taux_tva' => 0]
        )->taux_tva;
    }

    public static function enregistrerTauxTva(float $taux): void
    {
        static::query()->updateOrCreate(
            ['id' => 1],
            ['taux_tva' => $taux]
        );
    }
}
