<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function index($userId)
    {
        $cartCollection = Cart::collection($userId);
        return CartResource::collection($cartCollection);
    }
    //
    public function store($userId, Request $request)
    {
        Cart::addItem(
            $userId,
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
