<template>
    <div class="space-y-6">

        <div v-if="loading">
            Loading users...
        </div>

        <template v-else>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">
                        User Management
                    </h1>

                    <p class="text-gray-500">
                        Manage system users.
                    </p>
                </div>

                <button @click="showCreateModal = true" class="rounded-lg bg-blue-600 px-4 py-2 text-white
           transition hover:bg-blue-700">
                    Create User
                </button>
            </div>

            <div class="rounded-lg border bg-white p-4 shadow-sm">
                <input v-model="search" type="text" placeholder="Search users..." class="w-full rounded-lg border p-2">
            </div>

            <div class="overflow-hidden rounded-lg border bg-white shadow-sm">

                <table class="min-w-full">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-left">
                                Name
                            </th>

                            <th class="p-4 text-left">
                                Email
                            </th>

                            <th class="p-4 text-left">
                                Role
                            </th>

                            <th class="p-4 text-left">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr v-for="user in users" :key="user.id" class="border-t">
                            <td class="p-4">
                                {{ user.name }}
                            </td>

                            <td class="p-4">
                                {{ user.email }}
                            </td>

                            <td class="p-4">
                                {{ user.role.name }}
                            </td>

                            <td class="p-4 space-x-2">

                                <button 
                                @click="openEditModal(user)"
                                class="rounded-lg bg-amber-500 px-3 py-1.5 text-sm text-white
                                                transition hover:bg-amber-600">
                                    Edit
                                </button>

                                <button 
                                @click="handleDelete(user)"
                                class="rounded-lg bg-red-600 px-3 py-1.5 text-sm text-white
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
    <CreateUserModal :show="showCreateModal" :roles="roles" :creating="creatingUser" @close="showCreateModal = false"
        @created="handleCreate" />
    <EditUserModal :show="showEditModal" :roles="roles" :editing="editingUser" :user="selectedUser" @close="showEditModal = false"
        @updated="handleEdit" />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { createUser, deleteUser, getUsers, updateUser } from '../../services/userManagementService'
import CreateUserModal from '../../components/admin/users/CreateUserModal.vue'
import { getRoles } from '../../services/roleManagementService.js'
import EditUserModal from '../../components/admin/users/EditUserModal.vue'

const loading = ref(true)
const users = ref([])
const search = ref('')

const showCreateModal = ref(false)
const roles = ref([])
const creatingUser = ref(false)

const showEditModal = ref(false)
const selectedUser = ref(null)
const editingUser = ref(false)

const deletingUser = ref(false)

const pagination = ref({
    current_page: 1,
    last_page: 1
})
const links = ref({})

async function loadUsers(page = 1) {
    const data = await getUsers(page)

    console.log(data)

    users.value = data.data
    pagination.value = data.meta
    links.value = data.links
}

function nextPage() {
    if (!pagination.value.current_page) return

    loadUsers(
        pagination.value.current_page + 1
    )
}

function previousPage() {
    if (!pagination.value.current_page) return

    loadUsers(
        pagination.value.current_page - 1
    )
}

function openEditModal(user){
    selectedUser.value = user
    showEditModal.value = true
}

async function handleCreate(userData) {
    creatingUser.value = true
    try {
        loading.value = true
        await createUser(userData)
        showCreateModal.value = false
        await loadUsers(pagination.value.current_page)
    } finally {
        loading.value = false
        creatingUser.value = false
    }

}

async function handleEdit(userData) {
    editingUser.value = true
    try{
        loading.value = true
        await updateUser(userData.id, userData)
        showEditModal.value = false
        await loadUsers(pagination.value.current_page)
    } finally {
        loading.value = false
        editingUser.value = false
    }
}

async function handleDelete(user) {
    const confirmed = confirm(
        `Delete ${user.name}?`
    )
    if(!confirmed){
        return
    }

    deletingUser.value = true

    try{
        loading.value = true
        await deleteUser(user.id)
        await loadUsers(pagination.value.current_page)
    } finally {
        deletingUser.value = false
        loading.value = false
    }
}

onMounted(async () => {
    try {
        await loadUsers()
        const response = await getRoles()
        roles.value = response.data
    } finally {
        loading.value = false
    }
})

</script>