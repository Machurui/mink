<template>
    <section class="w-screen h-screen flex flex-col bg-gray-100">
        <div class="container mx-auto p-6 flex flex-col gap-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <nav
                    class="flex items-center justify-between backdrop-saturate-200 backdrop-blur-md bg-opacity-90 border border-gray-200 w-full max-w-full px-6 bg-white text-blue-gray-800 rounded-lg transition-all sticky top-4 z-40 py-4 shadow-lg shadow-blue-gray-500/10">
                    <div class="flex flex-col">
                        <h6 class="text-blue-gray-900 text-2xl font-semibold">Panneau d'administration du bétail</h6>
                        <small class="text-gray-600 font-normal mt-1 text-sm">
                            Liste des animaux disponibles
                        </small>
                    </div>
                    <div>
                        <button @click="redirectToHome"
                            class="bg-gradient-to-tr from-gray-900 to-gray-800 mx-2 text-white font-semibold py-2 px-5 rounded-md text-sm shadow-md hover:shadow-lg hover:scale-105 transition transform duration-300">
                            Accueil
                        </button>
                        <button @click="redirectToLogout"
                            class="bg-gradient-to-tr from-gray-900 to-gray-800 mx-2  text-white font-semibold py-2 px-5 rounded-md text-sm shadow-md hover:shadow-lg hover:scale-105 transition transform duration-300">
                            Logout
                        </button>
                    </div>
                </nav>
            </div>

            <!-- Table Section -->
            <div
                class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 overflow-hidden xl:col-span-2 border border-blue-gray-100 shadow-sm">
                <div
                    class="relative bg-clip-border rounded-xl overflow-hidden bg-transparent text-gray-700 shadow-none m-0 flex items-center justify-between p-6">
                    <!-- Actions Section -->
                    <div class="flex items-center gap-4 mt-4 justify-end">
                        <button @click="openModal('create')"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md">
                            Créer
                        </button>
                        <button @click="openModal('edit')" :disabled="selectedRows.length !== 1"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md disabled:opacity-50 disabled:pointer-events-none">
                            Modifier
                        </button>
                        <button @click="deleteSelected" :disabled="selectedRows.length === 0"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md disabled:opacity-50 disabled:pointer-events-none">
                            Supprimer
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-x-scroll max-h-[600px] px-0 pt-0 pb-2">
                    <table v-if="showTableData" class="w-full min-w-[640px] table-auto text-center">
                        <thead>
                            <tr>
                                <th class="border-b border-gray-300 p-4 text-center">
                                    <input type="checkbox" @change="toggleSelectAll" :checked="isAllSelected" />
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-center">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Nom
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-center">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Âge
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-center">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Espèce
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-center">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Race
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-center">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Description
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-center">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Statut
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-center">
                                    <p
                                        class="block antialiased font-sans text-[13px] font-medium uppercase text-blue-gray-400">
                                        Prix HT
                                    </p>
                                </th>
                                <th class="border-b border-blue-gray-50 py-3 px-6 text-center">
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
                                    <input type="checkbox" v-model="selectedRows" :value="row" />
                                </td>
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
                                        {{ row.status }}
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

        <!-- Modal Section -->
        <Modal v-if="showModal" :mode="modalMode" :animal="selectedAnimal" @close="showModal = false"
            @save="handleSave" />
    </section>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { PhotoIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';
import Modal from '../components/Modal.vue';

export default {
    components: {
        Modal,
        PhotoIcon
    },
    setup() {
        const tableRow = ref([]);
        const selectedRows = ref([]);
        const showTableData = ref(true);
        const showModal = ref(false);
        const modalMode = ref('create');
        const selectedAnimal = ref(null);

        // Fonction pour récupérer les animaux depuis l'API
        const fetchAnimals = async () => {
            try {
                const response = await axios.get('http://127.0.0.1:8000/animals');
                const data = response.data;
                tableRow.value = data.map(animal => ({
                    id: animal.id,
                    name: animal.name,
                    age: animal.age,
                    species: animal.species,
                    breed: animal.breed,
                    description: animal.description || 'Aucune description disponible',
                    status: animal.status,
                    price: animal.price ? parseFloat((animal.price).toFixed(2)) : 'N/A',
                    images: Array.isArray(animal.images)
                        ? animal.images.map(img => `/storage/${img}`)
                        : [],
                }));
            } catch (error) {
                console.error('Erreur lors de la récupération des animaux:', error);
            }
        };

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

        // Fonction pour ouvrir le modal
        const openModal = (mode) => {
            modalMode.value = mode;
            showModal.value = true;
            if (mode === 'edit' && selectedRows.value.length === 1) {
                selectedAnimal.value = selectedRows.value[0];
            } else {
                selectedAnimal.value = null;
            }
        };

        // Fonction pour supprimer les animaux sélectionnés
        const deleteSelected = async () => {
            if (selectedRows.value.length > 0) {
                const confirmDelete = confirm("Êtes-vous sûr de vouloir supprimer ces animaux ?");
                if (confirmDelete) {
                    try {
                        await Promise.all(selectedRows.value.map(animal => axios.delete(`http://127.0.0.1:8000/animals/${animal.id}`)));
                        await fetchAnimals();
                        selectedRows.value = [];
                    } catch (error) {
                        console.error('Erreur lors de la suppression des animaux:', error);
                    }
                }
            } else {
                alert("Aucun animal sélectionné pour la suppression.");
            }
        };

        // Fonction pour rediriger vers la page de déconnexion
        const redirectToLogout = async () => {
            try {
                // Envoie une requête POST à l'API pour se déconnecter
                const response = await axios.post('/logout');

                // Vérifie si la déconnexion est réussie
                if (response.status === 200) {
                    // Redirige vers la page de connexion si la déconnexion est réussie
                    window.location.href = '/signin';
                }
            } catch (error) {
                errorMessage.value = 'La déconnexion ne s\'est pas bien passé.Veuillez réessayer.';
            }
        };

        // Fonction pour rediriger vers la page d'accueil
        const redirectToHome = () => {
            window.location.href = '/';
        };

        // Fonction pour gérer la sélection de tous les animaux
        const isAllSelected = computed(() => selectedRows.value.length === tableRow.value.length);

        // Fonction pour basculer la sélection de tous les animaux
        const toggleSelectAll = () => {
            selectedRows.value = isAllSelected.value ? [] : [...tableRow.value];
        };

        // Fonction pour gérer l'enregistrement et la mise à jour des animaux
        const handleSave = async () => {
            await fetchAnimals();
            selectedRows.value = [];
            showModal.value = false;
        };

        // Appel de la fonction lors du montage du composant
        onMounted(fetchAnimals);

        return {
            showTableData,
            selectedRows,
            filteredRows: computed(() => tableRow.value),
            showModal,
            modalMode,
            selectedAnimal,
            openModal,
            handleSave,
            openGallery,
            deleteSelected,
            toggleSelectAll,
            isAllSelected,
            redirectToLogout,
            redirectToHome,
        };
    },
};
</script>
