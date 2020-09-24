<?php

namespace Tests\Feature\controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_add_status_to_order()
    {
        $order = factory(Order::class)->create();
        $this->post("/api/orders/$order->id/status", [
            'status'=> OrderStatus::PROCESSING,
            'remarks'=> OrderStatus::PROCESSING_MESSAGE
        ])->assertCreated();
        $this->assertEquals(OrderStatus::PROCESSING, $order->fresh()->status);
        $this->assertDatabaseCount('order_statuses', 2);
        $this->post("/api/orders/$order->id/status", [
            'status'=> OrderStatus::SHIPPED,
            'remarks'=> OrderStatus::SHIPPED_MESSAGE
        ])->assertCreated();
        $this->assertEquals(OrderStatus::SHIPPED, $order->fresh()->status);
        $this->assertDatabaseCount('order_statuses', 3);
    }
}
