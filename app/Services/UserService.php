<?php

namespace App\Services;

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
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'created user '.$user->name,
        ]);

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
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'updated user '.$user->name,
        ]);

        return $user;
    }

    public function delete(User $user): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'deleted user '.$user->name,
        ]);
        $user->delete();
    }
}
