<template>
    <section class="w-screen h-screen flex flex-col">
        <div class="card flex flex-col h-full w-full">
            <!-- Header et Filtres -->
            <div class="card-header flex flex-wrap gap-4 justify-between mb-4 p-4">
                <div>
                    <h6 class="text-blue-gray-700 text-xl">Vente de bétail</h6>
                    <small class="text-gray-600 font-normal mt-1">Liste des animaux à vendre -
                        <span class="font-bold">Nous contacter au 06 06 06 06 06</span>
                    </small>
                </div>

                <!-- Filtres et tri -->
                <div class="flex items-center gap-4">
                    <!-- Filtre par espèce -->
                    <div class="w-full max-w-sm min-w-[200px]">
                        <div class="relative">
                            <select v-model="speciesFilter"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                                <option value="">Toutes les espèces</option>
                                <option v-for="species in speciesOptions" :key="species" :value="species">{{ species }}
                                </option>
                            </select>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2"
                                stroke="currentColor" class="h-5 w-5 ml-1 absolute top-2.5 right-2.5 text-slate-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </div>
                    </div>

                    <!-- Filtre par race -->
                    <div class="w-full md:w-104 relative">
                        <!-- Champ de recherche -->
                        <input v-model="breedFilter" placeholder="Filtrer par race"
                            class="form-input w-full pr-12 py-2 pl-4 rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none" />
                        <!-- Icône à droite -->
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <!-- Icone de la loupe -->
                            <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                        </div>
                    </div>


                    <!-- Tri par prix -->
                    <button @click="togglePriceOrder"
                        class="inline-flex items-center justify-center rounded-md border border-slate-300 py-2 px-4 text-center text-sm transition-all shadow-sm hover:shadow-lg text-slate-600 hover:text-white hover:bg-slate-800 hover:border-slate-800 focus:text-white focus:bg-slate-800 focus:border-slate-800 active:border-slate-800 active:text-white active:bg-slate-800 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        type="button">
                        Prix
                        <ChevronDownIcon :class="priceOrder === 'asc' ? 'rotate-180' : ''" stroke-width="3"
                            class="w-4 h-4 ml-2" />
                    </button>

                    <!-- Tri par âge -->
                    <button @click="toggleAgeOrder"
                        class="inline-flex items-center justify-center rounded-md border border-slate-300 py-2 px-4 text-center text-sm transition-all shadow-sm hover:shadow-lg text-slate-600 hover:text-white hover:bg-slate-800 hover:border-slate-800 focus:text-white focus:bg-slate-800 focus:border-slate-800 active:border-slate-800 active:text-white active:bg-slate-800 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        type="button">
                        Âge
                        <ChevronDownIcon :class="priceOrder === 'asc' ? 'rotate-180' : ''" stroke-width="3"
                            class="w-4 h-4 ml-2" />
                    </button>
                </div>
            </div>

            <!-- Tableau des animaux avec hauteur et largeur fixes -->
            <div class="card-body flex-grow overflow-auto">
                <table v-if="showTableData" class="w-full min-w-full table-auto text-center">
                    <thead>
                        <tr>
                            <th class="border-b border-gray-300 p-4 text-center">Nom</th>
                            <th class="border-b border-gray-300 p-4 text-center">Âge</th>
                            <th class="border-b border-gray-300 p-4 text-center">Espèce</th>
                            <th class="border-b border-gray-300 p-4 text-center">Race</th>
                            <th class="border-b border-gray-300 p-4 text-center">Description</th>
                            <th class="border-b border-gray-300 p-4 text-center">Prix TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in filteredRows" :key="index">
                            <td class="p-4">{{ row.name }}</td>
                            <td class="p-4">{{ row.age }} an(s)</td>
                            <td class="p-4">{{ row.species }}</td>
                            <td class="p-4">{{ row.breed }}</td>
                            <td class="p-4">{{ row.description }}</td>
                            <td class="p-4">{{ row.price }} €</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { ChevronDownIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';

export default {
    components: {
        ChevronDownIcon,
        MagnifyingGlassIcon,
    },
    setup() {
        const tableRow = ref([]); // Contient tous les animaux
        const speciesFilter = ref(''); // Filtre pour l'espèce
        const breedFilter = ref(''); // Filtre pour la race
        const priceOrder = ref('asc'); // Ordre de tri pour le prix
        const ageOrder = ref('asc'); // Ordre de tri pour l'âge
        const currentSort = ref('age'); // Contrôle quel champ est trié (age ou prix)
        const speciesOptions = ref([]); // Espèces disponibles pour le filtre
        const showTableData = ref(true); // Contrôle l'affichage des données du tableau

        // Taux de TVA (par exemple 20%)
        const TVA_RATE = 0.20;

        // Charger les données depuis l'API Laravel
        const fetchAnimals = async () => {
            try {
                const response = await fetch('http://127.0.0.1:8000/animals');
                const data = await response.json();

                const speciesSet = new Set();
                tableRow.value = data.map(animal => {
                    if (animal.species) speciesSet.add(animal.species);

                    return {
                        name: animal.name,
                        age: animal.age,
                        species: animal.species,
                        breed: animal.breed,
                        description: animal.description || 'Aucune description disponible',
                        price: animal.price ? parseFloat((animal.price * (1 + TVA_RATE)).toFixed(2)) : 'N/A',
                        img: animal.image ? `/storage/${animal.image}` : 'default_image.jpg',
                    };
                });

                speciesOptions.value = Array.from(speciesSet);
            } catch (error) {
                console.error('Erreur lors de la récupération des animaux:', error);
            }
        };

        // Appel de la fonction lors du montage du composant
        onMounted(() => {
            fetchAnimals();
        });

        // Fonction pour trier les données
        const filteredRows = computed(() => {
            let filtered = [...tableRow.value]; // Ne jamais modifier les données originales

            // Appliquer les filtres par espèce et race
            if (speciesFilter.value) {
                filtered = filtered.filter(row => row.species === speciesFilter.value);
            }
            if (breedFilter.value) {
                filtered = filtered.filter(row => row.breed.toLowerCase().includes(breedFilter.value.toLowerCase()));
            }

            // Appliquer le tri en fonction du champ sélectionné
            if (currentSort.value === 'age') {
                // Tri par âge
                if (ageOrder.value === 'asc') {
                    filtered = filtered.sort((a, b) => (a.age === null ? Infinity : a.age) - (b.age === null ? Infinity : b.age));
                } else {
                    filtered = filtered.sort((a, b) => (b.age === null ? Infinity : b.age) - (a.age === null ? Infinity : a.age));
                }
            } else if (currentSort.value === 'price') {
                // Tri par prix
                if (priceOrder.value === 'asc') {
                    filtered = filtered.sort((a, b) => (a.price === 'N/A' ? Infinity : a.price) - (b.price === 'N/A' ? Infinity : b.price));
                } else {
                    filtered = filtered.sort((a, b) => (b.price === 'N/A' ? -Infinity : b.price) - (a.price === 'N/A' ? -Infinity : a.price));
                }
            }

            return filtered;
        });

        // Fonction pour changer l'ordre de tri par âge
        const toggleAgeOrder = () => {
            showTableData.value = false; // Vider temporairement la table
            currentSort.value = 'age'; // Indiquer que nous trions par âge
            ageOrder.value = ageOrder.value === 'asc' ? 'desc' : 'asc'; // Inverser l'ordre
            setTimeout(() => {
                showTableData.value = true; // Réafficher après 500ms
            }, 50);
        };

        // Fonction pour changer l'ordre de tri par prix
        const togglePriceOrder = () => {
            showTableData.value = false; // Vider temporairement la table
            currentSort.value = 'price'; // Indiquer que nous trions par prix
            priceOrder.value = priceOrder.value === 'asc' ? 'desc' : 'asc'; // Inverser l'ordre
            setTimeout(() => {
                showTableData.value = true; // Réafficher après 500ms
            }, 50);
        };

        return {
            speciesFilter,
            breedFilter,
            priceOrder,
            ageOrder,
            currentSort, // Ajout pour gérer quel champ est trié
            speciesOptions, // Liste dynamique des espèces
            filteredRows,
            toggleAgeOrder, // Fonction pour trier par âge
            togglePriceOrder, // Fonction pour trier par prix
            showTableData, // Contrôle l'affichage des données du tableau
        };
    },
};
</script>

<style scoped>
.card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 16px;
}

/* Tailwind CSS est déjà utilisé, nous ajustons quelques classes supplémentaires */
.card-body {
    /* La hauteur s'adapte à l'écran, tout en permettant un scroll interne */
    max-height: 75vh;
    /* 75% de la hauteur de l'écran pour être confortable */
}

@media (min-width: 640px) {

    /* Plus d'espace pour les petits écrans comme les tablettes */
    .card-body {
        max-height: 85vh;
        /* 85% pour les tablettes et plus */
    }
}

@media (min-width: 1024px) {

    /* Affichage sur des écrans plus larges */
    .card-body {
        max-height: 90vh;
        /* Utiliser presque tout l'écran sur les grands écrans */
    }
}

.icon-button {
    background: none;
    border: none;
}
</style>
