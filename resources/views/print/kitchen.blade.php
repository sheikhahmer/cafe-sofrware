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
    body {
        font-family: monospace;
        font-size: 13px;
        margin: 0;
        padding: 10px;
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
<body onload="window.print()">
<div style="text-align: center; margin-bottom: 10px;">
    <img src="{{ asset('assets/images/logo.png') }}" alt="Masoom's Cafe Logo" style="max-width: 130px;">
</div>
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

{{--<p class="center gap-3 font-semibold">--}}
{{--    <x-lucide-facebook style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />--}}
{{--    <x-lucide-instagram style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />--}}
{{--    <x-lucide-twitter style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />--}}
{{--    <span class="ml-2">@ Masoom's Cafe</span>--}}
{{--</p>--}}
</body>
</html>
