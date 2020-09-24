<?php

namespace Tests\Feature\controllers;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use Illuminate\Http\Response;

class OrdersControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $userId = 1;

    protected function setUp():void
    {
        parent::setUp();
        $this->order = factory(Order::class)->create(['user_id'=>$this->userId]);
    }


    /** @test */
    public function it_should_list_all_orders_of_a_user()
    {
        $this->withoutExceptionHandling();
        $userId = 1;

        $orders = factory(Order::class, 19)->create([
            'user_id'=>$userId
        ]);

        $orders = factory(Order::class, 19)->create([
            'user_id'=>123
        ]);
        $response = $this->get("/api/$userId/orders");
        $response->assertOk();
        $this->assertCount(15, $response->json()["data"]);
    }

    /** @test */
    public function it_should_get_specific_order()
    {
        $response = $this->get("/api/orders/{$this->order->id}")->assertOk();
    }

    /** @test */
    public function it_should_update_status_of_order()
    {
        $this->put("/api/orders/{$this->order->id}", ['status'=>OrderStatus::PROCESSING])
        ->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertEquals($this->order->fresh()->status, OrderStatus::PROCESSING);
    }

    /** @test */
    public function it_should_create_new_order()
    {
        $this->withoutExceptionHandling();
        $data = factory(Order::class)->make();

        $this->post('/api/orders/', $data->toArray())->assertCreated();
        $this->assertDatabaseHas('orders', ['user_id'=> $data->user_id, 'amount'=>$data->amount]);
        $this->assertCount(1, Order::orderBy('id', 'desc')->first()->statuses);
    }
}
