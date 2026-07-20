<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorService
{
    public function generateSecret(): string
    {
        return app(Google2FA::class)
            ->generateSecretKey();
    }

    public function verify(string $secret, string $code): bool|int
    {
        return app(Google2FA::class)
            ->verifyKey($secret, $code);
    }

    public function generateQrCode(User $user): string
    {
        if (! $user->two_factor_secret) {
            abort(400, '2FA secret missing.');
        }

        $encrypted_secret = $user->two_factor_secret;
        $secret = decrypt($encrypted_secret);
        if (! is_string($secret)) {
            abort(400, 'Invalid 2FA secret.');
        }

        $google2fa = app(Google2FA::class);
        $appName = config('app.name');
        if (! is_string($appName)) {
            abort(500, 'App name missing.');
        }
        $qrCode = QrCode::size(250)
            ->generate(
                $google2fa->getQRCodeUrl(
                    $appName,
                    $user->email,
                    $secret
                )
            );
        if ($qrCode === null) {
            abort(500, 'Failed to generate QR code.');
        }

        /** @phpstan-ignore-next-line */
        return (string) $qrCode;
    }
}
