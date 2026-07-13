import api from "./authService";

export async function getUsers(page = 1) {
    const response = await api.get(`/admin/users?page=${page}`)

    return response.data
}

export async function createUser(userData) {
    const response = await api.post('/admin/users', userData)
    return response.data
}

export async function updateUser(userId, updatedData) {
    const response = await api.put(`/admin/users/${userId}`, updatedData)
    return response.data
}

export async function deleteUser(userId) {
    const response = await api.delete(`/admin/users/${userId}`)
    return response.data
}