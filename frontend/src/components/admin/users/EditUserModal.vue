<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">
                    Edit User
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

                    <input v-model="form.name" class="w-full rounded-lg border p-2" :disabled="editing">
                </div>

                <div class="mb-6">
                    <label class="mb-1 block text-sm">
                        Role
                    </label>

                    <select v-model="form.role_id" class="w-full rounded-lg border p-2" :disabled="editing">
                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>
                    </select>
                </div>

                <button :disabled="editing" class="w-full rounded-lg bg-blue-600 py-2 text-white
                            transition
                            hover:bg-blue-700
                            disabled:cursor-not-allowed
                            disabled:bg-gray-400">
                    {{ editing ? 'Editing...' : 'Edit User' }}
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
    user: Object,
    editing: Boolean
})

const emit = defineEmits([
    'close',
    'updated',
])

const form = reactive({
    name: '',
    role_id: null,
})

async function submit() {
    emit('updated', {
        id: props.user.id,
        name: form.name,
        role_id: form.role_id
    })
}

function resetForm(){
    form.name = '',
    form.role_id = null
}


watch(
    () => props.show, 
    (show) => {
        if(!show){
            resetForm()
            return
        }
        if(props.user){
            form.name = props.user.name
            form.role_id = props.user.role.id
        }
    }
    
)

watch(
    () => props.user,
    (user) => {
        if(user){
            if(!user) return
            form.name = user.name
            form.role_id = user.role.id
        }
    },
    { immediate: true }
)
</script>