<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passwords\UpdatePasswordRequest;
use App\Services\PasswordService;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
    public function __construct(private PasswordService $service) {}

    public function update(UpdatePasswordRequest $request): JsonResponse
    {
        $user = auth()->user();
        if ($user->cannot('update', $user)) {
            abort(403);
        }
        /** @var array{
         * password: string
         * } $data
         */
        $data = $request->validated();
        $this->service->update($user, $data);

        return response()->json([
            'message' => 'Password changed successfully.',
        ]);
    }
}
