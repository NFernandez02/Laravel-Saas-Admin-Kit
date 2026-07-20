import api from "./authService";

export async function updatePassword(password) {
    const response = await api.put('/password', password)

    return response.data
}