<template>
    <div class="min-h-screen bg-gray-100">

        <AppNavbar />

        <div class="flex">

            <AppSidebar />

            <main class="flex-1 p-6">
                <slot/>
            </main>

        </div>

    </div>
</template>

<script setup>
import AppNavbar from '../components/AppNavbar.vue'
import AppSidebar from '../components/AppSidebar.vue'
</script>