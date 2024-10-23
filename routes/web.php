<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;

Route::get('/', function () {
    return view('app');
})
->name('Application');

// Route pour récupérer les animaux
Route::get('/animals', [AnimalController::class, 'index']);
