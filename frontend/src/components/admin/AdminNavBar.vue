<template>
    <header class="flex items-center justify-between border-b bg-white px-6 py-4">
        <h1 class="text-xl font-semibold">
            Laravel SaaS Admin Kit
        </h1>

        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">
                {{ auth.user?.name }}
            </span>

            <button @click="handleReturn" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600">
                Return as User
            </button>
        </div>
    </header>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/authStore';
const router = useRouter()
const auth = useAuthStore()

async function handleReturn() {
    router.push('/')
}
</script>