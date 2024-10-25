<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Animal;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Azerty2024'), // Hasher le mot de passe
        ]);

        // Fonctionnalité de test pour générer 50 animaux
        Animal::factory()->count(50)->create();
    }
}
