<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Point;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::create(['user_id' => $request->user_id, 'total_amount' => 0]);

        $total = 0;
        foreach ($request->products as $prod) {
            $product = Product::find($prod['id']);
            $price = $product->retails_price * $prod['quantity'];
            $total += $price;

            $order->items()->create(['product_id' => $product->id, 'quantity' => $prod['quantity'], 'price' => $price]);

            Point::create(['user_id' => $request->user_id, 'points' => intval($price / 10), 'source' => 'purchase', 'order_id' => $order->id]);
        }

        $order->total_amount = $total >= 500 ? $total : $total + 50;
        $order->save();

        return response()->json(['status' => 201, 'order' => $order]);
    }
}
