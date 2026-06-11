<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordService
{
    public function update(User $user, array $data)
    {
        $user->update([
            'password' => Hash::make($data['password']),
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'updated password for '.$user->name,
        ]);

        return $user;
    }
}
