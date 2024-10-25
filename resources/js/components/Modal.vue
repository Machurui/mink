<template>
    <section>
        <div open handler="close"
            class="fixed inset-0 z-[999] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300">
            <div
                class="relative mx-auto w-full max-w-[90vw] sm:max-w-[30rem] md:max-w-[34rem] lg:max-w-[38rem] max-h-[90vh] overflow-y-auto rounded-lg shadow-lg bg-white">
                <div class="relative flex flex-col bg-white">
                    <!-- Header du modal -->
                    <div
                        class="sticky top-0 flex justify-center items-center h-16 sm:h-20 bg-slate-800 text-white rounded-t-md shadow-md">
                        <h3 class="text-lg sm:text-2xl">
                            {{ mode === 'create' ? 'Ajouter un Animal' : 'Modifier l\'Animal' }}
                        </h3>
                        <IconButton color="gray" size="sm" variant="text" @click="close" class="absolute top-2 right-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </IconButton>
                    </div>

                    <!-- Formulaire de la modal -->
                    <div class="flex flex-col gap-4 p-4 sm:p-6">
                        <!-- Affichage des erreurs générales -->
                        <div v-if="errorMessage" class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                            {{ errorMessage }}
                        </div>

                        <!-- Champ Nom -->
                        <div class="w-full">
                            <label class="block mb-2 text-xs sm:text-sm text-slate-600">Nom</label>
                            <input v-model="formData.name" type="text" placeholder="Nom de l'animal"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm" />
                            <p v-if="validationErrors.name" class="text-red-600 text-xs">
                                {{ validationErrors.name }}
                            </p>
                        </div>

                        <!-- Champ Âge -->
                        <div class="w-full">
                            <label class="block mb-2 text-xs sm:text-sm text-slate-600">Âge</label>
                            <input v-model="formData.age" type="number" placeholder="Âge en années"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm" />
                            <p v-if="validationErrors.age" class="text-red-600 text-xs">
                                {{ validationErrors.age }}
                            </p>
                        </div>

                        <!-- Champ Espèce -->
                        <div class="w-full">
                            <label class="block mb-2 text-xs sm:text-sm text-slate-600">Espèce</label>
                            <input v-model="formData.species" type="text" placeholder="Espèce"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm" />
                            <p v-if="validationErrors.species" class="text-red-600 text-xs">
                                {{ validationErrors.species }}
                            </p>
                        </div>

                        <!-- Champ Race -->
                        <div class="w-full">
                            <label class="block mb-2 text-xs sm:text-sm text-slate-600">Race</label>
                            <input v-model="formData.breed" type="text" placeholder="Race de l'animal"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm" />
                            <p v-if="validationErrors.breed" class="text-red-600 text-xs">
                                {{ validationErrors.breed }}
                            </p>
                        </div>

                        <!-- Champ Description -->
                        <div class="w-full">
                            <label class="block mb-2 text-xs sm:text-sm text-slate-600">Description</label>
                            <textarea v-model="formData.description" placeholder="Description de l'animal"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm"></textarea>
                            <p v-if="validationErrors.description" class="text-red-600 text-xs">
                                {{ validationErrors.description }}
                            </p>
                        </div>

                        <!-- Champ Statut -->
                        <div class="w-full relative">
                            <label class="block mb-2 text-xs sm:text-sm text-slate-600">Statut</label>
                            <select v-model="formData.status"
                                class="w-full bg-transparent text-slate-700 text-sm border border-slate-200 rounded-md pl-3 pr-8 py-2 focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm cursor-pointer">
                                <option value="">Sélectionner un statut</option>
                                <option value="disponible">Disponible</option>
                                <option value="réservé">Réservé</option>
                                <option value="vendu">Vendu</option>
                            </select>
                            <p v-if="validationErrors.status" class="text-red-600 text-xs">
                                {{ validationErrors.status }}
                            </p>
                        </div>

                        <!-- Champ Prix -->
                        <div class="w-full">
                            <label class="block mb-2 text-xs sm:text-sm text-slate-600">Prix HT</label>
                            <input v-model="formData.price" type="number" placeholder="Prix HT"
                                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm" />
                            <p v-if="validationErrors.price" class="text-red-600 text-xs">
                                {{ validationErrors.price }}
                            </p>
                        </div>

                        <!-- Gestion des Images -->
                        <div class="w-full">
                            <label class="block mb-2 text-xs sm:text-sm text-slate-600">Images</label>
                            <div class="overflow-y-auto max-h-40 sm:max-h-56">
                                <div v-for="(preview, index) in imagePreviews" :key="index" class="mb-2 relative">
                                    <img :src="preview" alt="Image de l'animal"
                                        class="w-full h-28 sm:h-32 object-cover rounded-md shadow-sm">
                                    <button @click="removeImage(index)"
                                        class="absolute top-0 right-0 p-1 bg-red-500 rounded-full text-white">
                                        X
                                    </button>
                                </div>
                            </div>
                            <input type="file" @change="handleImageUpload" class="w-full text-slate-600" multiple />
                        </div>
                    </div>

                    <!-- Boutons du modal -->
                    <div class="p-4 sm:p-6 pt-0">
                        <button @click="save"
                            class="w-full rounded-md bg-slate-800 py-2 px-4 border border-transparent text-center text-xs sm:text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 active:bg-slate-700 disabled:pointer-events-none disabled:opacity-50"
                            type="button">
                            {{ mode === 'create' ? 'Ajouter' : 'Enregistrer' }}
                        </button>
                        <button @click="close"
                            class="w-full mt-4 rounded-md bg-slate-200 py-2 px-4 border border-transparent text-center text-xs sm:text-sm text-slate-800 transition-all shadow-md hover:shadow-lg focus:bg-slate-300 active:bg-slate-300 disabled:pointer-events-none disabled:opacity-50"
                            type="button">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>



