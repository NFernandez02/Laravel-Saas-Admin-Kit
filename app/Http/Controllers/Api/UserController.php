<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $service){}

    public function index(){
        return UserResource::collection(
            User::with('role')->get()
        );
    }
    public function show(User $user){
        $user->load('role');
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request){
        $user = $this->service->create($request->validated());
        return new UserResource($user);
    }

    public function update(User $user, UpdateUserRequest $request){
        $user = $this->service->update($user, $request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user){
        $this->service->delete($user);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
