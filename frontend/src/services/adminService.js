import api from "./authService";

export async function getDashboard() {
    const response = await api.get('/admin')

    return response.data.data
}