<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Route pour la page d'accueil
Route::get('/', function () {
    return view('app');
})->name('Application');

// Page du panneau (Panel)
Route::view('/panel', 'web.panel')->name('panel');

// Route pour récupérer les animaux
Route::get('/animals', [AnimalController::class, 'read'])->name('animals.read');

Route::post('/animals', [AnimalController::class, 'create'])->name('animals.create');

Route::post('/animals/{animal}/update', [AnimalController::class, 'update'])->name('animals.update');

Route::delete('/animals/{animal}', [AnimalController::class, 'delete'])->name('animals.delete');



// A REVOIR

// Affichage de la page de connexion
Route::view('/signin', 'auth.signin')->name('signin');

// Action pour l'authentification (login)
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Action pour la déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
