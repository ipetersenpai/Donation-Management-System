<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Caritas Tarlac Donation History Report</h2>
        @if ($start_date && $end_date)
            <!-- Format the dates using Carbon instance -->
            <p>From: {{ \Carbon\Carbon::parse($start_date)->format('M d, Y') }} To:
                {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}</p>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>Donator Name</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Reference No</th>
                <th>Payment Option</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($donations as $donation)
                <tr>
                    <td>{{ $donation->donator_name }}</td> <!-- Donator Name -->
                    <td>{{ $donation->category->category_name }}</td>
                    <td>{{ $donation->amount }}</td>
                    <td>{{ $donation->reference_no }}</td>
                    <td>{{ $donation->payment_option }}</td>
                    <td>{{ $donation->created_at }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <!-- Optional footer for page numbering or additional info -->
    <div class="footer">
        <p>Generated on: {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>

</html>
