<template>
    <AdminLayout>
        <div class="space-y-6">

            <div>
                <h1 class="text-3xl font-bold">
                    Admin Dashboard
                </h1>

                <p class="text-gray-500">
                    System overview and recent activity.
                </p>
            </div>

            <div v-if="loading">
                Loading dashboard...
            </div>

            <template v-else>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                    <div class="rounded-lg border bg-white p-6 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Total Users
                        </p>

                        <h2 class="mt-2 text-3xl font-bold">
                            {{ dashboard.stats.users_count }}
                        </h2>
                    </div>

                    <div class="rounded-lg border bg-white p-6 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Total Roles
                        </p>

                        <h2 class="mt-2 text-3xl font-bold">
                            {{ dashboard.stats.roles_count }}
                        </h2>
                    </div>

                    <div class="rounded-lg border bg-white p-6 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Total Admins
                        </p>

                        <h2 class="mt-2 text-3xl font-bold">
                            {{ dashboard.stats.admins_count }}
                        </h2>
                    </div>

                </div>

                <div class="rounded-lg border bg-white p-6 shadow-sm">

                    <h2 class="mb-4 text-xl font-semibold">
                        Recent Activity
                    </h2>

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead>
                                <tr class="border-b text-left">
                                    <th class="p-3">User</th>
                                    <th class="p-3">Action</th>
                                    <th class="p-3">Description</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr v-for="log in dashboard.latest_logs" :key="log.id" class="border-b">
                                    <td class="p-3">
                                        {{ log.user?.name ?? 'Deleted User' }}
                                    </td>

                                    <td class="p-3">
                                        {{ log.action }}
                                    </td>

                                    <td class="p-3">
                                        {{ log.description }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </template>

        </div>
    </AdminLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { getDashboard } from '../../services/adminService'
import AdminLayout from '../../layouts/AdminLayout.vue'

const loading = ref(true)
const dashboard = ref(null)

onMounted(async () => {
    try {
        dashboard.value = await getDashboard()
    } finally {
        loading.value = false
    }
})
</script>