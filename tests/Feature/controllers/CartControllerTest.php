<?php

namespace Tests\Feature\controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = [
            "product_id" => '1',
            "name" => "Sample Product",
            "sku" => "123",
            "unit_price" => "12",
            "discount" => 0
        ];
    }

    /** @test */
    public function it_should_add_item_to_cart()
    {
        $this->post('/api/cart/', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->post('/api/cart/', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseHas('carts', ['user_id' => 1, 'product_id' => $this->product['product_id']]);
        $this->assertDatabaseCount('carts', 1);
        $this->product['product_id'] = 2;
        $this->post('/api/cart/', $this->product + ['quantity' => 2])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseCount('carts', 2);
    }

    /** @test */
    public function it_should_update_cart_item()
    {
        $this->post('/api/cart/', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->put('/api/cart/1', ['product_id' => $this->product['product_id'], 'quantity' => 2])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseHas('carts', ['user_id' => 1, 'product_id' => $this->product['product_id'], 'quantity' => 2]);
        $this->assertDatabaseCount('carts', 1);
    }

    /** @test */
    public function it_should_remove_item_from_cart()
    {
        $this->post('/api/cart/', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->delete('/api/cart/1', ['product_id' => $this->product['product_id']])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseCount('carts', 0);
    }

    /** @test */
    public function it_should_remove_multiple_items_from_cart()
    {
        $this->withoutExceptionHandling();
        $this->post('/api/cart/', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->product['product_id'] = 2;
        $this->post('/api/cart/', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->product['product_id'] = 3;
        $this->delete('/api/cart/1', ['product_id' => [1, 2, 3]])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseCount('carts', 0);
    }
}
