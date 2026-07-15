<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">
                    Edit Permission
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

                <button :disabled="editing" class="w-full rounded-lg bg-blue-600 py-2 text-white
                            transition
                            hover:bg-blue-700
                            disabled:cursor-not-allowed
                            disabled:bg-gray-400">
                    {{ editing ? 'Editing...' : 'Edit Role' }}
                </button>

            </form>

        </div>
    </div>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    permission: Object,
    editing: Boolean
})

const emit = defineEmits([
    'close',
    'updated',
])

const form = reactive({
    name: '',
    permissions: [],
})

async function submit() {
    emit('updated', {
        id: props.permission.id,
        name: form.name,
    })
}

function resetForm() {
    form.name = ''
}


watch(
    () => props.show,
    (show) => {
        if (!show) {
            resetForm()
        }
    }
)

watch(
    () => props.permission,
    (permission) => {
        if(permission){
            if(!permission) return
            form.name = permission.name
        }
    },
    { immediate: true }
)
</script>