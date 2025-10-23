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

<div style="text-align: center; margin-bottom: 10px;">
    <img src="{{ asset('assets/images/logo.png') }}" alt="Masoom's Cafe Logo" style="max-width: 130px;">
</div>

<h3>PAID RECEIPT</h3>
<div class="paid">PAID ✔</div>

<p><strong>Order #:</strong> {{ $order->id }}</p>
<p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
<p><strong>Customer:</strong> {{ $order->customer_name ?? 'Walk-in' }}</p>
<p><strong>Order Type:</strong> {{ ucfirst($order->order_type) }}</p>

<p class="flex items-center gap-2 font-semibold">
    <x-heroicon-o-globe-alt style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />
    www.masoomscafe.com
</p>

<p class="flex items-center gap-2 font-semibold">
    <x-heroicon-o-map-pin style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />
    Masooms Cafe Gulgusht Branch
</p>

<p class="flex items-center gap-2 font-semibold">
    <x-heroicon-o-phone style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />
    03000274744
</p>

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

@php
    $itemsTotal = $order->items->sum('total');
    $discountAmount = 0;

    if ($order->discount_percentage > 0) {
        $discountAmount = round($itemsTotal * $order->discount_percentage / 100, 2);
    } elseif ($order->manual_discount > 0) {
        $discountAmount = $order->manual_discount;
    }

    $grandTotal = $itemsTotal - $discountAmount + $order->service_charges + $order->delivery_charges;
@endphp

<table class="totals" width="100%">
    <tr>
        <td>Subtotal</td>
        <td>{{ number_format($itemsTotal, 2) }}</td>
    </tr>

    @if ($order->discount_percentage > 0)
        <tr>
            <td>Discount ({{ (int) $order->discount_percentage }}%)</td>
            <td>-{{ number_format($discountAmount, 2) }}</td>
        </tr>
    @elseif ($order->manual_discount > 0)
        <tr>
            <td>Discount (Manual)</td>
            <td>-{{ number_format($discountAmount, 2) }}</td>
        </tr>
    @endif

    @if ($order->service_charges > 0)
        <tr>
            <td>Service Charges (7%)</td>
            <td>{{ number_format($order->service_charges, 2) }}</td>
        </tr>
    @endif

    @if ($order->delivery_charges > 0)
        <tr>
            <td>Delivery Charges</td>
            <td>{{ number_format($order->delivery_charges, 2) }}</td>
        </tr>
    @endif

    <tr>
        <td colspan="2" style="border-top: 1px dotted #000;"></td>
    </tr>
    <tr>
        <th>Total Paid</th>
        <th>{{ number_format($grandTotal, 2) }}</th>
    </tr>
</table>

<p class="center gap-3 font-semibold">
    <x-lucide-facebook style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />
    <x-lucide-instagram style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />
    <x-lucide-twitter style="width: 14px; height: 14px;" class="text-primary-500 inline-block align-middle" />
    <span class="ml-2">@ Masoom's Cafe</span>
</p>

<p class="center">THANKS FOR CHOOSING MASOOM'S CAFE!</p>
<p class="center">Software Powered By: 03356360207</p>

</body>
</html>
