<!DOCTYPE html>
<html>
<head>
    <title>Donation Receipt</title>
    <style>
        body {
            background-color: #800000; /* Background color for the email */
            color: #ffffff; /* Text color */
            font-family: Arial, sans-serif;
        }
        .container {
            width: 90%;
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff; /* White background for the content */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #ffffff; /* White background for the header */
            padding: 10px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            position: relative;
        }
        .header img {
            width: 24px;
            height: 24px;
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
        }
        h1 {
            color: #800000; /* Color for the header text */
        }
        p {
            margin: 10px 0;
            text-align: center;
        }
        strong {
            color: #800000; /* Color for strong text */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://img.icons8.com/material-outlined/24/checked.png" alt="Check Mark"/>
            <h1>Donation Receipt</h1>
        </div>
        <p>Thank you for your donation!</p>
        <p><strong>Amount:</strong> {{ $amount }}</p>
        <p><strong>Date Created:</strong> {{ $date_created }}</p>
        <p><strong>Reference Number:</strong> {{ $reference_no }}</p>
        <p><strong>Payment Option:</strong> {{ $payment_option }}</p>
    </div>
</body>
</html>
