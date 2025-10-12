<!DOCTYPE html>
<html>
<head>
    <title>Customer Receipt</title>
    <style>
        body { font-family: monospace; font-size: 13px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px dashed #000; padding: 4px; text-align: left; }
        .totals td { border: none; }
        .center { text-align: center; }
    </style>
</head>
<body onload="window.print()">
<h2>Masoom's Cafe</h2>
<p><strong>Order #:</strong> {{ $order->id }}</p>
<p><strong>Date:</strong> {{ $order->created_at }}</p>
<p><strong>Customer:</strong> {{ $order->customer_name ?? 'Walk-in' }}</p>
<p><strong>Order Type:</strong> {{ ucfirst($order->order_type) }}</p>

<table>
    <thead>
    <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
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
    <tr>
        <td>Subtotal</td>
        <td class="right">{{ number_format($order->items->sum('total'), 2) }}</td>
    </tr>
    @if ($order->service_charges > 0)
        <tr>
            <td>Service Charges (7%)</td>
            <td class="right">{{ number_format($order->service_charges, 2) }}</td>
        </tr>
    @endif
    @if ($order->delivery_charges > 0)
        <tr>
            <td>Delivery Charges</td>
            <td class="right">{{ number_format($order->delivery_charges, 2) }}</td>
        </tr>
    @endif
    @if ($order->manual_discount > 0 || $order->discount_percentage > 0)
        <tr>
            <td>Discount</td>
            <td class="right">
                -{{ number_format($order->manual_discount + ($order->items->sum('total') * $order->discount_percentage / 100), 2) }}
            </td>
        </tr>
    @endif
    <tr>
        <th>Grand Total</th>
        <th>{{ number_format($order->grand_total, 2) }}</th>
    </tr>
</table>

<p class="center">Thank you for visiting Sara Cafe!</p>
</body>
</html>
