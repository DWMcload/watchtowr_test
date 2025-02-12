<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderingService implements OrderingServiceInterface
{
    public function addProductToCart($product_id, $quantity): bool
    {
        //TODO: as optional upgrade, implement an "attachOrUpgrade" method for Cart Model which checks if the given
        // product is already in the cart to avoid adding the same item multiple times.
        // This is out of scope of the current test, but thought about.

        try {
            $cart = $this->getCart();
            $cart->products()->attach($product_id, ['quantity' => $quantity]);
            $cart->save();
        }
        catch (\Exception $exception){
            Log::error($exception->getMessage());
            return false;
        }

        return true;
    }

    public function removeProductFromCart($product_id): bool
    {
        //TODO: similarly to the above, quantity management can be added later here

        try {
            $cart = $this->getCart();
            $cart->products()->detach($product_id);
            $cart->save();
        }
        catch (\Exception $exception){
            Log::error($exception->getMessage());
            return false;
        }

        return true;
    }

    public function checkoutCart(): bool
    {
        try {
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->save();
            $cart = $this->getCart();
            $total = 0;
            foreach ($cart->products as $product) {
                $order->products()->attach($product, ['quantity' => $product->pivot->quantity, 'price' => $product->price]);
                $total += $product->price * $product->pivot->quantity;
            }
            $order->total_price = $total;
            $order->save();
            $cart->delete();
        }
        catch (\Exception $exception){
            Log::error($exception->getMessage());
            return false;
        }

        return true;
    }

    private function getCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => Auth::user()->id]);
    }
}
