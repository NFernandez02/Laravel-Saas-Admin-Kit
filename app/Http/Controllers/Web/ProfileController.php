<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service) {}

    public function index()
    {
        $user = auth()->user();
        if (auth()->user()->cannot('view', $user)) {
            abort(403);
        }

        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        if (auth()->user()->cannot('update', $user)) {
            abort(403);
        }
        $this->service->update($user, $request->validated());

        return redirect()->route('users.profile.update');
    }
}
