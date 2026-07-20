<template>
    <UserLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold">
                    Profile Settings
                </h1>

                <p class="text-gray-500">
                    Manage your account information and password.
                </p>
            </div>

            <div v-if="loading" class="rounded-lg border bg-white p-6">
                Loading profile...
            </div>

            <div v-else class="grid gap-6 lg:grid-cols-2">

                <!-- PROFILE CARD -->
                <div class="rounded-xl border bg-white p-6 shadow-sm">

                    <h2 class="mb-6 text-xl font-semibold">
                        Profile Information
                    </h2>

                    <form @submit.prevent="saveProfile" class="space-y-4">

                        <div class="flex flex-col items-center gap-4">

                            <img :src="avatarPreview" class="h-28 w-28 rounded-full object-cover border">

                            <input type="file" accept="image/*" @change="handleAvatar">

                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Name
                            </label>

                            <input v-model="profileForm.name" type="text" class="w-full rounded-lg border p-2">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Email
                            </label>

                            <input v-model="profileForm.email" type="email" class="w-full rounded-lg border p-2">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Bio
                            </label>

                            <textarea v-model="profileForm.bio" rows="4" maxlength="100"
                                class="w-full rounded-lg border p-2" />
                        </div>

                        <button :disabled="savingProfile"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700 disabled:bg-gray-400">
                            {{ savingProfile ? 'Saving...' : 'Save Profile' }}
                        </button>

                    </form>

                </div>

                <!-- PASSWORD CARD -->
                <div class="rounded-xl border bg-white p-6 shadow-sm">

                    <h2 class="mb-6 text-xl font-semibold">
                        Change Password
                    </h2>

                    <form @submit.prevent="changePassword" class="space-y-4">

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Current Password
                            </label>

                            <input v-model="passwordForm.current_password" type="password"
                                class="w-full rounded-lg border p-2">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                New Password
                            </label>

                            <input v-model="passwordForm.password" type="password" class="w-full rounded-lg border p-2">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Confirm Password
                            </label>

                            <input v-model="passwordForm.password_confirmation" type="password"
                                class="w-full rounded-lg border p-2">
                        </div>

                        <button :disabled="savingPassword"
                            class="rounded-lg bg-green-600 px-4 py-2 text-white transition hover:bg-green-700 disabled:bg-gray-400">
                            {{ savingPassword ? 'Updating...' : 'Update Password' }}
                        </button>

                    </form>

                </div>

                <!-- QR Code CARD -->
                <div class="rounded-xl border bg-white p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold">
                            Two-Factor Authentication
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Add an extra layer of security to your account.
                        </p>
                    </div>

                    <!-- Disabled State -->
                    <div v-if="!twoFactorEnabled && !qrCode">
                        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm text-amber-800">
                                Two-factor authentication is currently disabled.
                            </p>
                        </div>

                        <button @click="setupTwoFactor" :disabled="settingUpTwoFactor"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700 disabled:bg-gray-400">
                            {{
                                settingUpTwoFactor
                                    ? 'Generating QR Code...'
                                    : 'Enable Two-Factor Authentication'
                            }}
                        </button>
                    </div>

                    <!-- Setup State -->
                    <div v-else-if="!twoFactorEnabled && qrCode">
                        <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4">
                            <p class="text-sm text-blue-800">
                                Scan this QR code with Google Authenticator, Authy,
                                Microsoft Authenticator, or another TOTP application.
                            </p>
                        </div>

                        <div class="mb-6 flex justify-center rounded-xl border bg-gray-50 p-6" v-html="qrCode"></div>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium">
                                    Verification Code
                                </label>

                                <input v-model="verificationCode" type="text" maxlength="6" placeholder="123456"
                                    class="w-full rounded-lg border p-2">
                            </div>

                            <div class="flex gap-3">
                                <button @click="confirmTwoFactor" :disabled="confirmingTwoFactor"
                                    class="rounded-lg bg-green-600 px-4 py-2 text-white transition hover:bg-green-700">
                                    {{
                                        confirmingTwoFactor
                                            ? 'Verifying...'
                                            : 'Confirm & Enable'
                                    }}
                                </button>

                                <button @click="disableTwoFactor" class="rounded-lg border px-4 py-2">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Enabled State -->
                    <div v-else>
                        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4">
                            <p class="text-sm text-green-800">
                                Two-factor authentication is enabled.
                            </p>
                        </div>

                        <button @click="disableTwoFactor"
                            class="rounded-lg bg-red-600 px-4 py-2 text-white transition hover:bg-red-700">
                            Disable Two-Factor Authentication
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </UserLayout>

</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { confirm2FA, disable2FA, getProfile, updateProfile } from '../../services/profileService'
import { updatePassword } from '../../services/passwordService.js'
import { setupQR } from '../../services/profileService'
import UserLayout from '../../layouts/UserLayout.vue'

const loading = ref(true)

const savingProfile = ref(false)
const savingPassword = ref(false)

const avatarPreview = ref('')

const twoFactorEnabled = ref(false)
const qrCode = ref('')
const verificationCode = ref('')
const settingUpTwoFactor = ref(false)
const confirmingTwoFactor = ref(false)

const profileForm = reactive({
    name: '',
    email: '',
    bio: '',
    avatar: null,
})

const passwordForm = reactive({
    current_password: '',
    password: '',
    password_confirmation: '',
})

function handleAvatar(event) {
    const file = event.target.files?.[0]

    if (!file) return

    profileForm.avatar = file

    avatarPreview.value = URL.createObjectURL(file)
}

async function loadProfile() {
    const profile = await getProfile()

    profileForm.name = profile.data.name
    profileForm.email = profile.data.email
    profileForm.bio = profile.data.bio
    avatarPreview.value = profile.data.avatar_url

    twoFactorEnabled.value = profile.data.two_factor_enabled
    if (profile.data.two_factor_setup_pending) {
        qrCode.value = profile.qr_code
    }
}

async function saveProfile() {
    savingProfile.value = true

    try {

        await updateProfile(profileForm)

    } finally {
        savingProfile.value = false
    }
}

async function changePassword() {
    savingPassword.value = true

    try {

        await updatePassword(passwordForm)

        passwordForm.current_password = ''
        passwordForm.password = ''
        passwordForm.password_confirmation = ''

        alert('Password updated.')

    } catch (err) {
        alert(err.response?.data?.message ?? 'Change Password Failed.')
    } finally {
        savingPassword.value = false
    }
}

async function setupTwoFactor() {
    settingUpTwoFactor.value = true
    try {
        const response = await setupQR()
        qrCode.value = response.qr_code
    } finally {
        settingUpTwoFactor.value = false
    }
}

async function confirmTwoFactor() {
    confirmingTwoFactor.value = true
    try {
        const response = await confirm2FA(verificationCode.value)
        twoFactorEnabled.value = response.data.two_factor_enabled
        qrCode.value = null
    } finally {
        confirmingTwoFactor.value = false
    }
}

async function disableTwoFactor() {
    const response = await disable2FA()
    twoFactorEnabled.value = response.data.two_factor_enabled
    qrCode.value = null
}

onMounted(async () => {
    try {
        await loadProfile()
    } finally {
        loading.value = false
    }
})
</script>
