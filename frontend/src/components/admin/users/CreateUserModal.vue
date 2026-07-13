<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">
                    Create User
                </h2>

                <button @click="$emit('close')" class="text-gray-500 hover:text-black">
                    ✕
                </button>
            </div>

            <form @submit.prevent="submit">

                <div class="mb-4">
                    <label class="mb-1 block text-sm">
                        Name
                    </label>

                    <input v-model="form.name" class="w-full rounded-lg border p-2" :disabled="creating">
                </div>

                <div class="mb-4">
                    <label class="mb-1 block text-sm">
                        Email
                    </label>

                    <input v-model="form.email" type="email" class="w-full rounded-lg border p-2" :disabled="creating">
                </div>

                <div class="mb-4">
                    <label class="mb-1 block text-sm">
                        Password
                    </label>

                    <input v-model="form.password" type="password" class="w-full rounded-lg border p-2"
                        :disabled="creating">
                </div>

                <div class="mb-6">
                    <label class="mb-1 block text-sm">
                        Role
                    </label>

                    <select v-model="form.role_id" class="w-full rounded-lg border p-2" :disabled="creating">
                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>
                    </select>
                </div>

                <button :disabled="creating" class="w-full rounded-lg bg-blue-600 py-2 text-white
                            transition
                            hover:bg-blue-700
                            disabled:cursor-not-allowed
                            disabled:bg-gray-400">
                    {{ creating ? 'Creating...' : 'Create User' }}
                </button>

            </form>

        </div>
    </div>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    roles: Array,
    creating: Boolean
})

const emit = defineEmits([
    'close',
    'created',
])

const form = reactive({
    name: '',
    email: '',
    password: '',
    role_id: null,
})

async function submit() {
    emit('created', {
        ...form
    })
}

function resetForm(){
    form.name = '',
    form.email = '',
    form.password = '',
    form.role_id = null
}


watch(
    () => props.show,
    (show) => {
        if(!show){
            resetForm()
        }
    }
)
</script>