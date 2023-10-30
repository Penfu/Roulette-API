<!DOCTYPE html>
<html>

<head>
    <title>Unlick Provider</title>
</head>

<body>
    <h1>You account has been unlicked of {{ $provider }}</h1>

    <!-- The user can now connect with the generated password and change it in is settings -->
    <p>You can now login to your account with the following credentials:</p>
    <p>Email: {{ $user->email }}</p>
    <p>Password: {{ $password }}</p>

    <p>After login, you can change your password in your settings.</p>

    <p>Best regards</p>
</body>

</html>
