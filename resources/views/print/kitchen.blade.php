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
<body onload="window.print()">
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
            <td>{{ $item->price }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    <p>-- Send to Kitchen --</p>
</div>
</body>
</html>
