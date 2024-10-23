<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index()
    {
        // Récupérer tous les animaux de la base de données
        $animals = Animal::all();

        // Retourner les animaux sous forme de JSON
        return response()->json($animals);
    }
}
