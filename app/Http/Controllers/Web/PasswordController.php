<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passwords\UpdatePasswordRequest;
use App\Services\PasswordService;

class PasswordController extends Controller
{
    public function __construct(private PasswordService $service){}

    public function update(UpdatePasswordRequest $request){
        $user = auth()->user();
        if($user->cannot('update', $user)){
            abort(403);
        }
        
        $this->service->update($user, $request->validated());
        return redirect()->route('users.profile.index');
    }
}
