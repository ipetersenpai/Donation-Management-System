<!-- resources/views/auth/passwords/email.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>

    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Email address:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        <button type="submit">Send Password Reset Link</button>
    </form>
</body>
</html>
