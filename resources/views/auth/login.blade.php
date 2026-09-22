@extends('layouts.frontend')

@section('css')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('frontend/css/custom.css') }}"
    >
@endsection

@section('content')

    <div class="form-holder">

        <div class="form-content">

            <div class="form-items">

                <h3>Login</h3>

                <p>
                    Login to your employee account
                </p>

                {{-- ===================================================== --}}
                {{-- Success Message --}}
                {{-- ===================================================== --}}

                @if (session('success'))

                    <div
                        class="alert alert-success alert-dismissible fade show with-icon"
                        role="alert"
                    >
                        {{ session('success') }}

                        <button
                            type="button"
                            class="close"
                            data-dismiss="alert"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                @endif


                {{-- ===================================================== --}}
                {{-- Error Message --}}
                {{-- ===================================================== --}}

                @if ($errors->any())

                    <div
                        class="alert alert-danger alert-dismissible fade show with-icon"
                        role="alert"
                    >
                        {{ $errors->first() }}

                        <button
                            type="button"
                            class="close"
                            data-dismiss="alert"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                @endif


                {{-- ===================================================== --}}
                {{-- Login Form --}}
                {{-- ===================================================== --}}

                <form
                    method="POST"
                    action="{{ route('login.submit') }}"
                    autocomplete="off"
                    id="loginForm"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- Employee Code --}}
                    {{-- ================================================= --}}

                    <label
                        for="employee_code"
                        class="d-none"
                    >
                        Employee Code
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        name="employee_code"
                        id="employee_code"
                        value="{{ old('employee_code') }}"
                        placeholder="Employee Code"
                        autocomplete="username"
                        required
                    >


                    {{-- ================================================= --}}
                    {{-- Password --}}
                    {{-- ================================================= --}}

                    <label
                        for="password"
                        class="d-none"
                    >
                        Password
                    </label>

                    <div class="password-wrapper">

                        <input
                            class="form-control"
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="togglePassword"
                            aria-label="Show password"
                            title="Show password"
                        >
                            <span id="passwordEye">👁</span>
                        </button>

                    </div>


                    {{-- ================================================= --}}
                    {{-- RSA Public Key --}}
                    {{-- ================================================= --}}
                    {{--

                        Public key can be exposed to the browser.

                        IMPORTANT:
                        Private key is NEVER sent to the browser.

                    --}}

                    <input
                        type="hidden"
                        id="rsa_public_key"
                        value="{{ base64_encode($rsaPublicKey) }}"
                    >


                    {{-- ================================================= --}}
                    {{-- CAPTCHA --}}
                    {{-- ================================================= --}}

                    <div class="captcha-wrapper">

                        <label
                            for="captcha_code"
                            class="d-none"
                        >
                            CAPTCHA
                        </label>

                        <div class="captcha-row">

                            <img
                                src="{{ route('captcha.image') }}?{{ time() }}"
                                alt="CAPTCHA"
                                id="captchaImage"
                                class="captcha-image"
                            >

                            <button
                                type="button"
                                id="refreshCaptcha"
                                class="captcha-refresh"
                                aria-label="Refresh CAPTCHA"
                                title="Refresh CAPTCHA"
                            >
                                ↻
                            </button>

                        </div>

                        <input
                            type="text"
                            name="captcha_code"
                            id="captcha_code"
                            class="form-control captcha-input"
                            placeholder="Enter CAPTCHA code"
                            maxlength="6"
                            autocomplete="off"
                            required
                        >

                        @error('captcha_code')

                            <div class="captcha-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- Remember Me --}}
                    {{-- ================================================= --}}

                    <div class="form-check mb-3">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="form-check-input"
                            id="remember"
                        >

                        <label
                            class="form-check-label"
                            for="remember"
                        >
                            Remember Me
                        </label>

                    </div>


                    {{-- ================================================= --}}
                    {{-- Login Button --}}
                    {{-- ================================================= --}}

                    <div class="form-button">

                        <button
                            id="loginSubmit"
                            type="submit"
                            class="ibtn"
                        >
                            Login
                        </button>

                        <a href="#"></a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection


@section('js')

 <script src="{{ asset('frontend/js/login.js') }}"></script>

@endsection