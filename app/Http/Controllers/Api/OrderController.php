<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Services\OrderingServiceInterface;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private readonly OrderingServiceInterface $orderingService)
    {
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->with('products')->get();

        return response()->json(
            $orders
        );
    }

    public function checkout(CheckoutRequest $request)
    {
        $this->orderingService->checkoutCart();
        return response()->json(
            ["message" => "Order created"]
        );
    }
}
