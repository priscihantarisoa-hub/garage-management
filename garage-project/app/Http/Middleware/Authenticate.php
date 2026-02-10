<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware d'authentification simple pour le backoffice
 * 
 * Ce middleware vérifie si l'utilisateur est authentifié en session.
 * Si non authentifié, il redirige vers la page de connexion.
 */
class Authenticate
{
    /**
     * Gère la requête HTTP entrante
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est authentifié en session
        if (!session('authenticated')) {
            // Redirige vers la page de connexion si non authentifié
            return redirect()->route('login');
        }

        // Poursuit la requête si authentifié
        return $next($request);
    }
}
