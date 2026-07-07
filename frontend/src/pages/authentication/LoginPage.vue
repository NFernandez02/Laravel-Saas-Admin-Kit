<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md rounded-lg border p-8 shadow">
            <h1 class="text-2xl font-bold mb-6">
                Laravel SaaS Admin Kit
            </h1>
            <form @submit.prevent="submitLogin">
                <input v-model="email" type="email" placeholder="Email" class="w-full border rounded p-2 mb-3">

                <input v-model="password" type="password" placeholder="Password" class="w-full border rounded p-2 mb-4">
                <div v-if="error" class="mb-4 text-red-600 text-sm">
                    {{ error }}
                </div>

                <button type="submit" class="w-full rounded bg-black text-white p-2">Login</button>
            </form>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { login } from '../../services/authService';

const router = useRouter()

const email = ref('')
const password = ref('')
const error = ref('')

const submitLogin = async () => {
    try {
        error.value = ''

        const result = await login({
            email: email.value,
            password: password.value
        })

        if(result.requires_2fa){
            localStorage.setItem(
                'challenge_token',
                result.challenge_token
            )
            router.push('/challenge')
            return
        }

        localStorage.setItem(
            'token',
            result.token
        )

        router.push('/dashboard')
    } catch (err){
        error.value = 
            err.response?.data?.message ?? 'Login failed.'
    }
}
</script>