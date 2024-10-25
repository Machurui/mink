<template>
    <section class="grid text-center h-screen items-center p-8">
        <div>
            <h3 class="text-3xl font-semibold text-blue-gray-700 mb-2">Sign In</h3>
            <p class="mb-16 text-gray-600 text-lg font-normal">
                Enter your email and password to sign in
            </p>
            <form @submit.prevent="handleSignIn" class="mx-auto max-w-md text-left">
                <div v-if="errorMessage" class="mb-4 text-red-600 text-center">{{ errorMessage }}</div>
                <div class="mb-6">
                    <label for="email" class="block text-gray-900 font-medium mb-2">Your Email</label>
                    <input id="email" v-model="email" type="email" placeholder="name@mail.com"
                        class="w-full px-4 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-900 font-medium mb-2">Password</label>
                    <div class="relative">
                        <input id="password" v-model="password" :type="passwordShown ? 'text' : 'password'"
                            placeholder="********"
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
                <button type="submit"
                    class="w-full bg-gray-700 text-white py-2 rounded-lg font-semibold text-lg hover:bg-gray-800 mt-6">
                    Sign In
                </button>
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

const togglePasswordVisibility = () => {
    passwordShown.value = !passwordShown.value;
};

const handleSignIn = async () => {
    try {
        const response = await axios.post('/login', {
            email: email.value,
            password: password.value,
        });

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
