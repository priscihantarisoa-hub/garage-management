<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RepairController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\InterventionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes API pour l'application mobile et le jeu Godot
|
*/

// ============ PUBLIC ROUTES ============

// Auth routes (public)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/check-email', [AuthController::class, 'checkEmail']);
});

// Interventions (public - liste complète)
Route::get('/interventions', [InterventionController::class, 'index']);
Route::get('/interventions/types', [InterventionController::class, 'types']);
Route::get('/interventions/{id}', [InterventionController::class, 'show']);
Route::get('/interventions/by-type/{type}', [InterventionController::class, 'byType']);

// Stats publiques
Route::get('/stats', [RepairController::class, 'stats']);

// ============ PROTECTED ROUTES ============

Route::middleware('auth:sanctum')->group(function () {

    // Auth routes (protégées)
    Route::prefix('auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/push-token', [AuthController::class, 'updatePushToken']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Clients routes
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{id}', [ClientController::class, 'show']);
    Route::get('/clients/{id}/repairs', [ClientController::class, 'repairs']);
    Route::get('/clients/{id}/active-repairs', [ClientController::class, 'activeRepairs']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::put('/clients/{id}', [ClientController::class, 'update']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

    // Repairs routes
    Route::get('/repairs', [RepairController::class, 'index']);
    Route::get('/repairs/active', [RepairController::class, 'active']);
    Route::get('/repairs/completed', [RepairController::class, 'completed']);
    Route::get('/repairs/{id}', [RepairController::class, 'show']);
    Route::post('/repairs', [RepairController::class, 'store']);
    Route::put('/repairs/{id}/status', [RepairController::class, 'updateStatus']);
    Route::put('/repairs/{repairId}/interventions/{interventionId}', [RepairController::class, 'updateInterventionStatus']);

    // Payments routes
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::get('/payments/client/{clientId}', [PaymentController::class, 'clientPayments']);
    Route::get('/payments/stats', [PaymentController::class, 'stats']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::post('/payments/pay/{reparationId}', [PaymentController::class, 'pay']);
    Route::put('/payments/{id}/cancel', [PaymentController::class, 'cancel']);
});
