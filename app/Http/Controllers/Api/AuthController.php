<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $user = User::where('email', $request->validated('email'))->first();

        if(!$user || !Hash::check($request->validated('password'), $user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }
        $user->tokens()->delete();
        $token = $user
        ->createToken('api-token')
        ->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }
}
