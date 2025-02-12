<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\RemoveProductRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::user()->id]);
        return response()->json(
            $cart->with('products')->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addProduct(AddProductRequest $request): JsonResponse
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::user()->id]);
        //TODO: as optional upgrade, create an "attachOrUpgrade" method which checks if the given product is already in
        // the cart to avoid adding the same item multiple times - this is out of scope of the current test, but thought about
        $cart->products()->attach($request->product_id, ['quantity' => $request->quantity]);
        $cart->save();
        return response()->json(
            ["message" => "Product added"]
            //$cart->with('products')->get()
        );
    }

    public function removeProduct(RemoveProductRequest $request): JsonResponse
    {
        $cart = Cart::where(['user_id' => Auth::user()->id])->first();
        //TODO: similarly to the above, quantity management can be added later here
        $cart->products()->detach($request->product_id);
        return response()->json(
            ["message" => "Product removed"]
            //$cart->with('products')->get()
        );
    }
}
