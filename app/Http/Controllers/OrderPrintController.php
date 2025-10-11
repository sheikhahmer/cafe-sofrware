<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderPrintController extends Controller
{
    public function kitchen(Order $order)
    {
        // Fetch only the items that are NOT printed yet
        $items = $order->items()->where('kitchen_printed', false)->get();

        // If there are no new items to print, just redirect back with a message
        if ($items->isEmpty()) {
            return redirect()->back()->with('message', 'No new items to print.');
        }

        // Mark these items as printed
        $order->items()->whereIn('id', $items->pluck('id'))->update(['kitchen_printed' => true]);

        // Return your existing print view — but now it will only show the new items
        return view('print.kitchen', compact('order', 'items'));
    }

    public function receipt(Order $order)
    {
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
