<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\UploadedFile;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function show(): ProfileResource
    {
        $user = auth()->user();
        if ($user->cannot('view', $user)) {
            abort(403);
        }

        return new ProfileResource($user);
    }

    public function update(UpdateProfileRequest $request): ProfileResource
    {

        $user = auth()->user();
        if ($user->cannot('update', $user)) {
            abort(403);
        }
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
