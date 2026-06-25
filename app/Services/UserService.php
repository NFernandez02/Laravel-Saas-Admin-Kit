<?php

namespace App\Services;

use App\Jobs\CreateAuditLogJob;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param array{
     *      name: string,
     *      email: string,
     *      password: string,
     *      role_id: int
     * } $data
     */
    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);
        $user->load('role');
        CreateAuditLogJob::dispatch(
            auth()->id(),
            'created',
            'User',
            $user->id,
            'created user '.$user->name,
        );
        return $user;
    }

    /**
     * @param array{
     *      name: string,
     *      role_id: int
     * } $data
     */
    public function update(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'role_id' => $data['role_id'],
        ]);
        $user->refresh();
        $user->load('role');
        CreateAuditLogJob::dispatch(
            auth()->id(),
            'updated',
            'User',
            $user->id,
            'updated user '.$user->name,
        );
        return $user;
    }

    public function delete(User $user): void
    {
        CreateAuditLogJob::dispatch(
            auth()->id(),
            'deleted',
            'User',
            $user->id,
            'deleted user '.$user->name,
        );
        $user->delete();
    }
}
