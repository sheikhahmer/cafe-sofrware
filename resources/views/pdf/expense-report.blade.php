<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Expense Report</title>
    <style>
        body {
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            background: #fff;
            padding: 40px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .report-header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
            color: #222;
        }

        .report-header p {
            margin-top: 6px;
            color: #555;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 6px rgba(0,0,0,0.08);
        }

        th {
            background: #4a90e2;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }

        td {
            padding: 8px 8px;
            border-bottom: 1px solid #eee;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tfoot td {
            font-weight: bold;
            background: #f0f6ff;
            border-top: 2px solid #4a90e2;
        }

        .no-data {
            text-align: center;
            margin-top: 40px;
            font-size: 15px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="report-header">
    <h1>Daily Expense Report</h1>
    <p>
        Generated on: {{ now()->format('F j, Y g:i A') }}
    </p>
</div>

@if($expenses->count() > 0)
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th>Category</th>
            <th>Amount (₹)</th>
            <th>Day</th>
        </tr>
        </thead>
        <tbody>
        @php $grandTotal = 0; @endphp
        @foreach ($expenses as $key => $expense)
            @php $grandTotal += $expense->amount; @endphp
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $expense->description ?? 'N/A' }}</td>
                <td>{{ $expense->category ?? 'General' }}</td>
                <td>{{ number_format($expense->amount, 2) }}</td>
                <td>{{ $expense->created_at->format('l') }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;">Total:</td>
            <td colspan="2">{{ number_format($grandTotal, 2) }}</td>
        </tr>
        </tfoot>
    </table>
@else
    <p class="no-data">No expenses found for this period.</p>
@endif
</body>
</html>
