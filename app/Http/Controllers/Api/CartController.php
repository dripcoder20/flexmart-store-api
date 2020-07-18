<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    //
    public function store(Request $request)
    {
        $userId = 1; // Todo Change in the future
        Cart::addItem(
            1,
            $request->only(
                ['product_id', 'name', 'sku', 'unit_price', 'discount', 'metadata']
            )
        );
        return (CartResource::collection(Cart::items($userId)->get()))->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function update($userId, Request $request)
    {
        Cart::updateItem($userId, $request->product_id, $request->quantity);

        return (CartResource::collection(Cart::items($userId)->get()))->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy($userId, Request $request)
    {
        $productIds = is_array($request->product_id) ? $request->product_id : [$request->product_id];
        Cart::removeItem($userId, $productIds);

        return (CartResource::collection(Cart::items($userId)->get()))->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
