<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(private UserService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        $search = request()->string('search')->value();
        $users = User::with('role')->when(request('search'), function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
            ->paginate(10)
            ->withQueryString();

        return UserResource::collection($users);
    }

    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);
        $user->load('role');

        return new UserResource($user);
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $this->authorize('create', User::class);
        /** @var array{
         * name: string,
         * email: string,
         * password: string,
         * role_id: int
         * } $data
         */
        $data = $request->validated();
        $user = $this->service->create($data);

        return new UserResource($user);
    }

    public function update(User $user, UpdateUserRequest $request): UserResource
    {
        $this->authorize('update', $user);
        /** @var array{
         * name: string,
         * role_id: int
         * } $data
         */
        $data = $request->validated();
        $user = $this->service->update($user, $data);

        return new UserResource($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $this->service->delete($user);

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
