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
        th { background-color: #f0a202; color: white; }
        tfoot td { font-weight: bold; background: #f0f6ff; }
        @media print { body { margin: 0; padding: 10px; } }
    </style>
</head>
<body>
<p style="    text-align: center;
    font-family: 'Brush Script MT';
    font-size: 40px;
    display: list-item;
    margin-bottom: 10px;">
    Masoom’s
</p>
<h2>Daily Expense Report</h2>
<p style="text-align:center;">
{{--    Generated on: {{ now()->format('F j, Y g:i A') }}--}}
</p>

@if($expenses->count() > 0)
    <table>
        <thead>
        <tr>
            <th>#</th>
{{--            <th>Description</th>--}}
            <th>Product</th>
            <th>Amount (Rs)</th>
        </tr>
        </thead>
        <tbody>
        @php $total = 0; @endphp
        @foreach($expenses as $i => $expense)
            @php $total += $expense->debit; @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
{{--                <td>{{ $expense->account_description ?? 'N/A' }}</td>--}}
                <td>{{ $expense->product ?? '' }}</td>
                <td>{{ number_format($expense->debit) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2" style="text-align:right;">Total:</td>
            <td colspan="2">Rs {{ number_format($total) }}</td>
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
