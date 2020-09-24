<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_related_to_statuses()
    {
        $order = factory(Order::class)->create();
        $this->assertInstanceOf(OrderStatus::class, $order->statuses->first());
    }

    /** @test */
    public function it_should_add_default_status_when_created()
    {
        $order = factory(Order::class)->create();
        $this->assertEquals(\App\Enums\OrderStatus::PENDING, $order->statuses->first()->status);
    }

    /** @test */
    public function it_should_add_more_status_updates_for_an_order()
    {
        $order = factory(Order::class)->create();
        $order->addStatus(\App\Enums\OrderStatus::PROCESSING, \App\Enums\OrderStatus::PROCESSING_MESSAGE);
        $order->addStatus(\App\Enums\OrderStatus::SHIPPED, \App\Enums\OrderStatus::SHIPPED);
        $this->assertCount(3, $order->statuses);
    }
}
