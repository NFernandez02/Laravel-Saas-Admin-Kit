<?php

namespace App\Services;

use App\Jobs\CreateAuditLogJob;
use App\Models\AuditLog;
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
        CreateAuditLogJob::dispatch(
            auth()->id(),
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
        CreateAuditLogJob::dispatch(
            auth()->id(),
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
        CreateAuditLogJob::dispatch(
            auth()->id(),
            'deleted',
            'Permission',
            $permission->id,
            'deleted permission '.$permission->name,
        );
        $permission->delete();
    }
}
