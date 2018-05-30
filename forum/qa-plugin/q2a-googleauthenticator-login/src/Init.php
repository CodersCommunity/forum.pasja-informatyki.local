<?php

namespace CodersCommunity;

use RobThree\Auth\TwoFactorAuth;

class Init
{
    /**
     * Init constructor.
     * @throws \RobThree\Auth\TwoFactorAuthException
     */
    public function __construct()
    {
        $two = new TwoFactorAuth('Pasja Informatyki', 6, 30, 'sha512');
        $secret = $two->createSecret(160);

        echo 'Secret: ' . $secret . PHP_EOL;
        echo $two->getQRCodeImageAsDataUri('My label', $secret) . ' || ' . chunk_split($secret, 4, ' ') . PHP_EOL;
        $code = $two->getCode($secret);

        echo 'Code: ' . $code . PHP_EOL;
        echo ((true === $two->verifyCode($secret, $code)) ? 'OK' : 'FAIL');
    }
}

$ok = new Init();

/*
 * TODO:
 * 1. Generowanie secretu dla połączenia z 2FA
 *   a) Strona przeznaczona do wygenerowania secretu powinna zawierać qr code
 *   b) secret powinien zapisywać się w bazie danych do użytkownika
 * 2. Logowanie przy pomocy kodu z google 2FA
 *   a) należy pobrać informację, czy użytkownik wykorzystuje 2fa
 *   b) należy pobrać secret zapisany w bazie secret
 *   c) należy zweryfikować secret z kodem i poprawnie zalogować
 */