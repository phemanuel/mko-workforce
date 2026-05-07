<!DOCTYPE html>
<html>
<head>
    <title>Verify Account</title>
</head>
<body style="font-family: Arial; background:#f4f4f4; padding:20px;">

    <div style="max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px;">

        <h2 style="color:#000;">Welcome to MKO Workforce</h2>

        <p>Hello {{ $user->name }},</p>

        <p>Thank you for registering. Please verify your email to continue your application.</p>

        <a href="{{ route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]) }}"
           style="display:inline-block; padding:12px 20px; background:#dc2626; color:#fff; text-decoration:none; border-radius:5px;">
            Verify Email
        </a>

        <p style="margin-top:20px; font-size:12px; color:#666;">
            If you did not create this account, ignore this email.
        </p>

    </div>

</body>
</html>