<?php

namespace Tests\Unit\Models;

use App\Models\OrderStatus;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_should_belong_to_one_order()
    {
        $order = factory(Order::class)->create();
        $status = OrderStatus::first();
        $this->assertEquals($status->order_id, $order->id);
        $this->assertInstanceOf(Order::class, $status->order);
    }
}
