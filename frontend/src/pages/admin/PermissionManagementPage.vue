<template>
    <AdminLayout>
        <div class="space-y-6">

            <div v-if="loading">
                Loading permissions...
            </div>

            <template v-else>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">
                            Permission Management
                        </h1>

                        <p class="text-gray-500">
                            Manage role permissions.
                        </p>
                    </div>

                    <button @click="showCreateModal = true" class="rounded-lg bg-blue-600 px-4 py-2 text-white
           transition hover:bg-blue-700">
                        Create Permission
                    </button>
                </div>

                <div class="rounded-lg border bg-white p-4 shadow-sm">
                    <input v-model="search" type="text" placeholder="Search permissions..."
                        class="w-full rounded-lg border p-2">
                </div>

                <div class="overflow-hidden rounded-lg border bg-white shadow-sm">

                    <table class="min-w-full">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-left">
                                    Name
                                </th>

                                <th class="p-4 text-left">
                                    Roles Count
                                </th>

                                <th class="p-4 text-left">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr v-for="permission in permissions" :key="permission.id" class="border-t">
                                <td class="p-4">
                                    {{ permission.name }}
                                </td>

                                <td class="p-4">
                                    {{ permission.roles_count }}
                                </td>

                                <td class="p-4 space-x-2">

                                    <button @click="openEditModal(permission)" class="rounded-lg bg-amber-500 px-3 py-1.5 text-sm text-white
                                                transition hover:bg-amber-600">
                                        Edit
                                    </button>

                                    <button @click="handleDelete(permission)" class="rounded-lg bg-red-600 px-3 py-1.5 text-sm text-white
                                                transition hover:bg-red-700">
                                        Delete
                                    </button>

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
        <CreatePermissionModal :show="showCreateModal" :creating="creatingPermission" @close="showCreateModal = false"
            @created="handleCreate" />
        <EditPermissionModal :show="showEditModal" :editing="editingPermission" :permission="selectedPermission"
            @close="showEditModal = false" @updated="handleEdit" />
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { createPermission, deletePermission, getPermissions, updatePermission } from '../../services/permissionManagementService.js'
import CreatePermissionModal from '../../components/admin/permissions/CreatePermissionModal.vue'
import EditPermissionModal from '../../components/admin/permissions/EditPermissionModal.vue'
import AdminLayout from '../../layouts/AdminLayout.vue'

const loading = ref(true)
const permissions = ref([])
const search = ref('')

const showCreateModal = ref(false)
const creatingPermission = ref(false)

const showEditModal = ref(false)
const selectedPermission = ref(null)
const editingPermission = ref(false)

const deletingPermission = ref(false)

const pagination = ref({
    current_page: 1,
    last_page: 1
})
const links = ref({})

async function loadPermissions(page = 1) {
    const data = await getPermissions(page)


    permissions.value = data.data
    pagination.value = data.meta
    links.value = data.links
}

function nextPage() {
    if (!pagination.value.current_page) return

    loadPermissions(
        pagination.value.current_page + 1
    )
}

function previousPage() {
    if (!pagination.value.current_page) return

    loadPermissions(
        pagination.value.current_page - 1
    )
}

function openEditModal(permission) {
    selectedPermission.value = permission
    showEditModal.value = true
}

async function handleCreate(permissionData) {
    creatingPermission.value = true
    try {
        loading.value = true
        await createPermission(permissionData)
        showCreateModal.value = false
        await loadPermissions(pagination.value.current_page)
    } finally {
        loading.value = false
        creatingPermission.value = false
    }

}

async function handleEdit(permissionData) {
    editingPermission.value = true
    try {
        loading.value = true
        await updatePermission(permissionData.id, permissionData)
        showEditModal.value = false
        await loadPermissions(pagination.value.current_page)
    } finally {
        loading.value = false
        editingPermission.value = false
    }
}

async function handleDelete(permission) {
    const confirmed = confirm(
        `Delete ${permission.name}?`
    )
    if (!confirmed) {
        return
    }

    deletingPermission.value = true

    try {
        loading.value = true
        await deletePermission(permission.id)
        await loadPermissions(pagination.value.current_page)
    } catch (err) {
        alert(err.response?.data?.message ?? 'Delete failed.')
    } finally {
        deletingPermission.value = false
        loading.value = false
    }
}

onMounted(async () => {
    try {
        await loadPermissions()
    } finally {
        loading.value = false
    }
})

</script>