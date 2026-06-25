<?php

namespace App\Services;

use App\Jobs\CreateAuditLogJob;
use App\Models\AuditLog;
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
        CreateAuditLogJob::dispatch(
            auth()->id(),
            'updated',
            'User',
            $user->id,
            'updated password for '.$user->name,
        );

        return $user;
    }
}
