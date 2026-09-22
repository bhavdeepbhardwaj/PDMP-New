document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Login Elements
    |--------------------------------------------------------------------------
    */

    const loginForm =
        document.getElementById('loginForm');

    const passwordInput =
        document.getElementById('password');

    const publicKeyInput =
        document.getElementById('rsa_public_key');

    const submitButton =
        document.getElementById('loginSubmit');


    /*
    |--------------------------------------------------------------------------
    | Password Show / Hide
    |--------------------------------------------------------------------------
    */

    const togglePassword =
        document.getElementById('togglePassword');

    const passwordEye =
        document.getElementById('passwordEye');


    if (
        passwordInput &&
        togglePassword &&
        passwordEye
    ) {

        togglePassword.addEventListener(
            'click',
            function () {

                const isPassword =
                    passwordInput.getAttribute('type') === 'password';

                passwordInput.setAttribute(
                    'type',
                    isPassword ? 'text' : 'password'
                );

                passwordEye.textContent =
                    isPassword ? '🙈' : '👁';

                togglePassword.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );

                togglePassword.setAttribute(
                    'title',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CAPTCHA Refresh
    |--------------------------------------------------------------------------
    */

    const captchaImage =
        document.getElementById('captchaImage');

    const refreshCaptcha =
        document.getElementById('refreshCaptcha');


    if (
        captchaImage &&
        refreshCaptcha
    ) {

        refreshCaptcha.addEventListener(
            'click',
            function () {

                captchaImage.src =
                    '/captcha/image?rand='
                    + Math.random();

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RSA-OAEP Login Encryption
    |--------------------------------------------------------------------------
    */

    if (
        !loginForm ||
        !passwordInput ||
        !publicKeyInput
    ) {
        return;
    }


    let encryptionInProgress = false;


    loginForm.addEventListener(
        'submit',
        async function (event) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Normal Submit
            |--------------------------------------------------------------------------
            */

            event.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | Prevent Double Submit
            |--------------------------------------------------------------------------
            */

            if (encryptionInProgress) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Read Plain Password
            |--------------------------------------------------------------------------
            |
            | Plain password exists only in browser memory
            | until RSA encryption is completed.
            |
            */

            const plainPassword =
                passwordInput.value;


            /*
            |--------------------------------------------------------------------------
            | Empty Password
            |--------------------------------------------------------------------------
            */

            if (!plainPassword) {

                HTMLFormElement.prototype.submit.call(
                    loginForm
                );

                return;
            }


            encryptionInProgress = true;


            /*
            |--------------------------------------------------------------------------
            | Disable Login Button
            |--------------------------------------------------------------------------
            */

            if (submitButton) {

                submitButton.disabled = true;

                submitButton.innerText =
                    'Securing...';
            }


            try {

                /*
                |--------------------------------------------------------------------------
                | Web Crypto API Check
                |--------------------------------------------------------------------------
                */

                if (
                    !window.crypto ||
                    !window.crypto.subtle
                ) {

                    throw new Error(
                        'Web Crypto API is not available.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Decode Base64 Encoded PEM
                |--------------------------------------------------------------------------
                */

                const publicKeyPem =
                    atob(
                        publicKeyInput.value
                    );


                /*
                |--------------------------------------------------------------------------
                | Remove PEM Header / Footer
                |--------------------------------------------------------------------------
                */

                const publicKeyBase64 =
                    publicKeyPem
                        .replace(
                            '-----BEGIN PUBLIC KEY-----',
                            ''
                        )
                        .replace(
                            '-----END PUBLIC KEY-----',
                            ''
                        )
                        .replace(
                            /\s/g,
                            ''
                        );


                if (!publicKeyBase64) {

                    throw new Error(
                        'RSA public key is empty.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Base64 → Binary
                |--------------------------------------------------------------------------
                */

                const binaryString =
                    atob(publicKeyBase64);


                const keyBytes =
                    new Uint8Array(
                        binaryString.length
                    );


                for (
                    let i = 0;
                    i < binaryString.length;
                    i++
                ) {

                    keyBytes[i] =
                        binaryString.charCodeAt(i);
                }


                /*
                |--------------------------------------------------------------------------
                | Import RSA Public Key
                |--------------------------------------------------------------------------
                |
                | Current implementation:
                |
                | RSA-OAEP + SHA-1
                |
                | This is being used for compatibility with
                | PHP OpenSSL OPENSSL_PKCS1_OAEP_PADDING.
                |
                */

                const publicKey =
                    await crypto.subtle.importKey(
                        'spki',
                        keyBytes.buffer,
                        {
                            name: 'RSA-OAEP',
                            hash: 'SHA-256'
                        },
                        false,
                        ['encrypt']
                    );


                /*
                |--------------------------------------------------------------------------
                | Convert Password → UTF-8 Bytes
                |--------------------------------------------------------------------------
                */

                const encoder =
                    new TextEncoder();

                const passwordBytes =
                    encoder.encode(
                        plainPassword
                    );


                /*
                |--------------------------------------------------------------------------
                | RSA-OAEP Maximum Plaintext Size
                |--------------------------------------------------------------------------
                |
                | 3072-bit RSA = 384 bytes
                |
                | OAEP-SHA1 maximum:
                |
                | 384 - (2 × 20) - 2 = 342 bytes
                |
                */

                if (
                    passwordBytes.length > 342
                ) {

                    throw new Error(
                        'Password is too long for RSA-OAEP-SHA1 encryption.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | RSA-OAEP Encryption
                |--------------------------------------------------------------------------
                */

                const encryptedPassword =
                    await crypto.subtle.encrypt(
                        {
                            name: 'RSA-OAEP'
                        },
                        publicKey,
                        passwordBytes
                    );


                /*
                |--------------------------------------------------------------------------
                | Ciphertext → Base64
                |--------------------------------------------------------------------------
                */

                const encryptedBytes =
                    new Uint8Array(
                        encryptedPassword
                    );


                let binaryEncrypted = '';


                for (
                    let i = 0;
                    i < encryptedBytes.length;
                    i++
                ) {

                    binaryEncrypted +=
                        String.fromCharCode(
                            encryptedBytes[i]
                        );
                }


                const encryptedBase64 =
                    btoa(binaryEncrypted);


                /*
                |--------------------------------------------------------------------------
                | Replace Plain Password
                |--------------------------------------------------------------------------
                */

                passwordInput.value =
                    encryptedBase64;


                /*
                |--------------------------------------------------------------------------
                | Native Form Submit
                |--------------------------------------------------------------------------
                |
                | Explicit native submission prevents
                | recursion and submit element conflicts.
                |
                */

                HTMLFormElement.prototype.submit.call(
                    loginForm
                );

            } catch (error) {

                /*
                |--------------------------------------------------------------------------
                | RSA Error
                |--------------------------------------------------------------------------
                */

                console.error(
                    'RSA password encryption failed:',
                    error
                );

                console.error(
                    'RSA error name:',
                    error?.name
                );

                console.error(
                    'RSA error message:',
                    error?.message
                );


                /*
                |--------------------------------------------------------------------------
                | Restore Login Button
                |--------------------------------------------------------------------------
                */

                encryptionInProgress = false;


                if (submitButton) {

                    submitButton.disabled = false;

                    submitButton.innerText =
                        'Login';
                }


                /*
                |--------------------------------------------------------------------------
                | User Message
                |--------------------------------------------------------------------------
                */

                alert(
                    'Unable to secure the password. Please try again.'
                );
            }

        }
    );

});
