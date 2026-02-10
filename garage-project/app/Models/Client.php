<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Client
 * 
 * Représente les clients du garage automobile.
 * Chaque client peut avoir plusieurs véhicules et plusieurs réparations.
 */
class Client extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * @var array
     */
    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'voiture_marque',
        'voiture_modele',
        'voiture_immatriculation'
    ];

    /**
     * Définit la relation avec les réparations
     * Un client peut avoir plusieurs réparations
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reparations()
    {
        return $this->hasMany(Reparation::class);
    }
}
