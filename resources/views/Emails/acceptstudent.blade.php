<!DOCTYPE html>
<html>
<head>
    <title>Application Accepted</title>
</head>
<body>
    <h1>Dear {{ $student->name }},</h1>
    <p>We are pleased to inform you that your application has been accepted.</p>
    <p>Click the link below to set your password:</p>
    <a href="{{ url('password/reset', $token) }}">Set Password</a>
</body>
</html>
