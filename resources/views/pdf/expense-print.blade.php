<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Expense Report (Print)</title>
    <style>
        body { font-family: "Segoe UI", Roboto, Arial, sans-serif; font-size: 14px; color: #333; padding: 30px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #4a90e2; color: white; }
        tfoot td { font-weight: bold; background: #f0f6ff; }
        @media print { body { margin: 0; padding: 10px; } }
    </style>
</head>
<body>
<h2>Daily Expense Report</h2>
<p style="text-align:center;">
    Generated on: {{ now()->format('F j, Y g:i A') }}
</p>

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
        @php $total = 0; @endphp
        @foreach($expenses as $i => $expense)
            @php $total += $expense->amount; @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
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
            <td colspan="2">{{ number_format($total, 2) }}</td>
        </tr>
        </tfoot>
    </table>
@else
    <p style="text-align:center;">No expenses found for this period.</p>
@endif

<script>
    window.addEventListener('load', () => {
        window.print();
        setTimeout(() => window.close(), 1000);
    });
</script>
</body>
</html>
