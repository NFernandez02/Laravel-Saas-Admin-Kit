import api from "./authService";

export async function me() {
    const response = await api.get('/me')

    return response.data
}