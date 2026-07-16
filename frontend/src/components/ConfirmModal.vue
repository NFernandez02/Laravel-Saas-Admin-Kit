<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    >
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ title }}
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    {{ message }}
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="$emit('cancel')"
                    :disabled="loading"
                    class="rounded-lg border border-gray-300 px-4 py-2
                           text-sm font-medium
                           transition hover:bg-gray-100
                           disabled:cursor-not-allowed
                           disabled:opacity-50"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    @click="$emit('confirm')"
                    :disabled="loading"
                    class="rounded-lg bg-red-600 px-4 py-2
                           text-sm font-medium text-white
                           transition hover:bg-red-700
                           disabled:cursor-not-allowed
                           disabled:bg-gray-400"
                >
                    {{ loading ? 'Deleting...' : 'Delete' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: Boolean,
    title: String,
    message: String,
    loading: Boolean,
})

defineEmits([
    'cancel',
    'confirm',
])
</script>