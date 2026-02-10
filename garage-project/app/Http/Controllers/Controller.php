<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Contrôleur de base pour l'application
 * 
 * Ce contrôleur étend le contrôleur de base de Laravel et inclut
 * les traits nécessaires pour l'autorisation et la validation.
 * Il définit également le middleware d'authentification pour toutes les routes.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Constructeur du contrôleur
     * 
     * Applique le middleware d'authentification à toutes les méthodes
     * sauf pour les routes de login (showLoginForm et login).
     */
    public function __construct()
    {
        // Middleware d'authentification simple
        // Protège toutes les routes sauf celles spécifiées dans except()
        $this->middleware('auth')->except(['showLoginForm', 'login']);
    }
}
