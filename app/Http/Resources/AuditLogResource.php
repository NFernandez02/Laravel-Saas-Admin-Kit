<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
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
            'user' => $this->whenLoaded('user', function () {
                return [
                    'user_id' => $this->user->id,
                    'user_name' => $this->user->name,
                ];
            }),
            'action' => $this->action,
            'target_type' => $this->target_type,
            'target_id' => $this->target_id,
            'description' => $this->description,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
