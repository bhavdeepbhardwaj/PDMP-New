<!DOCTYPE html>
<html>

<head>

    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-header">

                        <h4>Forgot Password</h4>

                    </div>

                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success">

                                {{ session('success') }}

                            </div>
                        @endif

                        @if ($errors->any())

                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>

                        @endif

                        <form method="POST" action="{{ route('password.email') }}">

                            @csrf

                            <div class="mb-3">

                                <label class="form-label">

                                    Employee Code

                                </label>

                                <input type="text" name="employee_code" value="{{ old('employee_code') }}"
                                    class="form-control" required>

                            </div>

                            <button type="submit" class="btn btn-primary w-100">

                                Send Reset Link

                            </button>

                        </form>

                        <div class="text-center mt-3">

                            <a href="{{ route('login') }}">

                                Back to Login

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
