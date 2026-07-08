<template>
    <aside class="min-h-screen w-64 border-r bg-white">
        <nav class="flex flex-col gap-2 p-4">

            <RouterLink to="/" class="rounded px-4 py-2 hover:bg-gray-100">
                Dashboard
            </RouterLink>

            <RouterLink to="/profile" class="rounded px-4 py-2 hover:bg-gray-100">
                Profile
            </RouterLink>

            <RouterLink v-if="auth.user?.role === 'admin'" to="/admin"
                class="rounded bg-blue-50 px-4 py-2 text-blue-700 hover:bg-blue-100">
                Admin Mode
            </RouterLink>

        </nav>
    </aside>
</template>

<script setup>
import { useAuthStore } from '../stores/authStore'

const auth = useAuthStore()
</script>