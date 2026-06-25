<?php

namespace App\Services;

use App\Jobs\CreateAuditLogJob;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * @param array{
     * name: string,
     * email: string,
     * bio?: string,
     * avatar?: UploadedFile
     * }$data
     */
    public function update(User $user, array $data): User
    {
        if (isset($data['avatar'])) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $data['avatar']->store('avatars', 'public');
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'bio' => $data['bio'] ?? null,
            'avatar' => $data['avatar'] ?? $user->avatar,
        ]);
        $userId = auth()->id();
        CreateAuditLogJob::dispatch(
            $userId === null ? null : (int) $userId,
            'updated',
            'User',
            $user->id,
            'updated profile for '.$user->name,
        );

        return $user;
    }
}
