<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }
    public function create(){
        return view('admin.roles.create');
    }
    public function store(StoreRoleRequest $request){
        $validated = $request->validated();

        $role = Role::create([
            'name' => $validated['name']
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'Role',
            'target_id' => $role->id,
            'description' => 'created role ' . $role->name
        ]);
        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role){
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Role $role, UpdateRoleRequest $request){
        $validated = $request->validated();

        $role->update([
            'name' => $validated['name']
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'Role',
            'target_id' => $role->id,
            'description' => 'updated role ' . $role->name
        ]);
        return redirect()->route('admin.roles.index');
    }
    public function destroy(Role $role){
        if($role->users()->exists()){
            return back()->withErrors('Role is assigned to a user');
        }
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'Role',
            'target_id' => $role->id,
            'description' => 'deleted role ' . $role->name
        ]);
        $role->delete();
        return redirect()->route('admin.roles.index');
    }
}
