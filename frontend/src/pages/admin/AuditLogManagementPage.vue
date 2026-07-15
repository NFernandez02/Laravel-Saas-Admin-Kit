<template>
    <AdminLayout>
        <div class="space-y-6">

            <div v-if="loading">
                Loading logs...
            </div>

            <template v-else>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">
                            Audit Logs
                        </h1>

                        <p class="text-gray-500">
                            Check previous logs.
                        </p>
                    </div>

                </div>

                <div class="rounded-lg border bg-white p-4 shadow-sm">
                    <form @submit.prevent="loadLogs(1)" class="flex gap-2">
                        <input v-model="search" type="text" placeholder="Search logs..." class="flex-1 rounded-lg border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:outline-none">

                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white
               transition hover:bg-blue-700">
                            Search
                        </button>
                    </form>
                </div>

                <div class="overflow-hidden rounded-lg border bg-white shadow-sm">

                    <table class="min-w-full">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-left">
                                    User
                                </th>

                                <th class="p-4 text-left">
                                    Action
                                </th>

                                <th class="p-4 text-left">
                                    Target
                                </th>

                                <th class="p-4 text-left">
                                    Description
                                </th>

                                <th class="p-4 text-left">
                                    Date
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr v-for="log in logs" :key="log.id" class="border-t">
                                <td class="p-4">
                                    {{ log.user.name }}
                                </td>

                                <td class="p-4">
                                    {{ log.action }}
                                </td>

                                <td class="p-4">
                                    {{ log.target_type }}
                                </td>

                                <td class="p-4">
                                    {{ log.description }}
                                </td>

                                <td class="p-4">
                                    {{ log.date }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

                <div class="flex justify-end gap-2">
                    <button @click="previousPage" :disabled="pagination?.current_page === 1" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium
                            transition hover:bg-gray-100
                            disabled:cursor-not-allowed
                            disabled:opacity-50">
                        Previous
                    </button>
                    <span class="rounded-lg border bg-gray-50 px-4 py-2 text-sm text-gray-600">
                        Page {{ pagination.current_page }}
                        of
                        {{ pagination.last_page }}
                    </span>
                    <button @click="nextPage" :disabled="pagination?.current_page === pagination?.last_page" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium
                            transition hover:bg-gray-100
                            disabled:cursor-not-allowed
                            disabled:opacity-50">
                        Next
                    </button>

                </div>
            </template>

        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { getLogs } from '../../services/logManagementService.js'

const loading = ref(true)
const logs = ref([])
const search = ref('')

const pagination = ref({
    current_page: 1,
    last_page: 1
})
const links = ref({})

async function loadLogs(page = 1) {
    const data = await getLogs(page, search.value)

    console.log(data)

    logs.value = data.data
    pagination.value = data.meta
    links.value = data.links

    if (
        data.meta.last_page > 0 &&
        page > data.meta.last_page
    ) {
        return loadLogs(
            data.meta.last_page
        )
    }
}

function nextPage() {
    if (!pagination.value.current_page) return

    loadLogs(
        pagination.value.current_page + 1, search.value
    )
}

function previousPage() {
    if (!pagination.value.current_page) return

    loadLogs(
        pagination.value.current_page - 1, search.value
    )
}

onMounted(async () => {
    try {
        await loadLogs()
    } finally {
        loading.value = false
    }
})

</script>