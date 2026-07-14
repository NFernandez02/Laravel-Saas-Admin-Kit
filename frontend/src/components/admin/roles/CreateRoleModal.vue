<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">
                    Create Role
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
                        Permissions
                    </label>
                    <div class="grid grid-cols-2 gap-3">

                        <label v-for="permission in permissions" :key="permission.id"
                            class="flex items-center gap-2 rounded border p-3 hover:bg-gray-50">
                            <input type="checkbox" v-model="form.permissions" :value="permission.id" :disabled="creating">

                            <span class="text-sm">
                                {{ permission.name }}
                            </span>
                        </label>

                    </div>
                </div>

                <button :disabled="creating" class="w-full rounded-lg bg-blue-600 py-2 text-white
                            transition
                            hover:bg-blue-700
                            disabled:cursor-not-allowed
                            disabled:bg-gray-400">
                    {{ creating ? 'Creating...' : 'Create Role' }}
                </button>

            </form>

        </div>
    </div>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    permissions: Array,
    creating: Boolean
})

const emit = defineEmits([
    'close',
    'created',
])

const form = reactive({
    name: '',
    permissions: [],
})

async function submit() {
    emit('created', {
        ...form
    })
}

function resetForm() {
    form.name = '',
    form.permissions = []
}


watch(
    () => props.show,
    (show) => {
        if (!show) {
            resetForm()
        }
    }
)
</script>