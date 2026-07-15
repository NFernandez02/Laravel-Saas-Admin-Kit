import api from "./authService";

export async function getPermissions(page = 1, search ='') {
    const response = await api.get(`/admin/permissions?page=${page}&search=${encodeURIComponent(search)}`)

    return response.data
}

export async function getAllPermissions() {
    const response = await api.get('/admin/permissions/all')

    return response.data
}

export async function createPermission(permissionData){
    const response = await api.post('/admin/permissions', permissionData)

    return response.data
}

export async function updatePermission(permissionId, permissionData) {
    const response = await api.put(`/admin/permissions/${permissionId}`, permissionData)

    return response.data
}

export async function deletePermission(permissionId) {
    const response = await api.delete(`/admin/permissions/${permissionId}`)
    
    return response.data
}