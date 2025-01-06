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
        <h2>Caritas Tarlac Fund Allocation Report</h2>
        @if ($start_date && $end_date)
            <p>From: {{ \Carbon\Carbon::parse($start_date)->format('M d, Y') }} To:
                {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}</p>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Project Name</th>
                <th>Allocated Amount</th>
                <th>Date Created</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allocations as $allocation)
                <tr>
                    <td>{{ $allocation->category->category_name ?? 'Uncategorized' }}</td>
                    <td>{{ $allocation->project_name }}</td>
                    <td>{{ number_format($allocation->allocated_amount, 2) }}</td>
                    <td>{{ $allocation->created_at->format('M d, Y') }}</td>
                    <td>{{ $allocation->updated_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on: {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>

</html>
