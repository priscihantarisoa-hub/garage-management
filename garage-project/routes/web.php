<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminRepairController;

/*
|--------------------------------------------------------------------------
| Routes Web pour le Garage Backoffice
|--------------------------------------------------------------------------
|
| Ce fichier contient toutes les routes web de l'application.
| Les routes sont regroupées par fonctionnalité et protégées par
| un middleware d'authentification simple.
|
*/

// Page d'accueil - redirection vers le login
// Redirige automatiquement les visiteurs vers la page de connexion
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes d'authentification (accessibles sans connexion)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes protégées par authentification
// Toutes les routes dans ce groupe nécessitent une connexion active
Route::middleware(['auth'])->group(function () {
    // Tableau de bord
    // Page principale affichant les statistiques et l'état du garage
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Interventions - Gestion complète des interventions
    Route::get('/interventions', [InterventionController::class, 'index'])->name('interventions.index');
    Route::get('/interventions/create', [InterventionController::class, 'create'])->name('interventions.create');
    Route::post('/interventions', [InterventionController::class, 'store'])->name('interventions.store');
    Route::get('/interventions/{intervention}/edit', [InterventionController::class, 'edit'])->name('interventions.edit');
    Route::put('/interventions/{intervention}', [InterventionController::class, 'update'])->name('interventions.update');
    Route::delete('/interventions/{intervention}', [InterventionController::class, 'destroy'])->name('interventions.destroy');

    // Gestion des réparations - Flux avec limite de 3 voitures
    Route::get('/admin/repairs', [AdminRepairController::class, 'index'])->name('admin.repairs.index');
    Route::get('/admin/repairs/create', [AdminRepairController::class, 'create'])->name('admin.repairs.create');
    Route::post('/admin/repairs', [AdminRepairController::class, 'store'])->name('admin.repairs.store');
    Route::post('/admin/repairs/{id}/update-status', [AdminRepairController::class, 'updateStatus'])->name('admin.repairs.update-status');

    // Test Firebase - Désactivé temporairement
    // Route::get('/test/firebase', function () {
    //     $firebaseService = app(\App\Services\FirebaseSyncService::class);
    //     $connection = $firebaseService->testConnection();
    //     
    //     return response()->json([
    //         'firebase_connection' => $connection ? 'OK' : 'FAILED',
    //         'message' => $connection ? 'Firebase est connecté' : 'Erreur de connexion Firebase'
    //     ]);
    // })->name('test.firebase');
});
