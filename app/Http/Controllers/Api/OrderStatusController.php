<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderResource;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderStatusController extends Controller
{
    //
    public function store(Order $order, Request $request)
    {
        $order->addStatus($request->status, $request->remarks);
        return (new OrderResource($order))->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
