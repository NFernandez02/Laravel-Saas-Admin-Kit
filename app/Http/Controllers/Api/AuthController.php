<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\TwoFactorAuthRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(private TwoFactorService $two_factor_service) {}

    public function login(LoginRequest $request): JsonResponse
    {
        /** @var array{
         * email: string,
         * password: string
         * } $data
         */
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials',
            ], 401);
        }

        if ($user->two_factor_enabled) {
            $challengeToken = Str::uuid();

            Cache::put(
                "2fa:{$challengeToken}",
                $user->id,
                now()->addMinutes(5)
            );

            return response()->json([
                'requires_2fa' => true,
                'challenge_token' => $challengeToken,
            ]);
        }

        $user->tokens()->delete();
        $token = $user
            ->createToken('api-token')
            ->plainTextToken;
        /** @var Role $role */
        $role = $user->role;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role->name,
            ],
        ]);
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function verify(TwoFactorAuthRequest $request): JsonResponse
    {
        $request->validated();
        $challengeToken = $request->string('challenge_token')->toString();
        $userId = Cache::get("2fa:{$challengeToken}");
        if (! $userId) {

            return response()->json([
                'message' => 'Challenge expired.',
            ], 410);
        }
        /** @var User $user */
        $user = User::findOrFail($userId);
        $code = $request->string('code')->toString();
        $encrypted_secret = $user->two_factor_secret;
        if (! is_string($encrypted_secret)) {
            abort(400, '2FA secret missing.');
        }
        $secret = decrypt($encrypted_secret);
        if (! is_string($secret)) {
            abort(400, 'Invalid 2FA secret.');
        }
        if (! $this->two_factor_service->verify($secret, $code)) {
            return response()->json([
                'message' => 'Invalid code.',
            ], 401);
        }
        $user->tokens()->delete();
        $token = $user
            ->createToken('api-token')
            ->plainTextToken;

        Cache::forget(
            "2fa:{$challengeToken}"
        );

        /** @var Role $role */
        $role = $user->role;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role->name,
            ],
        ]);
    }

    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Role $role */
        $role = $user->role;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $role->name,
        ]);
    }
}
