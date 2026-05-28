<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    
    public function update(User $user, array $data)
    {
        if(isset($data['avatar'])){
            if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $data['avatar']->store('avatars', 'public');
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'bio' => $data['bio'] ?? null,
            'avatar' => $data['avatar'] ?? $user->avatar
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
