<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-xl shadow p-8">
            <h1 class="text-2xl font-bold text-center mb-2">
                Two-Factor Authentication
            </h1>

            <p class="text-gray-500 text-center mb-6">
                Enter the code from your authenticator app.
            </p>

            <form @submit.prevent="verifyCode">
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium">
                        Authentication Code
                    </label>

                    <input v-model="code" type="text" maxlength="6"
                        class="w-full border rounded px-3 py-2 text-center tracking-widest" />
                </div>

                <div v-if="error" class="mb-4 text-red-600 text-sm">
                    {{ error }}
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Verify
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { verifyTwoFactor } from '../../services/authService';
import { ref } from 'vue';
import { useAuthStore } from '../../stores/authStore';

const router = useRouter()

const code = ref('')
const error = ref('')

const verifyCode = async () => {
    try {
        error.value = ''
        
        const challengeToken = localStorage.getItem('challenge_token')

        const result = await verifyTwoFactor({
            challenge_token: challengeToken,
            code: code.value
        })
        const auth = useAuthStore()
        auth.setToken(result.token)
        auth.setUser(result.user)
        auth.clearChallenge()
        localStorage.removeItem(
            'challenge_token'
        )

        router.push('/')
    } catch (err){
        error.value = err.response?.data?.message ?? 'Invalid code.'
    }
}
</script>