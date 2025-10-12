<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Item Sales Report (Category wise)</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 18px; }
        h2 { text-align: center; margin-bottom: 6px; }
        h4 { background: #f2f2f2; padding: 6px; margin-top: 18px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #eee; }
        .right { text-align: right; }
        .subtotal { font-weight: bold; background: #fafafa; }
    </style>
</head>
<body>
<h2>Item Sales Report - {{ ucfirst($filter ?? 'today') }} ({{ $today->format('d M Y') }})</h2>

@php $grandTotal = 0; @endphp

@forelse ($items as $categoryId => $categoryItems)
    @php
        $category = $categories[$categoryId] ?? null;
        $categoryTotal = $categoryItems->sum(fn($i) => $i->total_sales);
        $grandTotal += $categoryTotal;
    @endphp

    <h4>{{ $category?->name ?? 'Uncategorized' }}</h4>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th class="right">Quantity</th>
            <th class="right">Price (avg)</th>
            <th class="right">Total Sales</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($categoryItems as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product?->name ?? 'N/A' }}</td>
                <td class="right">{{ (int) $item->total_quantity }}</td>
                <td class="right">
                    {{-- show average price if you want: total_sales / quantity --}}
                    @if($item->total_quantity > 0)
                        {{ number_format($item->total_sales / $item->total_quantity, 2) }}
                    @else
                        0.00
                    @endif
                </td>
                <td class="right">{{ number_format($item->total_sales, 2) }}</td>
            </tr>
        @endforeach
        <tr class="subtotal">
            <td colspan="4" class="right">Category Total</td>
            <td class="right">{{ number_format($categoryTotal, 2) }}</td>
        </tr>
        </tbody>
    </table>
@empty
    <p>No items found for the selected period.</p>
@endforelse

<hr>
<table style="width: 100%;">
    <tr>
        <td style="text-align: right; font-weight: bold;">Grand Total</td>
        <td style="width:120px; text-align: right; font-weight: bold;">{{ number_format($grandTotal, 2) }}</td>
    </tr>
</table>
</body>
</html>
