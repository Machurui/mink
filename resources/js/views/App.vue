<template>
    <section class="w-screen h-screen flex flex-col bg-gray-100">
        <div class="container mx-auto p-6 flex flex-col gap-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <nav
                    class="flex items-center justify-between backdrop-saturate-200 backdrop-blur-md bg-opacity-90 border border-gray-200 w-full max-w-full px-6 bg-white text-blue-gray-800 rounded-lg transition-all sticky top-4 z-40 py-4 shadow-lg shadow-blue-gray-500/10">
                    <div class="flex flex-col">
                        <h6 class="text-blue-gray-900 text-2xl font-semibold">Vente de bétail</h6>
                        <small class="text-gray-600 font-normal mt-1 text-sm">
                            Liste des animaux à vendre -
                            <span class="font-semibold">Nous contacter au 06 06 06 06 06</span>
                        </small>
                    </div>
                    <button @click="redirectToPanel"
                        class="bg-gradient-to-tr from-gray-900 to-gray-800 text-white font-semibold py-2 px-5 rounded-md text-sm shadow-md hover:shadow-lg hover:scale-105 transition transform duration-300">
                        Panel Admin
                    </button>
                </nav>

            </div>

            <!-- Filter Section -->
            <div class="flex items-center gap-4 mt-4">
                <div class="w-full max-w-md">
                    <select v-model="speciesFilter"
                        class="w-full bg-white border border-gray-300 text-gray-800 text-base py-2 px-3 rounded-lg shadow focus:outline-none focus:ring focus:border-blue-400">
                        <option value="">Toutes les espèces</option>
                        <option v-for="species in speciesOptions" :key="species" :value="species">{{ species }}</option>
                    </select>
                </div>

                <div class="w-full md:w-104 relative">
                    <input v-model="breedFilter" placeholder="Filtrer par race"
                        class="w-full bg-white border border-gray-300 text-base py-2 px-3 rounded-lg shadow focus:outline-none focus:ring focus:border-blue-400" />
                </div>

                <button @click="togglePriceOrder"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-base py-2 px-4 rounded-lg flex items-center gap-1">
                    Prix
                    <span v-if="currentSort === 'price'">
                        <ChevronDownIcon v-if="priceOrder === 'asc'" class="h-4 w-4" />
                        <ChevronDownIcon v-else class="h-4 w-4 transform rotate-180" />
                    </span>
                </button>

                <button @click="toggleAgeOrder"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-base py-2 px-4 rounded-lg flex items-center gap-1">
                    Âge
                    <span v-if="currentSort === 'age'">
                        <ChevronDownIcon v-if="ageOrder === 'asc'" class="h-4 w-4" />
                        <ChevronDownIcon v-else class="h-4 w-4 transform rotate-180" />
                    </span>
                </button>
            </div>

            <!-- Table Section -->
            <div
                class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 overflow-hidden xl:col-span-2 border border-blue-gray-100 shadow-sm">
                <div
                    class="relative bg-clip-border rounded-xl overflow-hidden bg-transparent text-gray-700 shadow-none m-0 flex items-center justify-between p-6">
                    <div>
                        <h6
                            class="block antialiased tracking-normal font-sans text-base font-semibold leading-relaxed text-blue-gray-900 mb-1">
                            Liste des animaux</h6>
                        <p
                            class="antialiased font-sans text-sm leading-normal flex items-center gap-1 font-normal text-blue-gray-600">
                            <ShoppingCartIcon class="h-5 w-5" />
                            <strong>{{ animalCount }}</strong> {{ animalCount === 1 ? 'animal' : 'animaux' }} en vente
                        </p>
                    </div>
                </div>
                <div class="p-6 overflow-x-scroll max-h-[600px] px-0 pt-0 pb-2">
                    <table class="w-full min-w-[640px] table-auto text-center">
                        <thead>
                            <tr>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-left">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Nom
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-left">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Âge
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-left">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Espèce
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-left">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Race
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-left">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Description
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-left">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Prix TTC
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-left">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Image(s)
                                    </p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in filteredRows" :key="index">
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    <p
                                        class="block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-bold">
                                        {{ row.name }}
                                    </p>
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    <p class="block antialiased font-sans text-sm font-medium text-blue-gray-600">
                                        {{ row.age }} an(s)
                                    </p>
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    <p class="block antialiased font-sans text-sm font-medium text-blue-gray-600">
                                        {{ row.species }}
                                    </p>
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    <p class="block antialiased font-sans text-sm font-medium text-blue-gray-600">
                                        {{ row.breed }}
                                    </p>
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    <p class="block antialiased font-sans text-sm font-medium text-blue-gray-600">
                                        {{ row.description }}
                                    </p>
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    <p class="block antialiased font-sans text-sm font-medium text-blue-gray-600">
                                        {{ row.price }} €
                                    </p>
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    <button @click="openGallery(row.images)" class="text-blue-500">
                                        <PhotoIcon class="h-8 w-8" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>


