<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Http\Request;

/**
 * Contrôleur pour la gestion des interventions du garage
 * 
 * Ce contrôleur gère les opérations CRUD (Create, Read, Update, Delete)
 * pour les différentes interventions proposées par le garage.
 * Chaque intervention a un nom, une description, un prix, une durée (en secondes) et un type.
 */
class InterventionController extends Controller
{
    /**
     * Affiche la liste de toutes les interventions
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupération de toutes les interventions triées par nom
        // avec pagination de 10 éléments par page
        $interventions = Intervention::orderBy('nom')->paginate(10);
        return view('interventions.index', compact('interventions'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle intervention
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Récupération des types d'interventions prédéfinis
        // pour le menu déroulant du formulaire
        $types = Intervention::typesInterventions();
        return view('interventions.create', compact('types'));
    }

    /**
     * Enregistre une nouvelle intervention dans la base de données.
     * Chaque intervention a un nom, une description, un prix, une durée (en secondes) et un type.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1',
            'type' => 'required|in:' . implode(',', array_keys(Intervention::typesInterventions())),
            'actif' => 'boolean'
        ]);

        // Création de l'intervention avec toutes les données validées
        Intervention::create($request->all());

        // Redirection vers la liste avec message de succès
        return redirect()->route('interventions.index')
            ->with('success', 'Intervention créée avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'une intervention existante
     * 
     * @param  \App\Models\Intervention  $intervention
     * @return \Illuminate\Http\Response
     */
    public function edit(Intervention $intervention)
    {
        // Récupération des types d'interventions pour le formulaire
        $types = Intervention::typesInterventions();
        return view('interventions.edit', compact('intervention', 'types'));
    }

    /**
     * Met à jour une intervention existante dans la base de données
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Intervention  $intervention
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Intervention $intervention)
    {
        // Validation des données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1',
            'type' => 'required|in:' . implode(',', array_keys(Intervention::typesInterventions())),
            'actif' => 'boolean'
        ]);

        // Mise à jour de l'intervention avec les nouvelles données
        $intervention->update($request->all());

        // Redirection vers la liste avec message de succès
        return redirect()->route('interventions.index')
            ->with('success', 'Intervention mise à jour avec succès.');
    }

    /**
     * Supprime une intervention de la base de données
     * 
     * @param  \App\Models\Intervention  $intervention
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Intervention $intervention)
    {
        // Suppression de l'intervention
        $intervention->delete();

        // Redirection vers la liste avec message de succès
        return redirect()->route('interventions.index')
            ->with('success', 'Intervention supprimée avec succès.');
    }
}
