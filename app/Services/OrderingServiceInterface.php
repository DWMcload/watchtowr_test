<?php

namespace App\Services;

use App\Models\Cart;

interface OrderingServiceInterface
{
    public function addProductToCart($product_id, $quantity): bool;

    public function removeProductFromCart($product_id): bool;

    public function checkoutCart(): bool;
}
