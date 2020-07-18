<?php

namespace Tests\Unit\Models;

use App\Models\Cart;
use Tests\TestCase;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        $faker = resolve(Faker::class);
        $this->product = [
            "product_id" => '1',
            "name" => "Sample Product",
            "sku" => "123",
            "unit_price" => "12",
            "discount" => 0
        ];
    }
    /** @test */
    public function it_should_add_product_to_cart()
    {
        $cart = Cart::add(1, $this->product);
        $this->assertCount(1, Cart::all());
    }

    /** @test */
    public function it_should_remove_product_to_cart_if_quantity_is_less_than_zero()
    {
        $cart = Cart::add(1, $this->product, -1);
        $cart = Cart::add(1, $this->product, 1);
        $cart = Cart::add(1, $this->product, -1);
        $this->assertEquals(1, Cart::first()->quantity);
    }
    /** @test */
    public function it_should_increment_quantity_of_item_instead_of_adding_a_new_one()
    {
        $cart = Cart::add(1, $this->product);
        $cart = Cart::add(1, $this->product);
        $cart = Cart::add(1, $this->product);
        $this->assertCount(1, Cart::all());
        $this->assertEquals(3, Cart::first()->quantity);
    }

    /** @test */
    public function it_should_remove_item_in_cart_if_quantity_is_zero()
    {
        Cart::add(1, $this->product);
        $this->assertCount(1, Cart::all());
        Cart::updateCartItem(1, $this->product['product_id']);
        $this->assertCount(0, Cart::all());
    }

    /** @test */
    public function it_should_update_cart_item_quantity()
    {
        $cart = Cart::add(1, $this->product);
        $this->assertCount(1, Cart::all());
        Cart::updateCartItem(1, $this->product['product_id'], 20);

        $this->assertEquals(20, Cart::first()->quantity);
    }
    /** @test */
    public function it_should_remove_cart_item()
    {
        $cart = Cart::add(1, $this->product);
        $this->assertCount(1, Cart::all());
        Cart::removeCartItem(1, $this->product['product_id']);

        $this->assertCount(0, Cart::all());
    }
    /** @test */
    public function it_should_do_nothing_if_cart_item_does_not_exist()
    {
        $removed = Cart::removeCartItem(1, $this->product['product_id']);
        $this->assertFalse($removed);
        $removed = Cart::updateCartItem(1, $this->product['product_id']);
        $this->assertFalse($removed);
    }
}
