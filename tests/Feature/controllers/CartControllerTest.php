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
    public function it_should_list_all_the_items_in_the_cart()
    {
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->product['product_id'] = 2;
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->post('/api/2/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->withoutExceptionHandling();
        $response  = $this->get('/api/1/cart');
        $response->assertStatus(200);
        $this->assertCount(2, $response->json()['data']);
        $response  = $this->get('/api/2/cart');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json()['data']);
    }

    /** @test */
    public function it_should_add_item_to_cart()
    {
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseHas('carts', ['user_id' => 1, 'product_id' => $this->product['product_id']]);
        $this->assertDatabaseCount('carts', 1);
        $this->product['product_id'] = 2;
        $this->post('/api/1/cart', $this->product + ['quantity' => 2])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseCount('carts', 2);
    }

    /** @test */
    public function it_should_update_cart_item()
    {
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->put('/api/1/cart', ['product_id' => $this->product['product_id'], 'quantity' => 2])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseHas('carts', ['user_id' => 1, 'product_id' => $this->product['product_id'], 'quantity' => 2]);
        $this->assertDatabaseCount('carts', 1);
    }

    /** @test */
    public function it_should_remove_item_from_cart()
    {
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->delete('/api/1/cart', ['product_id' => $this->product['product_id']])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseCount('carts', 0);
    }

    /** @test */
    public function it_should_remove_multiple_items_from_cart()
    {
        $this->withoutExceptionHandling();
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->product['product_id'] = 2;
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->product['product_id'] = 3;
        $this->delete('/api/1/cart', ['product_id' => [1, 2, 3]])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseCount('carts', 0);
    }

    /** @test */
    public function it_should_remove_multiple_items_from_cart_comma_delimiter()
    {
        $this->withoutExceptionHandling();
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->product['product_id'] = 2;
        $this->post('/api/1/cart', $this->product + ['quantity' => 1])->assertStatus(Response::HTTP_ACCEPTED);
        $this->product['product_id'] = 3;
        $this->delete('/api/1/cart', ['product_id' => "1,2"])->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseCount('carts', 0);
    }
}
