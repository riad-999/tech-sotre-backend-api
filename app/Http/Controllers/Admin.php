<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class Admin extends Controller
{
    public function order(Request $request)
    {
        $order = new OrderResource(Order::find(2));
        return response($order);
    }
}