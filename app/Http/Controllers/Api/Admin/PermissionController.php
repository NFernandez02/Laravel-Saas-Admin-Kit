<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\StorePermissionRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
    public function __construct(private PermissionService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $permissions = Permission::withCount('roles')->
        when(request('search'), function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })
            ->paginate(10)
            ->withQueryString();

        return PermissionResource::collection($permissions);
    }

    public function show(Permission $permission): PermissionResource
    {
        $permission->load('roles');
        $permission->loadCount('roles');

        return new PermissionResource($permission);
    }

    public function store(StorePermissionRequest $request): PermissionResource
    {
        /** @var array{name: string} $data */
        $data = $request->validated();
        $permission = $this->service->create($data);
        $permission->load('roles');
        $permission->loadCount('roles');

        return new PermissionResource($permission);
    }

    public function update(Permission $permission, UpdatePermissionRequest $request): PermissionResource
    {
        /** @var array{name: string} $data */
        $data = $request->validated();
        $permission = $this->service->update($permission, $data);
        $permission->load('roles');
        $permission->loadCount('roles');

        return new PermissionResource($permission);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        try {
            $this->service->delete($permission);

            return response()->json([
                'message' => 'Permission deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }
    }
}
