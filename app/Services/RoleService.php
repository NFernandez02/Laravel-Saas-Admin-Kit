<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Role;

class RoleService
{
    public function create(array $data)
    {
        $role = Role::create([
            'name' => $data['name']
        ]);
        $role->permissions()->sync($data['permissions']);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'Role',
            'target_id' => $role->id,
            'description' => 'created role ' . $role->name
        ]);
        return $role;
    }

    public function update(Role $role, array $data)
    {
        $role->update([
            'name' => $data['name']
        ]);
        $role->permissions()->sync($data['permissions']);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'Role',
            'target_id' => $role->id,
            'description' => 'updated role ' . $role->name
        ]);
        return $role;
    }
    public function delete(Role $role)
    {
        if($role->users()->exists()){
            throw new \Exception('This role is assigned to a user');
        }
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'Role',
            'target_id' => $role->id,
            'description' => 'deleted role ' . $role->name
        ]);
        $role->delete();
    }
}
