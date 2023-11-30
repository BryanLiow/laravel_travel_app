<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }

        .container {
            background-color: #fff;
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .header {
            color: #fff;
            background-color: #007bff;
            padding: 20px;
            border-radius: 4px 4px 0 0;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .content {
            padding: 20px;
            color: #495057;
        }

        .content p {
            font-size: 14px;
            line-height: 1.6;
        }

        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-top: 25px;
            font-size: 18px;
        }

        .info {
            margin-top: 30px;
            font-size: 12px;
            color: #6c757d;
        }

        .info p {
            margin: 5px 0;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #adb5bd;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Forgot your password?</h1>
        </div>
        <div class="content">
            <p>Looks like you'd like to reset your password for the account with the email <strong>{{ $email }}</strong>. Don't worry, you can make a new one by clicking the button below.</p>
            <a href="http://localhost:3000/reset/{{ $token }}" style="background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; margin-top: 25px; font-size: 18px;" class="button">Reset Password</a>
            <p class="info">If you didn't ask to reset your password, just ignore this email. Your account is safe with us.</p>
            <p class="info">If the button doesn't work, just paste the link below into your browser window:</p>
            <p class="info"><a href="http://localhost:3000/reset/{{ $token }}">http://localhost:3000/reset/{{ $token }}</a></p>
        </div>
    </div>
</body>

</html>