<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     * 
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Gère la tentative de connexion de l'utilisateur
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validation des champs du formulaire de connexion
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Login simple pour le backoffice (à adapter avec vos identifiants)
        // NOTE: En production, utiliser un système d'authentification robuste
        if ($request->email === 'admin@garage.com' && $request->password === 'admin123') {
            // Stocke l'état d'authentification en session
            session(['authenticated' => true]);
            // Redirige vers le tableau de bord ou la page demandée
            return redirect()->intended(route('dashboard'));
        }

        // En cas d'échec, retourne au formulaire avec message d'erreur
        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ]);
    }

    /**
     * Gère la déconnexion de l'utilisateur
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Supprime l'état d'authentification de la session
        session()->forget('authenticated');
        // Redirige vers la page de login
        return redirect()->route('login');
    }
}
