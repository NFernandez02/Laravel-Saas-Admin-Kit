<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function store(Request $request){
        $fillables = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => 'required',
            'role' => 'required',
        ]);

        User::create([
            'name' => $fillables['name'],
            'email' => $fillables['email'],
            'password' => Hash::make($fillables['password']),
            'role_id' => $fillables['role']
        ]);
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user){
        $roles = Role::all();
        return view('admin.users.edit', compact(['user', 'roles']));
    }

    public function update(User $user, Request $request){
        $fillables = $request->validate([
            'name' => 'required',
            'role' => 'required'
        ]);

        $user->update([
            'name' => $fillables['name'],
            'role_id' => $fillables['role']
        ]);

        $user->save();

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user){
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}
