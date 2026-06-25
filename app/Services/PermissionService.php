<?php

namespace App\Services;

use App\Jobs\CreateAuditLogJob;
use App\Models\Permission;

class PermissionService
{
    /**
     * @param  array{name: string}  $data
     */
    public function create(array $data): Permission
    {
        $permission = Permission::create([
            'name' => $data['name'],
        ]);
        $userId = auth()->id();
        CreateAuditLogJob::dispatch(
            $userId === null ? null : (int) $userId,
            'created',
            'Permission',
            $permission->id,
            'created permission '.$permission->name,
        );

        return $permission;
    }

    /**
     * @param  array{name: string}  $data
     */
    public function update(Permission $permission, array $data): Permission
    {
        $permission->update([
            'name' => $data['name'],
        ]);
        $userId = auth()->id();
        CreateAuditLogJob::dispatch(
            $userId === null ? null : (int) $userId,
            'updated',
            'Permission',
            $permission->id,
            'updated permission '.$permission->name,
        );

        return $permission;
    }

    public function delete(Permission $permission): void
    {
        if ($permission->roles()->exists()) {
            throw new \DomainException('This permission is assigned to a role');
        }
        $userId = auth()->id();
        CreateAuditLogJob::dispatch(
            $userId === null ? null : (int) $userId,
            'deleted',
            'Permission',
            $permission->id,
            'deleted permission '.$permission->name,
        );
        $permission->delete();
    }
}
