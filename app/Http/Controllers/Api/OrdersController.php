<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class OrdersController extends Controller
{
    //
    public function index($userId)
    {
        // list all orders
        $orders = Order::collection($userId);
        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        // Cross check product stocks
        // Cross check total amount
        // Cross check user
        // Create Order
        // Notify Admin
        // Send Email
        // Delete cart items included in this checkout
        $order = Order::create($request->only(
            'user_id',
            'amount',
            'user',
            'payment_type',
            'shipping_type',
            'shipping_information',
            'shipping_charge',
            'discount',
            'cart'
        ));
        $cartIds = Arr::pluck($order->cart, 'product_id');
        Cart::removeItem($request->user_id, $cartIds);
        return (new OrderResource($order))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(Order $order, Request $request)
    {
        $order->update($request->only('status', 'payment_received', 'remarks'));
        // TODO Send Email regarding order status update
        return (new OrderResource($order))->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
