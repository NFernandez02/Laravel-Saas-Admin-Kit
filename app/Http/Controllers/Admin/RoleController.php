<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function store(Request $request){
        $fillables = $request->validate([
            'name' => ['required',  'string', 'max:255', Rule::unique('roles', 'name')]
        ]);

        Role::create([
            'name' => $fillables['name']
        ]);

        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role){
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Role $role, Request $request){
        $fillables = $request->validate([
            'name' => ['required', 'max:255', 'string', Rule::unique('roles', 'name')->ignore($role->id)]
        ]);

        $role->update([
            'name' => $fillables['name']
        ]);

        return redirect()->route('admin.roles.index');
    }
    public function destroy(Role $role){
        if($role->users()->existst()){
            return back()->withErrors('Role is assigned to a user');
        }
        $role->delete();
        return redirect()->route('admin.roles.index');
    }
}
