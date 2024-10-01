<!DOCTYPE html>
<html>
<head>
    <title>Set Your Password</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}</h1>
    <p>You have been registered as a {{ $user->role }}. Please set your password using the link below:</p>
    <a href="{{ url('password/reset', $token) }}">Set Password</a>
</body>
</html>
