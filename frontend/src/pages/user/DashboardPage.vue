<template>
    <AuthenticatedLayout>

        <div class="rounded-lg bg-white p-6 shadow">

            <h1 class="text-2xl font-bold">
                Dashboard
            </h1>

            <p class="mt-2 text-gray-600">
                Welcome back,
                {{ auth.user?.name }}
            </p>

        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '../../layouts/AuthenticatedLayout.vue'
import { useAuthStore } from '../../stores/authStore'

const auth = useAuthStore()
</script>