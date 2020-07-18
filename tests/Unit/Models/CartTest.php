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
        $cart = Cart::addItem(1, $this->product);
        $this->assertCount(1, Cart::all());
    }

    /** @test */
    public function it_should_remove_product_to_cart_if_quantity_is_less_than_zero()
    {
        $cart = Cart::addItem(1, $this->product, -1);
        $cart = Cart::addItem(1, $this->product, 1);
        $cart = Cart::addItem(1, $this->product, -1);
        $this->assertEquals(1, Cart::first()->quantity);
    }
    /** @test */
    public function it_should_increment_quantity_of_item_instead_of_adding_a_new_one()
    {
        $cart = Cart::addItem(1, $this->product);
        $cart = Cart::addItem(1, $this->product);
        $cart = Cart::addItem(1, $this->product);
        $this->assertCount(1, Cart::all());
        $this->assertEquals(3, Cart::first()->quantity);
    }

    /** @test */
    public function it_should_update_cart_item_quantity()
    {
        $cart = Cart::addItem(1, $this->product);
        $this->assertCount(1, Cart::all());
        Cart::updateItem(1, $this->product['product_id'], 20);

        $this->assertEquals(20, Cart::first()->quantity);
    }
    /** @test */
    public function it_should_remove_cart_item()
    {
        $cart = Cart::addItem(1, $this->product);
        $this->assertCount(1, Cart::all());
        Cart::removeItem(1, [$this->product['product_id']]);

        $this->assertCount(0, Cart::all());
    }
    /** @test */
    public function it_should_do_nothing_if_cart_item_does_not_exist()
    {
        $removed = Cart::updateItem(1, $this->product['product_id']);
        $this->assertFalse($removed);
    }

    /** @test */
    public function it_should_delete_cart_item_if_quantity_is_zero()
    {
        $cart = Cart::addItem(1, $this->product);
        $this->assertCount(1, Cart::all());
        $removed = Cart::updateItem(1, $this->product['product_id'], 0);
        $this->assertCount(0, Cart::all());
    }
    /** @test */
    public function it_should_return_cart_items_added_by_user()
    {
        $cart = Cart::addItem(1, $this->product);
        $this->product['product_id'] = 2;
        $cart = Cart::addItem(1, $this->product);
        $this->product['product_id'] = 3;
        $cart = Cart::addItem(1, $this->product);
        $this->product['product_id'] = 4;
        $cart = Cart::addItem(1, $this->product);

        $this->assertCount(4, Cart::items(1)->get());
    }


    /** @test */
    public function it_should_get_cart_total_items()
    {
        $cart = Cart::addItem(1, $this->product);
        $this->product['product_id'] = 2;
        $cart = Cart::addItem(1, $this->product);
        $this->product['product_id'] = 3;
        $cart = Cart::addItem(1, $this->product);
        $this->product['product_id'] = 4;
        $cart = Cart::addItem(1, $this->product);
        $this->assertEquals(4, Cart::getItemsCount(1));
    }

    /** @test */
    public function it_should_get_cart_total_value()
    {
        $cart = Cart::addItem(1, $this->product, 10); //12 * 10 = 120
        $this->product['product_id'] = 2;
        $this->product['unit_price'] = 20;
        $this->product['discount'] = .50; // Unit price is now 10
        $cart = Cart::addItem(1, $this->product, 5); //10 * 5 = 50
        $this->assertEquals(170, Cart::getTotal(1));
    }
}
