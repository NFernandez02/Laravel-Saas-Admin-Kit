<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Models\User;
use App\Services\ProfileService;
use App\Services\TwoFactorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service, private TwoFactorService $twoFactorService) {}

    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();
        $this->authorize('view', $user);

        $qrCode = null;
        if ($user->two_factor_secret && ! $user->two_factor_enabled) {
            $qrCode = $this->twoFactorService->generateQrCode($user);
        }

        return view('profile.edit', compact('user', 'qrCode'));
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

    public function setup(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $secret = $this->twoFactorService->generateSecret();
        $user->update([
            'two_factor_secret' => encrypt($secret),
        ]);

        return redirect()->route('users.profile.index');
    }

    public function confirm(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $encrypted_secret = $user->two_factor_secret;
        if (! is_string($encrypted_secret)) {
            abort(400, '2FA secret missing.');
        }
        $secret = decrypt($encrypted_secret);
        if (! is_string($secret)) {
            abort(400, 'Invalid 2FA secret.');
        }
        $code = $request->string('code')->toString();
        $valid = $this->twoFactorService->verify($secret, $code);

        if (! $valid) {
            return back()
                ->withErrors([
                    'code' => 'Invalid code.',
                ]);
        }

        $user->update([
            'two_factor_enabled' => true,
        ]);

        return redirect()->route('users.profile.index');
    }

    public function delete(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
        ]);

        return redirect()->route('users.profile.index');
    }
}
