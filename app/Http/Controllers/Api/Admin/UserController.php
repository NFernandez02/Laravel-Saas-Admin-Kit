<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(private UserService $service) {}

    public function index()
    {
        if (auth()->user()->cannot('viewAny', User::class)) {
            abort(403);
        }
        $users = User::with('role')->
        when(request('search'), function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })
            ->paginate(10)
            ->withQueryString();

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        if (auth()->user()->cannot('view', $user)) {
            abort(403);
        }
        $user->load('role');
        
        $user->nan_property;
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request)
    {
        if (auth()->user()->cannot('create', User::class)) {
            abort(403);
        }
        $user = $this->service->create($request->validated());

        return new UserResource($user);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        if (auth()->user()->cannot('update', $user)) {
            abort(403);
        }

        $user = $this->service->update($user, $request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        if (auth()->user()->cannot('delete', $user)) {
            abort(403);
        }
        $this->service->delete($user);

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
