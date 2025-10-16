<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Sales Report</title>
    <style>
        body {
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 14px;
            color: #333;
            background: #fff;
            padding: 40px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .report-header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #222;
        }

        .report-header p {
            margin-top: 6px;
            color: #555;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 6px rgba(0,0,0,0.08);
        }

        th {
            background: #4a90e2;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 8px 8px;
            border-bottom: 1px solid #eee;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tfoot td {
            font-weight: bold;
            background: #f0f6ff;
            border-top: 2px solid #4a90e2;
        }

        .no-data {
            text-align: center;
            margin-top: 40px;
            font-size: 16px;
            color: #777;
        }

        @media print {
            body {
                margin: 0;
                padding: 20px;
                font-size: 12px;
            }
            .report-header {
                margin-bottom: 20px;
            }
            table {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
<div class="report-header">
    <h1>Item Sales Report</h1>
    <p>Generated on: {{ now()->format('F j, Y g:i A') }}</p>
</div>

@if($data->count() > 0)
    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Category</th>
            <th>Product</th>
            <th>Total Quantity</th>
            <th>Total Sales (₹)</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $record)
            <tr>
                <td>{{ $record->order_id }}</td>
                <td>{{ $record->category->name ?? 'N/A' }}</td>
                <td>{{ $record->product->name ?? 'N/A' }}</td>
                <td>{{ $record->total_quantity }}</td>
                <td>{{ number_format($record->total_sales, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;">Grand Total:</td>
            <td>{{ $data->sum('total_quantity') }}</td>
            <td>{{ number_format($data->sum('total_sales'), 2) }}</td>
        </tr>
        </tfoot>
    </table>
@else
    <p class="no-data">No data found for the selected filters.</p>
@endif

<script>
    window.addEventListener('load', () => {
        window.print();
        setTimeout(() => window.close(), 1000);
    });
</script>
</body>
</html>

