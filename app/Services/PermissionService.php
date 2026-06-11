<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Permission;

class PermissionService
{
    public function create(array $data)
    {
        $permission = Permission::create([
            'name' => $data['name'],
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'Permission',
            'target_id' => $permission->id,
            'description' => 'created permission '.$permission->name,
        ]);

        return $permission;
    }

    public function update(Permission $permission, array $data)
    {
        $permission->update([
            'name' => $data['name'],
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'edited',
            'target_type' => 'Permission',
            'target_id' => $permission->id,
            'description' => 'edited permission '.$permission->name,
        ]);

        return $permission;
    }

    public function delete(Permission $permission)
    {
        if ($permission->roles()->exists()) {
            throw new \Exception('This permission is assigned to a role');
        }
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'Permission',
            'target_id' => $permission->id,
            'description' => 'deleted permission '.$permission->name,
        ]);
        $permission->delete();
    }
}
