<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalFactory extends Factory
{
    /**
     * Le nom du modèle associé à cette factory.
     *
     * @var string
     */
    protected $model = Animal::class;

    /**
     * Définir les valeurs par défaut de chaque champ pour les animaux.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(), // Nom aléatoire
            'age' => $this->faker->numberBetween(1, 15), // Âge entre 1 et 15 ans
            'species' => $this->faker->randomElement(['chien', 'chat', 'loup', 'hamster']), // Espèce aléatoire
            'breed' => $this->faker->word(), // Race aléatoire
            'description' => $this->faker->sentence(), // Description fictive
            'price' => $this->faker->randomFloat(2, 50, 1000), // Prix aléatoire entre 50 et 1000
            'image' => $this->faker->imageUrl(640, 480, 'animals', true), // URL d'image fictive
        ];
    }
}

