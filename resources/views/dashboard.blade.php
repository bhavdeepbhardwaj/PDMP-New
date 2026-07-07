<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <div class="container mt-5">

        <h2>

            Welcome {{ auth()->user()->name }}

        </h2>

        <hr>

        <table class="table">

            <tr>

                <th>Employee Code</th>

                <td>{{ auth()->user()->employee_code }}</td>

            </tr>

            <tr>

                <th>Email</th>

                <td>{{ auth()->user()->email }}</td>

            </tr>

        </table>

        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button class="btn btn-danger">

                Logout

            </button>

        </form>

    </div>

</body>

</html>
