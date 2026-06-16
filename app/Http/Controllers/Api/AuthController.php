<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
        $user->tokens()->delete();
        $token = $user
            ->createToken('api-token')
            ->plainTextToken;

        return response()->json([
            'token' => $token,
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
}
