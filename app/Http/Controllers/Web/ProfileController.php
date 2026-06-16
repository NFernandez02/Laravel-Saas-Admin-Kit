<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();
        $this->authorize('view', $user);

        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $this->authorize('update', $user);
        /** @var array{
         * name: string,
         * email: string,
         * bio?: string,
         * avatar?: UploadedFile
         * } $data
         */
        $data = $request->validated();
        $this->service->update($user, $data);

        return redirect()->route('users.profile.update');
    }
}
