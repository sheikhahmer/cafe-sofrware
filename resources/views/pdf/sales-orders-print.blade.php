<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Orders Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; padding: 30px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f3f3f3; }
        tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        @media print {
            body { margin: 0; }
            button { display: none; }
        }
    </style>
</head>
<body>
<h2>Sales Orders Report ({{ now()->toFormattedDateString() }})</h2>
<p style="text-align:center; margin-bottom: 10px;">
    From: <strong>{{ $startOfDay->format('d M Y h:i A') }}</strong> —
    To: <strong>{{ $endOfDay->format('d M Y h:i A') }}</strong>
</p>

<table>
    <thead>
    <tr>
        <th>Order No</th>
        <th>Customer</th>
        <th>Type</th>
        <th>Status</th>
        <th>Total</th>
        <th>Order Date</th>
    </tr>
    </thead>
    <tbody>
    @php $grandTotal = 0; @endphp
    @foreach ($orders as $order)
        @php $grandTotal += $order->grand_total; @endphp
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</td>
            <td>{{ ucfirst($order->status) }}</td>
            <td>{{ number_format($order->grand_total, 2) }}</td>
            <td>{{ $order->created_at->format('d M Y h:i A') }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="4" style="text-align: right;">Grand Total:</td>
        <td colspan="2">{{ number_format($grandTotal, 2) }}</td>
    </tr>
    </tfoot>
</table>

<script>
    // Auto-print and optionally close window after printing
    window.addEventListener('load', () => {
        window.print();
        setTimeout(() => window.close(), 1000);
    });
</script>
</body>
</html>
