<template>
    <aside class="min-h-screen w-64 border-r bg-white">
        <nav class="flex flex-col gap-2 p-4">

            <RouterLink to="/admin">
                Dashboard
            </RouterLink>

            <RouterLink to="/admin/users">
                Users
            </RouterLink>

            <RouterLink to="/admin/roles">
                Roles
            </RouterLink>

            <RouterLink to="/admin/permissions">
                Permissions
            </RouterLink>

            <RouterLink to="/admin/audit-logs">
                Audit Logs
            </RouterLink>
        </nav>
    </aside>
</template>