<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        .email-header {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
        }
        .email-body {
            font-size: 16px;
            color: #555555;
        }
        .email-footer {
            font-size: 14px;
            color: #777777;
            margin-top: 20px;
        }
        .verify-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff; /* Primary blue color */
            color: #ffffff; /* White text color */
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            Verify Your Email Address
        </div>
        <div class="email-body">
            <p>Dear {{ $user->first_name }},</p>
            <p>Please click the button below to verify your email address:</p>
            <a href="{{ $verificationUrl }}" class="verify-button">Verify Email</a>
            <p>If you did not create an account, no further action is required.</p>
        </div>
        <div class="email-footer">
            <p>Thank you for using our application!</p>
        </div>
    </div>
</body>
</html>
