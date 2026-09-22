<?php

namespace App\Services;

use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use RuntimeException;

class RsaLoginService
{
    /**
     * Decrypt login password using RSA-OAEP SHA-256.
     */
    public function decrypt(string $encryptedPassword): string
    {
        /*
        |--------------------------------------------------------------------------
        | RSA Private Key Path
        |--------------------------------------------------------------------------
        */

        $privateKeyPath = config('rsa.login.private_key');

        /*
        |--------------------------------------------------------------------------
        | Validate Private Key File
        |--------------------------------------------------------------------------
        */

        if (
            empty($privateKeyPath) ||
            ! is_file($privateKeyPath) ||
            ! is_readable($privateKeyPath)
        ) {
            throw new RuntimeException(
                'Login RSA private key is unavailable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Read Private Key
        |--------------------------------------------------------------------------
        */

        $privateKeyContent = file_get_contents($privateKeyPath);

        if ($privateKeyContent === false) {
            throw new RuntimeException(
                'Login RSA private key could not be read.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Load RSA Private Key
        |--------------------------------------------------------------------------
        |
        | Do not preserve the underlying exception because cryptographic
        | exceptions should not be exposed through application logging.
        |
        */

        try {

            $privateKey = PublicKeyLoader::load(
                $privateKeyContent
            );

        } catch (\Throwable) {

            throw new RuntimeException(
                'Invalid login RSA private key.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ensure RSA Private Key
        |--------------------------------------------------------------------------
        */

        if (
            ! $privateKey instanceof
            \phpseclib3\Crypt\RSA\PrivateKey
        ) {
            throw new RuntimeException(
                'Login RSA private key is not an RSA private key.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate RSA Key Size
        |--------------------------------------------------------------------------
        */

        if ($privateKey->getLength() < 3072) {

            throw new RuntimeException(
                'Login RSA private key must be at least 3072 bits.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Configure RSA-OAEP SHA-256
        |--------------------------------------------------------------------------
        */

        $privateKey = $privateKey
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256')
            ->withMGFHash('sha256');

        /*
        |--------------------------------------------------------------------------
        | Strict Base64 Decode
        |--------------------------------------------------------------------------
        */

        $ciphertext = base64_decode(
            $encryptedPassword,
            true
        );

        if ($ciphertext === false) {

            throw new RuntimeException(
                'Invalid encrypted password.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate RSA Ciphertext Length
        |--------------------------------------------------------------------------
        |
        | RSA ciphertext size is equal to RSA modulus size in bytes.
        |
        */

        $expectedCiphertextLength = intdiv(
            $privateKey->getLength(),
            8
        );

        if (
            strlen($ciphertext) !==
            $expectedCiphertextLength
        ) {
            throw new RuntimeException(
                'Invalid encrypted password length.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RSA-OAEP SHA-256 Decryption
        |--------------------------------------------------------------------------
        */

        try {

            $plaintext = $privateKey->decrypt(
                $ciphertext
            );

        } catch (\Throwable) {

            /*
            |--------------------------------------------------------------------------
            | Security
            |--------------------------------------------------------------------------
            | Do not retain the underlying exception.
            |
            | The original cryptographic exception trace may contain
            | sensitive ciphertext passed to decrypt().
            |
            */

            throw new RuntimeException(
                'Unable to decrypt login password.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Decrypted Password
        |--------------------------------------------------------------------------
        */

        if (
            ! is_string($plaintext) ||
            $plaintext === ''
        ) {
            throw new RuntimeException(
                'Unable to decrypt login password.'
            );
        }

        return $plaintext;
    }
}