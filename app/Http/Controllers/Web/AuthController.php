<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class AuthController extends Controller
{
    public function login()
    {
        return view('authentication.login');
    }

    public function loginUser(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (FacadesAuth::attempt([
            'email' => $fields['email'],
            'password' => $fields['password'],
        ])) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        FacadesAuth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
