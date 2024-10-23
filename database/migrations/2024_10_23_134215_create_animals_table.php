<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque animal
            $table->string('name'); // Nom de l'animal
            $table->integer('age')->nullable(); // Âge de l'animal (peut être nul si inconnu)
            $table->string('species'); // Espèce de l'animal (ex : chien, chat)
            $table->string('breed'); // Espèce de l'animal (ex : chien, chat)
            $table->text('description')->nullable(); // Description de l'animal
            $table->float('price')->nullable(); // Prix de l'animal HT
            $table->string('image')->nullable(); // Chemin de l'image de l'animal
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
