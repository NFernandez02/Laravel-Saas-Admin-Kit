<template>
    <AdminLayout>
        <div class="space-y-6">

            <div v-if="loading">
                Loading roles...
            </div>

            <template v-else>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">
                            Role Management
                        </h1>

                        <p class="text-gray-500">
                            Manage user roles.
                        </p>
                    </div>

                    <button @click="showCreateModal = true" class="rounded-lg bg-blue-600 px-4 py-2 text-white
           transition hover:bg-blue-700">
                        Create Role
                    </button>
                </div>

                <div class="rounded-lg border bg-white p-4 shadow-sm">
                    <input v-model="search" type="text" placeholder="Search roles..."
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
                                    Users Count
                                </th>

                                <th class="p-4 text-left">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr v-for="role in roles" :key="role.id" class="border-t">
                                <td class="p-4">
                                    {{ role.name }}
                                </td>

                                <td class="p-4">
                                    {{ role.users_count }}
                                </td>

                                <td class="p-4 space-x-2">

                                    <button @click="openEditModal(role)" class="rounded-lg bg-amber-500 px-3 py-1.5 text-sm text-white
                                                transition hover:bg-amber-600">
                                        Edit
                                    </button>

                                    <button @click="handleDelete(role)" class="rounded-lg bg-red-600 px-3 py-1.5 text-sm text-white
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
        <CreateRoleModal :show="showCreateModal" :permissions="permissions" :creating="creatingRole"
            @close="showCreateModal = false" @created="handleCreate" />
        <EditRoleModal :show="showEditModal" :permissions="permissions" :editing="editingRole" :role="selectedRole"
            @close="showEditModal = false" @updated="handleEdit" />
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { createRole, deleteRole, getRoles, updateRole } from '../../services/roleManagementService.js'
import EditUserModal from '../../components/admin/users/EditUserModal.vue'
import { getPermissions } from '../../services/permissionManagementService.js'
import CreateRoleModal from '../../components/admin/roles/CreateRoleModal.vue'
import EditRoleModal from '../../components/admin/roles/EditRoleModal.vue'
import AdminLayout from '../../layouts/AdminLayout.vue'

const loading = ref(true)
const roles = ref([])
const search = ref('')

const showCreateModal = ref(false)
const permissions = ref([])
const creatingRole = ref(false)

const showEditModal = ref(false)
const selectedRole = ref(null)
const editingRole = ref(false)

const deletingRole = ref(false)

const pagination = ref({
    current_page: 1,
    last_page: 1
})
const links = ref({})

async function loadRoles(page = 1) {
    const data = await getRoles(page)


    roles.value = data.data
    pagination.value = data.meta
    links.value = data.links
}

function nextPage() {
    if (!pagination.value.current_page) return

    loadRoles(
        pagination.value.current_page + 1
    )
}

function previousPage() {
    if (!pagination.value.current_page) return

    loadRoles(
        pagination.value.current_page - 1
    )
}

function openEditModal(role) {
    selectedRole.value = role
    showEditModal.value = true
}

async function handleCreate(roleData) {
    creatingRole.value = true
    try {
        loading.value = true
        await createRole(roleData)
        showCreateModal.value = false
        await loadRoles(pagination.value.current_page)
    } finally {
        loading.value = false
        creatingRole.value = false
    }

}

async function handleEdit(roleData) {
    editingRole.value = true
    try {
        loading.value = true
        await updateRole(roleData.id, roleData)
        showEditModal.value = false
        await loadRoles(pagination.value.current_page)
    } finally {
        loading.value = false
        editingRole.value = false
    }
}

async function handleDelete(role) {
    const confirmed = confirm(
        `Delete ${role.name}?`
    )
    if (!confirmed) {
        return
    }

    deletingRole.value = true

    try {
        loading.value = true
        await deleteRole(role.id)
        await loadRoles(pagination.value.current_page)
    } catch (err) {
        alert(err.response?.data?.message ?? 'Delete failed.')
    } finally {
        deletingRole.value = false
        loading.value = false
    }
}

onMounted(async () => {
    try {
        await loadRoles()
        const response = await getPermissions()
        permissions.value = response.data
    } finally {
        loading.value = false
    }
})

</script>