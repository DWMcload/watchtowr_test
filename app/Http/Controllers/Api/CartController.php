<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\RemoveProductRequest;
use App\Models\Cart;
use App\Services\OrderingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function __construct(private readonly OrderingServiceInterface $orderingService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
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
        if($this->orderingService->addProductToCart($request->product_id, $request->quantity)){
            return response()->json(
                ["message" => "Product added"]
            );
        }
        return response()->json(
            ["message" => "Product not added"], 422
        );


    }

    public function removeProduct(RemoveProductRequest $request): JsonResponse
    {
        if($this->orderingService->removeProductFromCart($request->product_id))
        {
            return response()->json(
                ["message" => "Product removed"]
            );
        }
        return response()->json(
            ["message" => "Product not removed"], 422
        );
    }
}
