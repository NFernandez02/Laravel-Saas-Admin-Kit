<?php

namespace App\Services;

use App\Jobs\CreateAuditLogJob;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordService
{
    /**
     * @param  array{password: string}  $data
     */
    public function update(User $user, array $data): User
    {
        $user->update([
            'password' => Hash::make($data['password']),
        ]);
        $userId = auth()->id();

        CreateAuditLogJob::dispatch(
            $userId === null ? null : (int) $userId,
            'updated',
            'User',
            $user->id,
            'updated password for '.$user->name,
        );

        return $user;
    }
}