<script>
import { ref, computed, onMounted } from 'vue';
import { ChevronDownIcon, MagnifyingGlassIcon, PhotoIcon, ShoppingCartIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';

export default {
    components: {
        ChevronDownIcon,
        MagnifyingGlassIcon,
        PhotoIcon,
        ShoppingCartIcon,
    },
    setup() {
        const tableRow = ref([]);
        const speciesFilter = ref('');
        const breedFilter = ref('');
        const priceOrder = ref('asc');
        const ageOrder = ref('asc');
        const currentSort = ref('age');
        const speciesOptions = ref([]);
        const showTableData = ref(true);

        // Taux de TVA (par exemple 20%)
        const TVA_RATE = 0.20;

        // Rediriger vers le panel d'administration
        const redirectToPanel = () => {
            window.location.href = '/panel';
        };

        // Fonction pour récupérer les animaux depuis l'API
        const fetchAnimals = async () => {
            try {
                const response = await axios.get('http://127.0.0.1:8000/animals', {
                    params: {
                        available_only: true,
                    }
                });

                const data = response.data;

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
                        images: Array.isArray(animal.images)
                            ? animal.images.map(img => `/storage/${img}`)
                            : [],
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
            let filtered = [...tableRow.value];

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

        // Fonction pour ouvrir la galerie d'images
        const openGallery = (images) => {
            if (images && images.length > 0) {
                const imageGallery = document.createElement('div');
                imageGallery.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center';
                imageGallery.innerHTML = `
                <div class="bg-white p-4 rounded-lg">
                    <h2 class="text-lg mb-2 text-center">Galerie d'Images</h2>
                    <div class="flex flex-wrap justify-center mb-4">
                        ${images.map(image => `<img src="${image}" class="h-96 w-96 object-cover m-2 rounded" alt="Image de l'animal" />`).join('')}
                    </div>
                    <div class="flex justify-center">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="bg-red-500 text-white rounded px-4 py-2">Fermer</button>
                    </div>
                </div>
                `;
                document.body.appendChild(imageGallery);
            } else {
                alert('Aucune image à afficher.');
            }
        };

        // Compter le nombre d'animaux dans la table
        const animalCount = computed(() => tableRow.value.length);

        // Fonction pour changer l'ordre de tri par âge
        const toggleAgeOrder = () => {
            // Vider temporairement la table
            showTableData.value = false;
            // Indiquer que nous trions par âge
            currentSort.value = 'age';
            // Inverser l'ordre
            ageOrder.value = ageOrder.value === 'asc' ? 'desc' : 'asc';
            setTimeout(() => {
                // Réafficher après 50ms
                showTableData.value = true;
            }, 50);
        };

        // Fonction pour changer l'ordre de tri par prix
        const togglePriceOrder = () => {
            showTableData.value = false;
            currentSort.value = 'price';
            priceOrder.value = priceOrder.value === 'asc' ? 'desc' : 'asc';
            setTimeout(() => {
                showTableData.value = true;
            }, 50);
        };

        return {
            speciesFilter,
            breedFilter,
            priceOrder,
            ageOrder,
            currentSort,
            speciesOptions,
            filteredRows,
            toggleAgeOrder,
            togglePriceOrder,
            showTableData,
            redirectToPanel,
            animalCount,
            openGallery,
        };
    },
};
</script>
