import axios from "axios";

const api = axios.create({
    baseURL: 'http://localhost:8000/api/v1',
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
})

export async function login(credentials) {
    const response = await api.post('/login', credentials)
    return response.data
}

export async function verifyTwoFactor(data) {
    const response = await api.post('/2fa/verify', data)
    return response.data    
}

export async function logout(token) {
    const response = await api.post(
        '/logout',
        {},
        {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        }
    )
    return response.data
}

export default api