<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Intervention
 * 
 * Représente les différents types d'interventions proposées par le garage.
 * Chaque intervention a un nom, une description, un prix, une durée et un type.
 */
class Intervention extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * @var array
     */
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'duree',
        'type',
        'actif'
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs
     * 
     * @var array
     */
    protected $casts = [
        'prix' => 'decimal:2',
        'duree' => 'integer',
        'actif' => 'boolean'
    ];

    /**
     * Retourne la liste des types d'interventions possibles
     * 
     * @return array
     */
    public static function typesInterventions()
    {
        return [
            'frein' => 'Frein',
            'vidange' => 'Vidange',
            'filtre' => 'Filtre',
            'batterie' => 'Batterie',
            'amortisseurs' => 'Amortisseurs',
            'embrayage' => 'Embrayage',
            'pneus' => 'Pneus',
            'refroidissement' => 'Système de refroidissement'
        ];
    }

    /**
     * Définit la relation avec les réparations
     * Une intervention peut concerner plusieurs réparations
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reparations()
    {
        return $this->hasMany(Reparation::class);
    }

    /**
     * Définit la relation many-to-many avec les réparations
     * Pour la gestion des statuts par intervention dans une réparation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function repairs()
    {
        return $this->belongsToMany(Reparation::class, 'repair_intervention')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
