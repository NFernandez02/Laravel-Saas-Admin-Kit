<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(private RoleService $service){}
    public function index()
    {
        $roles = Role::withCount('users')->
        when(request('search'), function($query, $search){
            $query->where('name', 'like', '%'. $search. '%');
        })
        ->paginate(10)
        ->withQueryString();
        return RoleResource::collection($roles);
    }

    public function show(Role $role){
        $role->load('permissions');
        $role->loadCount('users');
        return new RoleResource($role);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->service->create($request->validated());
        return new RoleResource($role);
    }

    public function update(Role $role, UpdateRoleRequest $request)
    {
        $role = $this->service->update($role, $request->validated());
        $role->load('permissions');
        $role->loadCount('users');
        return new RoleResource($role);
    }
    public function destroy(Role $role)
    {
        try {
            $this->service->delete($role);
            return response()->json([
                'message' => 'Role deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 409);
        }
    }
}
