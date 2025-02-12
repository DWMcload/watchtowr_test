<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->with('products')->get();

        return response()->json(
            $orders
        );
    }

    public function checkout(CheckoutRequest $request)
    {
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->save();
        $cart = Cart::where(['user_id' => Auth::user()->id])->first();
        $total = 0;
        foreach ($cart->products as $product) {
            $order->products()->attach($product, ['quantity' => $product->pivot->quantity, 'price' => $product->price]);
            $total += $product->price * $product->pivot->quantity;
        }
        $order->total_price = $total;
        $order->save();
        $cart->delete();
        return response()->json(
            ["message" => "Order created"]
        );
    }
}
