import api from "./authService";

export async function getLogs(page = 1, search = '') {
    const response = await api.get(`/admin/logs?page=${page}&search=${encodeURIComponent(search)}`)

    return response.data
}