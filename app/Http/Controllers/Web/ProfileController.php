<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function index(): View
    {
        $user = auth()->user();
        if (auth()->user()->cannot('view', $user)) {
            abort(403);
        }

        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        if (auth()->user()->cannot('update', $user)) {
            abort(403);
        }
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
