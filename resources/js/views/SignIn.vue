<template>
    <section class="grid text-center h-screen items-center p-8">
        <div>
            <h3 class="text-3xl font-semibold text-blue-gray-700 mb-2">Connexion</h3>
            <p class="mb-16 text-gray-600 text-lg font-normal">
                Veuillez saisir votre e-mail et votre mot de passe
            </p>
            <form @submit.prevent="handleSignIn" class="mx-auto max-w-md text-left">
                <div v-if="errorMessage" class="mb-4 text-red-600 text-center">{{ errorMessage }}</div>
                <div class="mb-6">
                    <label for="email" class="block text-gray-900 font-medium mb-2">E-mail</label>
                    <input id="email" v-model="email" type="email" placeholder="name@mail.com" required
                        class="w-full px-4 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-900 font-medium mb-2">Mot de passe</label>
                    <div class="relative">
                        <input id="password" v-model="password" :type="passwordShown ? 'text' : 'password'"
                            placeholder="********" required
                            class="w-full px-4 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                        <button type="button" @click="togglePasswordVisibility"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-600 focus:outline-none">
                            <span v-if="passwordShown">
                                <EyeIcon class="h-5 w-5" />
                            </span>
                            <span v-else>
                                <EyeSlashIcon class="h-5 w-5" />
                            </span>
                        </button>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="w-full my-2 bg-gradient-to-tr from-gray-900 to-gray-800 text-white py-2 rounded-lg font-semibold text-lg hover:bg-gray-900 mt-6">
                        Connexion
                    </button>
                    <button
                        class="w-full my-2 flex items-center justify-center rounded-md border border-slate-300 py-2 px-4 text-center text-lg transition-all shadow-sm hover:shadow-lg text-slate-600 hover:text-white hover:bg-slate-800 hover:border-slate-800 focus:text-white focus:bg-slate-800 focus:border-slate-800 active:border-slate-800 active:text-white active:bg-slate-800 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        type="button" @click="goBack">
                        Accueil

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-4 h-4 ml-1.5">
                            <path fill-rule="evenodd"
                                d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/solid';

const email = ref('');
const password = ref('');
const passwordShown = ref(false);
const errorMessage = ref('');

// Fonction pour afficher ou masquer le mot de passe
const togglePasswordVisibility = () => {
    passwordShown.value = !passwordShown.value;
};

// Fonction pour rediriger vers la page d'accueil
const goBack = () => {
    window.location.href = '/';
};

// Fonction pour gérer la connexion
const handleSignIn = async () => {
    try {
        // Envoie une requête POST à l'API pour se connecter
        const response = await axios.post('/login', {
            email: email.value,
            password: password.value,
        });

        // Vérifie si la connexion est réussie
        if (response.status === 200) {
            // Redirige vers le web panel si la connexion est réussie
            window.location.href = '/panel';
        }
    } catch (error) {
        if (error.response && error.response.status === 401) {
            errorMessage.value = 'Mauvaises informations d\'identification.Veuillez réessayer.';
        } else {
            errorMessage.value = 'Une erreur est survenue. Veuillez réessayer plus tard.';
        }
    }
};
</script>
