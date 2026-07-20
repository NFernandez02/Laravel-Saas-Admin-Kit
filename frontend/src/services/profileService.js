import api from "./authService";

export async function getProfile() {
    const response = await api.get('/profile')

    return response.data
}

export async function updateProfile(profileData){
    const formData = new FormData()

    formData.append('name', profileData.name)
    formData.append('email', profileData.email)

    if(profileData.bio){
        formData.append('bio', profileData.bio)
    }
    if(profileData.avatar){
        formData.append('avatar', profileData.avatar)
    }

    const response = await api.post('/profile', formData,
        {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            params: {
                _method: 'PUT'
            }
        }
    )

    return response.data
}

export async function setupQR() {
    const response = await api.post('/profile/two-factor/setup')

    return response.data
}

export async function confirm2FA(code){
    const response = await api.post('/profile/two-factor/confirm', {code})

    return response.data
}

export async function disable2FA(){
    const response = await api.post('/profile/two-factor/disable');

    return response.data
}