import api from "./authService"

export async function getRoles(page = 1, search = '') {
    const response = await api.get(`/admin/roles?page=${page}&search=${encodeURIComponent(search)}`)

    return response.data
}

export async function createRole(roleData) {
    const response = await api.post('/admin/roles/', roleData)

    return response.data
}

export async function updateRole(roleId, roleData) {
    const response = await api.put(`/admin/roles/${roleId}`, roleData)

    return response.data
}

export async function deleteRole(roleId) {
    const response = await api.delete(`/admin/roles/${roleId}`)
    
    return response.data
}