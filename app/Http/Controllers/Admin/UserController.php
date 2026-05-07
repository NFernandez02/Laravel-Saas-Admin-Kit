<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(){
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'));
    }
    public function create(){
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request){
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role']
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'created user ' . $user->name
        ]);
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user){
        $roles = Role::all();
        return view('admin.users.edit', compact(['user', 'roles']));
    }

    public function update(User $user, UpdateUserRequest $request){
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'role_id' => $validated['role']
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'updated user ' . $user->name
        ]);
        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user){
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'deleted user ' . $user->name
        ]);
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}
