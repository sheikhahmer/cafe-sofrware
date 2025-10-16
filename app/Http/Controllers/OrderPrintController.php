<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderPrintController extends Controller
{
    public function kitchen(Order $order)
    {
        // Get comma-separated item IDs from query string
        $itemIds = request('item_ids');

        if ($itemIds) {
            $itemIdsArray = explode(',', $itemIds);
            $items = $order->items()->whereIn('id', $itemIdsArray)->get();
        } else {
            // Fallback for direct access
            $items = $order->items()->where('kitchen_printed', false)->get();
        }

        return view('print.kitchen', compact('order', 'items'));
    }

    public function receipt(Order $order)
    {
        $order->items()->update(['receipt_printed' => true]);
        return view('print.receipt', compact('order'));
    }
    public function paid(Order $order)
    {
        if ($order->status !== 'paid') {
            $order->update(['status' => 'paid']);
        }
        return view('print.paid', compact('order'));
    }
}
