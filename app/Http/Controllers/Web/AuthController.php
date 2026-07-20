<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\TwoFactor\ConfirmTwoFactorRequest;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private TwoFactorService $two_factor_service) {}

    public function login(): View
    {
        return view('authentication.login');
    }

    public function loginUser(LoginRequest $request): RedirectResponse
    {
        /** @var array{
         * email: string,
         * password: string
         * } $fields
         */
        $fields = $request->validated();
        $user = User::where('email', $fields['email'])->first();

        if ($user && Hash::check($fields['password'], $user->password) && $user->two_factor_enabled) {
            session(['2fa_user_id' => $user->id]);

            return redirect()->route('challenge');
        }

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

    public function logout(Request $request): RedirectResponse
    {
        FacadesAuth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('2fa_user_id');

        return redirect('/');
    }

    public function challenge(): View|RedirectResponse
    {
        if (! session('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('authentication.challenge');
    }

    public function verify(ConfirmTwoFactorRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = User::findOrFail(session('2fa_user_id'));
        $code = $request->string('code')->toString();

        $encrypted_secret = $user->two_factor_secret;
        if (! is_string($encrypted_secret)) {
            abort(400, '2FA secret missing.');
        }
        $secret = decrypt($encrypted_secret);
        if (! is_string($secret)) {
            abort(400, 'Invalid 2FA secret.');
        }

        if ($this->two_factor_service->verify($secret, $code)) {
            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->forget('2fa_user_id');

            return redirect('/');
        }

        return back()->withErrors([
            'code' => 'Invalid Code.',
        ]);
    }
}