<script>
import { ref, watch } from 'vue';
import axios from 'axios';

export default {
    props: {
        mode: String,
        animal: Object,
    },
    emits: ['close', 'save'],
    setup(props, { emit }) {
        const formData = ref({
            name: '',
            age: '',
            species: '',
            breed: '',
            description: '',
            status: '',
            price: '',
            images: [],
        });

        // Tableau pour stocker les URLs de prévisualisation des images
        const imagePreviews = ref([]);

        // Propriété pour afficher les messages d'erreur
        const errorMessage = ref('');

        // Stocker les erreurs de validation par champ
        const validationErrors = ref({});

        // A COMMENTER
        watch(
            () => props.animal,
            (newVal) => {
                if (newVal) {

                    Object.assign(formData.value, newVal);


                    formData.value.images = Array.isArray(newVal.images) ? newVal.images : JSON.parse(newVal.images || '[]');


                    imagePreviews.value = [];


                    if (formData.value.images.length > 0) {
                        imagePreviews.value = formData.value.images.map(image => `${image}`);
                    }
                }
            },
            { immediate: true }
        );

        // Fonctions pour gérer la fermeture
        const close = () => {
            errorMessage.value = '';
            validationErrors.value = {};
            emit('close');
        };

        // Fonction pour valider le formulaire
        const validateForm = () => {
            const errors = {};
            if (!formData.value.name) errors.name = 'Le nom est obligatoire.';
            if (!formData.value.age) errors.age = 'L\'âge est obligatoire.';
            if (!formData.value.species) errors.species = 'L\'espèce est obligatoire.';
            if (!formData.value.breed) errors.breed = 'La race est obligatoire.';
            if (!formData.value.description) errors.description = 'La description est obligatoire.';
            if (!formData.value.status) errors.status = 'Le statut est obligatoire.';
            if (!formData.value.price) errors.price = 'Le prix est obligatoire.';

            validationErrors.value = errors;
            return Object.keys(errors).length === 0;
        };

        // Fonction pour créer ou mettre à jour un animal
        const save = async () => {
            // Vérifier si le formulaire est valide
            if (!validateForm()) {
                errorMessage.value = 'Veuillez remplir tous les champs obligatoires.';
                return;
            }

            // Créer un objet FormData pour envoyer les données
            const form = new FormData();

            // Ajouter les autres champs à par les images
            Object.keys(formData.value).forEach(key => {
                if (key !== 'images') {
                    form.append(key, formData.value[key] || '');
                }
            });

            // Si nous sommes en mode "modification", ajouter les anciennes images
            if (props.mode === 'edit') {
                formData.value.images.forEach((image, index) => {
                    // Vérifier si l'image est une chaîne et supprimer le préfixe 'storage/'
                    if (typeof image === 'string') {
                        const modifiedImage = image.replace(/^\/storage\//, '');
                        form.append(`existing_image_${index}`, modifiedImage);
                    }
                });
            }

            // Ajouter chaque image individuellement avec une clé unique
            formData.value.images.forEach((image, index) => {
                if (image instanceof File) {
                    form.append(`image_${index}`, image);
                }
            });

            try {
                // Envoyer la requête pour créer ou mettre à jour l'animal
                const response = props.mode === 'create'
                    ? await axios.post('/animals', form, { headers: { 'Content-Type': 'multipart/form-data' } })
                    : await axios.post(`/animals/${props.animal.id}/update`, form, { headers: { 'Content-Type': 'multipart/form-data' } });
                emit('save', response.data.animal);
                close();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    errorMessage.value = Object.values(errors).flat().join(', ');
                } else {
                    errorMessage.value = "Erreur lors de la sauvegarde de l'animal.";
                }
            }
        };

        // Fonction pour gérer le téléchargement d'images
        const handleImageUpload = (event) => {
            const files = event.target.files;

            // Parcourir chaque fichier et ajouter uniquement les images
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                // Vérifier si le fichier est une image et n'est pas déjà dans la liste
                if (file && file.type.startsWith('image/') && !formData.value.images.some(img => img.name === file.name)) {

                    // Ajouter le fichier à la liste des images
                    formData.value.images.push(file);

                    // Créer un objet FileReader pour lire le fichier
                    const reader = new FileReader();

                    // Lire le fichier en tant que Data URL pour l'afficher en prévisualisation
                    reader.onload = (e) => {
                        imagePreviews.value.push(e.target.result);
                    };

                    // Lire le fichier
                    reader.readAsDataURL(file);
                }
            }
        };

        // Fonction pour supprimer une image en prévisualisation
        const removeImage = (index) => {
            formData.value.images.splice(index, 1);
            imagePreviews.value.splice(index, 1);
        };

        return {
            formData,
            close,
            save,
            handleImageUpload,
            removeImage,
            errorMessage,
            imagePreviews,
            validationErrors
        };
    },
};
</script>
