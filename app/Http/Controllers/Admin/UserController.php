<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Role;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(private UserService $service){}


    public function index(){
        if(auth()->user()->cannot('viewAny', User::class)){
            abort(403);
        }
        $users = User::with('role')->
        when(request('search'), function ($query, $search) {
            $query->where(function($q) use ($search){
                $q->where('name', 'like', '%'. $search . '%')
                  ->orWhere('email', 'like', '%' . $search. '%');
            });
        })
        ->paginate(10)
        ->withQueryString();
        return view('admin.users.index', compact('users'));
    }
    public function create(){
        if(auth()->user()->cannot('create', User::class)){
            abort(403);
        }
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request){
        if(auth()->user()->cannot('create', User::class)){
            abort(403);
        }

        $this->service->create($request->validated());
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user){
        if(auth()->user()->cannot('update', $user)){
            abort(403);
        }
        $roles = Role::all();
        return view('admin.users.edit', compact(['user', 'roles']));
    }

    public function update(User $user, UpdateUserRequest $request){
        if(auth()->user()->cannot('update', $user)){
            abort(403);
        }
        $this->service->update($user, $request->validated());
        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user){
        if(auth()->user()->cannot('delete', $user)){
            abort(403);
        }
        $this->service->delete($user);
        return redirect()->route('admin.users.index');
    }
}
