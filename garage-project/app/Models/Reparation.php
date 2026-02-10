<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Reparation
 * 
 * Représente les réparations effectuées par le garage.
 * Chaque réparation est liée à un client et à une intervention,
 * et suit un cycle de vie avec différents statuts.
 */
class Reparation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * @var array
     */
    protected $fillable = [
        'client_id',
        'intervention_id',
        'statut',
        'slot',
        'debut_reparation',
        'fin_reparation',
        'montant_total'
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs
     * 
     * @var array
     */
    protected $casts = [
        'debut_reparation' => 'datetime',
        'fin_reparation' => 'datetime',
        'montant_total' => 'decimal:2',
        'slot' => 'integer'
    ];

    /**
     * Définit la relation avec le client
     * Une réparation appartient à un seul client
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Définit la relation avec l'intervention
     * Une réparation concerne une seule intervention
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    /**
     * Définit la relation many-to-many avec les interventions
     * Une réparation peut avoir plusieurs interventions
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function interventions()
    {
        return $this->belongsToMany(Intervention::class, 'repair_intervention')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Retourne la liste des statuts possibles pour une réparation
     * 
     * @return array
     */
    public static function statuts()
    {
        return [
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'paye' => 'Payé'
        ];
    }
}
