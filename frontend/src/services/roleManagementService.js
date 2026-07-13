import api from "./authService"

export async function getRoles(page = 1) {
    const response = await api.get(`/admin/roles?page=${page}`)

    return response.data
}