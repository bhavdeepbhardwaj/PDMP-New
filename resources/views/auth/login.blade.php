<!DOCTYPE html>
<html>

<head>

    <title>Employee Login</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">

                        <h4>Employee Login</h4>

                    </div>

                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success">

                                {{ session('success') }}

                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">

                                {{ $errors->first() }}

                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.submit') }}">

                            @csrf

                            <div class="mb-3">

                                <label>

                                    Employee Code

                                </label>

                                <input type="text" name="employee_code" value="{{ old('employee_code') }}"
                                    class="form-control" required>

                            </div>

                            <div class="mb-3">

                                <label>Password</label>

                                <input type="password" name="password" class="form-control" required>

                            </div>

                            <div class="form-check mb-3">

                                <input type="checkbox" name="remember" value="1" class="form-check-input">

                                <label class="form-check-label">

                                    Remember Me

                                </label>

                            </div>

                            <button type="submit" class="btn btn-primary w-100">

                                Login

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
