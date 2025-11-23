<!DOCTYPE html>
<html>
<head>
    <title>Kitchen Print</title>
    <style>
        body { font-family: monospace; font-size: 14px; }
        h2 { text-align: center; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 4px; border-bottom: 1px dashed #000; }
        .footer { margin-top: 10px; text-align: center; }
    </style>
</head>
<style>
    @page {
        margin: 0;
    }
    body {
        margin: 0;
        padding: 10px; /* keep small padding */
    }
    h2 {
        text-align: center;
        margin-bottom: 10px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border-bottom: 1px dashed #000;
        padding: 4px;
    }
    th {
        text-align: left;
    }
    td:nth-child(2), td:nth-child(3), td:nth-child(4),
    th:nth-child(2), th:nth-child(3), th:nth-child(4) {
        text-align: center;
    }
    .totals {
        margin-top: 5px;
    }
    .totals td {
        border: none;
        padding: 2px 4px;
    }
    .totals td:first-child {
        text-align: left;
    }
    .totals td:last-child {
        text-align: center;
    }
    .center {
        text-align: center;
        margin-top: 10px;
    }
    th, td {
        white-space: nowrap;
    }
</style>
<body onload="window.print()">
{{--    <img src="{{ asset('assets/images/logo.png') }}" alt="Masoom's Cafe Logo" style="max-width: 130px;">--}}
    <p style="    text-align: center;
    font-family: 'Brush Script MT';
    font-size: 40px;
    display: list-item;
    margin-bottom: 10px;">
        Masoom’s
    </p>
<h2>KITCHEN ORDER</h2>
<p><strong>Order #:</strong> {{ $order->id }}</p>
<p><strong>Type:</strong> {{ ucfirst($order->order_type) }}</p>
<p><strong>Customer:</strong> {{ $order->customer_name ?? 'Walk-in' }}</p>

<table>
    <thead>
    <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->price * $item->quantity }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    <p>-- Send to Kitchen --</p>
</div>

</body>
</html>
