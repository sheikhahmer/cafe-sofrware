<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Orders Report</title>
    <style>
        body {
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 12px;
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
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #222;
        }

        .report-header p {
            margin-top: 6px;
            color: #555;
            font-size: 13px;
        }

        .type-section {
            margin-bottom: 50px;
        }

        .type-title {
            background: #4a90e2;
            color: #fff;
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 6px rgba(0,0,0,0.08);
        }

        th {
            background: #f0f6ff;
            color: #333;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
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
            font-size: 15px;
            color: #777;
        }

        @media print {
            body {
                margin: 0;
                padding: 20px;
                font-size: 11px;
            }
            .report-header {
                margin-bottom: 20px;
            }
            table {
                box-shadow: none;
            }
            .type-title {
                page-break-before: auto;
            }
        }
    </style>
</head>
<body>
<div class="report-header">
    <h1>Sales Orders Report</h1>
    <p>
        Generated on: {{ now()->format('F j, Y g:i A') }}
    </p>
</div>

@php
    $groupedOrders = $orders->groupBy('order_type');
    $typeLabels = [
        'dine_in' => 'Dine In',
        'takeaway' => 'Take Away',
        'delivery' => 'Delivery',
    ];
@endphp

@if($orders->count() > 0)
    @foreach ($groupedOrders as $type => $typeOrders)
        <div class="type-section">
            <div class="type-title">{{ $typeLabels[$type] ?? ucfirst(str_replace('_', ' ', $type)) }}</div>
            <table>
                <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total Rs</th>
                    <th>Order Date</th>
                </tr>
                </thead>
                <tbody>
                @php $subtotal = 0; @endphp
                @foreach ($typeOrders as $order)
                    @php $subtotal += $order->grand_total; @endphp
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer_name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ number_format($order->grand_total, 2) }}</td>
                        <td>{{ $order->created_at->format('d M Y h:i A') }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right;">Subtotal ({{ $typeLabels[$type] ?? ucfirst($type) }}):</td>
                    <td colspan="2">{{ number_format($subtotal, 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    @endforeach

    <hr style="margin: 40px 0; border: none; border-top: 2px solid #4a90e2;">

    <h3 style="text-align:right;">Grand Total: Rs{{ number_format($orders->sum('grand_total'), 2) }}</h3>
@else
    <p class="no-data">No sales found for this period.</p>
@endif
</body>
</html>
