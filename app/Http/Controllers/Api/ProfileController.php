<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Http\Requests\TwoFactor\ConfirmTwoFactorRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Services\ProfileService;
use App\Services\TwoFactorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service, private TwoFactorService $two_factor_service) {}

    public function show(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $this->authorize('view', $user);

        return response()->json([
            'data' => new ProfileResource($user),
            'qr_code' => $user->two_factor_secret && ! $user->two_factor_enabled ? $this->two_factor_service->generateQrCode($user) : null,
        ]);
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

    public function setup2FA(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $secret = $this->two_factor_service->generateSecret();
        $user->update([
            'two_factor_secret' => encrypt($secret),
        ]);
        $qrCode = $this->two_factor_service->generateQrCode($user);

        return response()->json([
            'qr_code' => $qrCode,
        ]);
    }

    public function confirm2FA(ConfirmTwoFactorRequest $request): ProfileResource
    {
        /** @var User $user */
        $user = auth()->user();

        $encrypted_secret = $user->two_factor_secret;
        if (! is_string($encrypted_secret)) {
            abort(400, '2FA secret missing.');
        }
        $secret = decrypt($encrypted_secret);
        if (! is_string($secret)) {
            abort(400, 'Invalid 2FA secret.');
        }

        $code = $request->string('code')->toString();
        $valid = $this->two_factor_service->verify($secret, $code);

        if (! $valid) {
            abort(422, 'Invalid code.');
        }

        $user->update([
            'two_factor_enabled' => true,
        ]);

        return new ProfileResource($user);
    }

    public function disable2FA(Request $request): ProfileResource
    {
        /** @var User $user */
        $user = auth()->user();
        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
        ]);

        return new ProfileResource($user);
    }
}
