<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserStoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_product_list()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)->assertJsonCount(5);
    }

    public function test_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/add-to-cart', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response->assertStatus(200)->assertJson(["message" => "Product added"]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'cart_id' => 1
        ]);
        $this->assertDatabaseHas('cart_product', [
            'product_id' => $product->id,
            'cart_id' => 1,
            'quantity' => 1
        ]);
    }

    public function test_user_can_checkout_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        //Cart::create([ 'user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1 ]);

        $this->actingAs($user)->postJson('/api/add-to-cart', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($user)->postJson('/api/checkout');

        $response->assertStatus(200)->assertJson(["message" => "Order created"]);

        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'order_id' => $response->json('order_id')]);
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);
    }

    public function test_user_can_view_orders()
    {
        $user = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/orders');

        $response->assertStatus(200)->assertJsonCount(3);
    }
}
