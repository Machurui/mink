<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Route pour la page d'accueil, accessible authentifié ou non
Route::get('/', function () {
    return view('app');
})->name('Application');

// Routes accessibles seulement aux utilisateurs non authentifiés
Route::middleware('guest')->group(function () {
    // Affichage de la page de connexion
    Route::view('/signin', 'auth.signin')->name('signin');

    // Action pour l'authentification (login)
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Routes accessibles seulement aux utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    // Page du panneau (Panel)
    Route::view('/panel', 'web.panel')->name('panel');

    // Route pour créer un animal
    Route::post('/animals', [AnimalController::class, 'create'])->name('animals.create');

    // Route pour mettre à jour un animal
    Route::post('/animals/{animal}/update', [AnimalController::class, 'update'])->name('animals.update');

    // Route pour supprimer un animal
    Route::delete('/animals/{animal}', [AnimalController::class, 'delete'])->name('animals.delete');

    // Action pour la déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Route pour récupérer les animaux, accessible authentifié ou non
Route::get('/animals', [AnimalController::class, 'read'])->name('animals.read');
