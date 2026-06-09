<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'users_count' => $this['users_count'],
            'roles_count' => $this['roles_count'],
            'admins_count' => $this['admins_count'],
            'latest_logs' => AuditLogResource::collection($this['latest_logs'])
        ];
    }
}
