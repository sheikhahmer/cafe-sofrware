<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Item Sales Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; padding: 30px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0a202; }
        tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
<h2>Item Sales Report ({{ now()->toFormattedDateString() }})</h2>
<table>
    <thead>
    <tr>
        <th>Order ID</th>
        <th>Category</th>
        <th>Product</th>
        <th>Total Quantity Sold</th>
        <th>Total Sales</th>
    </tr>
    </thead>
    <tbody>
    @php $grandTotal = 0; @endphp
    @foreach ($data as $item)
        @php $grandTotal += $item->total_sales; @endphp
        <tr>
            <td>{{ $item->order_id ?? '-' }}</td>
            <td>{{ $item->category->name ?? '-' }}</td>
            <td>{{ $item->product->name ?? '-' }}</td>
            <td>{{ $item->total_quantity }}</td>
            <td>{{ number_format($item->total_sales, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="4" style="text-align: right;">Grand Total:</td>
        <td>Rs {{ number_format($grandTotal, 2) }}</td>
    </tr>
    </tfoot>
</table>

<script>
    // Auto-trigger print on page load
    window.addEventListener('load', () => {
        window.print();
        // Optional: close tab after print (for kiosk-style usage)
        setTimeout(() => window.close(), 1000);
    });
</script>
</body>
</html>
