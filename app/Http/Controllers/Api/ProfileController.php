<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service){}

    public function show(){
        $user = auth()->user();
        if($user->cannot('view', $user)){
            abort(403);
        }
        return new ProfileResource($user);
    }

    public function update(UpdateProfileRequest $request){
        $user = auth()->user();
        if($user->cannot('update', $user)){
            abort(403);
        }
        $user = $this->service->update($user, $request->validated());

        return new ProfileResource($user);
    }
}
