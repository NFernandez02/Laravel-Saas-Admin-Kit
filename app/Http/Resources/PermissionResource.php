<?php

namespace App\Http\Resources;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Permission
 */
class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'roles_count' => $this->whenCounted('roles'),
            'roles' => $this->whenLoaded(
                'roles',
                fn () => $this->roles->map(fn ($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                ])
            ),
        ];
    }
}
