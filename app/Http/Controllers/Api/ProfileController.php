<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\UploadedFile;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function show(): ProfileResource
    {
        /** @var User $user */
        $user = auth()->user();
        $this->authorize('view', $user);

        return new ProfileResource($user);
    }

    public function update(UpdateProfileRequest $request): ProfileResource
    {
        /** @var User $user */
        $user = auth()->user();
        $this->authorize('update', $user);
        /** @var array{
         * name: string,
         * email: string,
         * bio?: string,
         * avatar?: UploadedFile
         * }
         */
        $data = $request->validated();
        $user = $this->service->update($user, $data);

        return new ProfileResource($user);
    }
}
