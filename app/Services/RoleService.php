<?php

namespace App\Services;

use App\Jobs\CreateAuditLogJob;
use App\Models\Role;

class RoleService
{
    /**
     * @param array{
     * name: string,
     * permissions: list<int>
     * } $data
     */
    public function create(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
        ]);
        $role->permissions()->sync($data['permissions']);
        $userId = auth()->id();
        CreateAuditLogJob::dispatch(
            $userId === null ? null : (int) $userId,
            'created',
            'Role',
            $role->id,
            'created role '.$role->name,
        );

        return $role;
    }

    /**
     * @param array{
     * name: string,
     * permissions: list<int>
     * } $data
     */
    public function update(Role $role, array $data): Role
    {
        $role->update([
            'name' => $data['name'],
        ]);
        $role->permissions()->sync($data['permissions']);
        $userId = auth()->id();
        CreateAuditLogJob::dispatch(
            $userId === null ? null : (int) $userId,
            'updated',
            'Role',
            $role->id,
            'updated role '.$role->name,
        );

        return $role;
    }

    public function delete(Role $role): void
    {
        if ($role->users()->exists()) {
            throw new \DomainException('This role is assigned to a user');
        }
        $userId = auth()->id();
        CreateAuditLogJob::dispatch(
            $userId === null ? null : (int) $userId,
            'deleted',
            'Role',
            $role->id,
            'deleted role '.$role->name,
        );
        $role->delete();
    }
}
