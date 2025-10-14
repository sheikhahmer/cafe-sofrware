<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Item Sales Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f3f3f3; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
<h2>Item Sales Report - {{ now()->toFormattedDateString() }}</h2>

@if($data->count() > 0)
    <table>
        <thead>
        <tr>
            <th>Category</th>
            <th>Product</th>
            <th>Total Quantity</th>
            <th>Total Sales</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $record)
            <tr>
                <td>{{ $record->category->name ?? 'N/A' }}</td>
                <td>{{ $record->product->name ?? 'N/A' }}</td>
                <td>{{ $record->total_quantity }}</td>
                <td>{{ number_format($record->total_sales, 2) }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2"><strong>Grand Total</strong></td>
            <td><strong>{{ $data->sum('total_quantity') }}</strong></td>
            <td><strong>{{ number_format($data->sum('total_sales'), 2) }}</strong></td>
        </tr>
        </tbody>
    </table>
@else
    <p>No data found for the selected filters.</p>
@endif

<!-- Display active filters -->
<div style="margin-top: 20px; font-size: 10px; color: #666;">
    <strong>Filters Applied:</strong>
    @if(isset($filters['today']) && $filters['today'])
        Today |
    @endif
    @if(isset($filters['this_week']) && $filters['this_week'])
        This Week |
    @endif
    @if(isset($filters['custom_date']))
        Custom Date:
        {{ $filters['custom_date']['start_date'] ?? '' }}
        to
        {{ $filters['custom_date']['end_date'] ?? '' }}
    @endif
    @if(empty($filters))
        Today (Default)
    @endif
</div>

</body>
</html>
