<?php

require_once GOOGLEAUTHENTICATOR_BASIC_PATH . '/vendor/autoload.php';

use RobThree\Auth\TwoFactorAuth;

class Init
{

    private $auth;
    private $secret;

    public function __construct($secret = null)
    {
        $this->auth = new TwoFactorAuth('Forum Pasja Informatyki', 6, 30, 'sha512');
        $this->secret = $secret;
        $this->auth->ensureCorrectTime();
    }

    public function verifyCode($code): bool
    {
        return $this->auth->verifyCode($this->secret, $code, 5);
    }

    public function createSecret(): void
    {
        $this->secret = $this->auth->createSecret(160);
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getCode()
    {
        return $this->auth->getCode($this->secret);
    }

    public function getQRCode(): string
    {
        return $this->auth->getQRCodeImageAsDataUri('Forum Pasja Informatyki', $this->secret);
    }

    public function getRandomRecoveryCode(): string
    {
        return $this->randomString(5) . '-' . $this->randomString(5);
    }

    public function getDebugInfo(): array 
    {
        $result = [
            'style' => 'tall',
            'type' => 'static',
            'note' => 'Secret: ' . $this->secret . '<br>Kod: ' . $this->auth->getCode($this->secret) . '<br>Valid: ' . ((true === $this->auth->verifyCode($this->secret, $this->auth->getCode($this->secret))) ? 'OK' : 'FAIL') . '<br>Obrazek: <img src="' . $this->auth->getQRCodeImageAsDataUri('Pasja Informatyki', $this->secret) . '">'
        ];

        return $result;
    }

    // https://stackoverflow.com/a/31107425
    /**
     * Generate a random string, using a cryptographically secure 
     * pseudorandom number generator (random_int)
     *
     * This function uses type hints now (PHP 7+ only), but it was originally
     * written for PHP 5 as well.
     * 
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     * 
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    public function randomString(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}

//$ok = new Init();

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