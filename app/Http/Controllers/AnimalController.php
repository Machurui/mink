<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AnimalController extends Controller
{
    /**
     * Récupère la liste des animaux
     */
    public function read(Request $request)
    {
        // Vérifie si le paramètre 'available_only' est passé et vaut true
        if ($request->query('available_only', false)) {
            // Récupère uniquement les animaux avec un statut disponible
            $animals = Animal::available()->get();
        } else {
            // Récupère tous les animaux
            $animals = Animal::all();
        }

        // Retourne les animaux sous forme de JSON
        return response()->json($animals);
    }

    /**
     * Crée un nouvel animal
     */
    public function create(Request $request)
    {
        // Validation des autres données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'species' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|max:255',
            'price' => 'nullable|numeric'
        ]);

        $imagePaths = [];

        // Traitement de chaque fichier envoyé
        foreach ($request->allFiles() as $key => $image) {
            // Vérifie si la clé commence par `image_`
            if (strpos($key, 'image_') === 0) {
                if ($image->isValid()) {
                    try {
                        // Stocke l'image dans le répertoire public
                        $path = $image->store('animals', 'public');
                        $imagePaths[] = $path; // Ajoute le chemin de l'image au tablea
                    } catch (\Exception $e) {
                        Log::error("Error storing image $key:", ['error' => $e->getMessage()]);
                    }
                } else {
                    Log::warning("Image $key is invalid or not uploaded successfully.");
                }
            }
        }

        try {
            // Fusionne les données validées avec les chemins des images
            $animalData = array_merge($validatedData, ['images' => $imagePaths]);

            // Crée l'objet'
            $animal = Animal::create($animalData);

            return response()->json(['animal' => $animal, 'message' => 'Animal ajouté avec succès'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'ajout de l\'animal.'], 500);
        }
    }

    /**
     * Met à jour un animal
     */
    public function update(Request $request, $id)
    {
        // Pour vérifier le contenu brut de la requête
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            $data = $request->all(); // fallback si la décodage JSON échoue
        }

        // Recherche l'animal par ID
        $animal = Animal::find($id);

        // Vérifie si l'animal existe
        if (!$animal) {
            return response()->json(['message' => 'Animal non trouvé.'], 404);
        }

        // Validation des données
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'age' => 'nullable|integer',
            'species' => 'sometimes|required|string|max:255',
            'breed' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|string|max:255',
            'price' => 'nullable|numeric'
        ]);

        // Récupérer les images existantes
        $existingImages = $animal->images ?? [];

        // Récupérer les images à conserver depuis la requête
        $imagesToKeep = [];
        foreach ($request->all() as $key => $value) {
            // Vérifie si la clé commence par `existing_image_`
            if (strpos($key, 'existing_image_') === 0) {
                $imagesToKeep[] = $value;
            }
        }

        // Supprimer les anciennes images qui ne sont pas dans les images à conserver
        foreach ($existingImages as $imagePath) {
            if (!in_array($imagePath, $imagesToKeep)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        // Traitement des nouvelles images
        $imagePaths = $imagesToKeep;

        // Ajouter les nouvelles images
        foreach ($request->allFiles() as $key => $image) {
            // Vérifie si la clé commence par `image_`
            if (strpos($key, 'image_') === 0) {
                if ($image->isValid()) {
                    try {
                        // Stocke l'image dans le répertoire public
                        $path = $image->store('animals', 'public');
                        $imagePaths[] = $path;
                    } catch (\Exception $e) {
                        Log::error("Error storing image $key:", ['error' => $e->getMessage()]);
                    }
                } else {
                    Log::warning("Image $key is invalid or not uploaded successfully.");
                }
            }
        }

        try {
            // Mettre à jour l'objet
            $animal->update(array_merge($validatedData, ['images' => $imagePaths]));

            return response()->json(['animal' => $animal, 'message' => 'Animal mis à jour avec succès'], 200);
        } catch (\Exception $e) {
            Log::error('Database Update Error:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur lors de la mise à jour de l\'animal.'], 500);
        }
    }

    /**
     * Supprime un animal
     */
    public function delete($id)
    {
        // Recherche de l'animal par ID
        $animal = Animal::find($id);

        // Vérifie si l'animal existe
        if (!$animal) {
            return response()->json(['message' => 'Animal non trouvé.'], 404);
        }

        try {
            // Récupérer les chemins des images en vérifiant leur format
            $imagePaths = is_array($animal->images) ? $animal->images : json_decode($animal->images, true);

            // Si $imagePaths est null après json_decode, on tente avec explode
            if (!is_array($imagePaths)) {
                $imagePaths = explode(',', $animal->images);
            }

            // Supprimer les fichiers d'images du répertoire storage/app/public/animals
            foreach ($imagePaths as $path) {
                $fullPath = storage_path('app/public/' . $path);

                // Supprimer le fichier du système de fichiers
                if (file_exists($fullPath)) {
                    if (!unlink($fullPath)) {
                        Log::warning("Failed to delete file at:", ['path' => $fullPath]);
                    }
                } else {
                    Log::warning("File not found for deletion:", ['path' => $fullPath]);
                }
            }

            // Supprime l'objet
            $animal->delete();
            return response()->json(['message' => 'Animal et images supprimés avec succès.'], 200);
        } catch (\Exception $e) {
            Log::error('Database Deletion Error:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur lors de la suppression de l\'animal.'], 500);
        }
    }
}
