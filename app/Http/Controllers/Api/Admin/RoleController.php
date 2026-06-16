<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function __construct(private RoleService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $search = request()->string('search')->value();
        $roles = Role::withCount('users')->
        when(request('search'), function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
            ->paginate(10)
            ->withQueryString();

        return RoleResource::collection($roles);
    }

    public function show(Role $role): RoleResource
    {
        $role->load('permissions');
        $role->loadCount('users');

        return new RoleResource($role);
    }

    public function store(StoreRoleRequest $request): RoleResource
    {
        /** @var array{
         * name: string,
         * permissions: list<int>
         * } $data
         */
        $data = $request->validated();
        $role = $this->service->create($data);

        return new RoleResource($role);
    }

    public function update(Role $role, UpdateRoleRequest $request): RoleResource
    {
        /** @var array{
         * name: string,
         * permissions: list<int>
         * } $data
         */
        $data = $request->validated();
        $role = $this->service->update($role, $data);
        $role->load('permissions');
        $role->loadCount('users');

        return new RoleResource($role);
    }

    public function destroy(Role $role): JsonResponse
    {
        try {
            $this->service->delete($role);

            return response()->json([
                'message' => 'Role deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }
    }
}
