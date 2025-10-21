
<!DOCTYPE html>
<html>
<head>
    <title>Paid Receipt</title>
    <style>
        body {
            font-family: monospace;
            font-size: 13px;
            margin: 0;
            padding: 10px;
        }
        h2, h3 {
            text-align: center;
            margin: 0;
        }
        .paid {
            text-align: center;
            color: green;
            font-weight: bold;
            margin: 10px 0;
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
            text-align: right;
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
            text-align: right;
        }
        .center {
            text-align: center;
            margin-top: 10px;
        }
        th, td {
            white-space: nowrap;
        }
    </style>

</head>
<body onload="window.print()">
<h2>Masoom's Cafe</h2>
<h3>PAID RECEIPT</h3>
<div class="paid">PAID ✔</div>

<p><strong>Order #:</strong> {{ $order->id }}</p>
<p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
<p><strong>Customer:</strong> {{ $order->customer_name ?? 'Walk-in' }}</p>

<table>
    <thead>
    <tr>
        <th>Item</th><th>Qty</th><th>Price</th><th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($order->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price, 2) }}</td>
            <td>{{ number_format($item->total, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="totals" width="100%">
    <tr><td>Service Charges</td><td>{{ number_format($order->service_charges, 2) }}</td></tr>
    @if ($order->delivery_charges > 0)
        <tr><td>Delivery</td><td>{{ number_format($order->delivery_charges, 2) }}</td></tr>
    @endif
    <tr><td><strong>Total Paid</strong></td><td><strong>{{ number_format($order->grand_total, 2) }}</strong></td></tr>
</table>

<p style="text-align:center;">Thank you! Visit Again!</p>
</body>
</html>
