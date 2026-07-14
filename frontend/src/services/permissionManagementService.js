import api from "./authService";

export async function getPermissions() {
    const response = await api.get('/admin/permissions')

    return response.data
}