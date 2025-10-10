<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderPrintController extends Controller
{
    public function kitchen(Order $order)
    {
        return view('print.kitchen', compact('order'));
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
