<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passwords\UpdatePasswordRequest;
use App\Http\Resources\PasswordResource;
use App\Services\PasswordService;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function __construct(private PasswordService $service){}

    public function update(UpdatePasswordRequest $request){
        $user = auth()->user();
        if($user->cannot('update', $user)){
            abort(403);
        }
        $this->service->update($user, $request->validated());
        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
