<template>
    <div class="min-h-screen bg-gray-100">

        <AppNavBar />

        <div class="flex">

            <AppSideBar />

            <main class="flex-1 p-6">
                <slot />
            </main>

        </div>

    </div>
</template>

<script setup>
import AppNavBar from '@/components/user/AppNavBar.vue';
import AppSideBar from '@/components/user/AppSideBar.vue';

</script>