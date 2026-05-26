<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;

class ProfileService
{
    
    public function update(User $user, array $data)
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email']
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'updated profile for ' . $user->name
        ]);
    }
}
