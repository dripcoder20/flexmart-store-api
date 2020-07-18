<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];

    public static function add($userId, $product, $quantity = 1)
    {
        //if quantity is less than 1, update quantity to 1
        $quantity = $quantity > 0 ? $quantity : 0;

        // if item exist, just add quantity
        // else add item to cart
        $cart = self::exist($userId, $product['product_id'])->first();
        if ($cart) {
            $cart->increment('quantity', $quantity);
            return $cart;
        }

        if ($quantity < 1) {
            return null;
        }

        $cartItem = array_merge([
            'user_id' => $userId,
            'quantity' => $quantity
        ], $product);

        $cart = self::create($cartItem);
        return $cart;
    }

    public static function updateCartItem($userId, $productId, $quantity = 0)
    {
        $cart = self::exist($userId, $productId)->first();
        if (! $cart) {
            return false;
        }

        if ($quantity === 0) {
            $cart->delete();
            return true;
        }
        return $cart->update(['quantity' => $quantity]);
    }

    public static function removeCartItem($userId, $productId)
    {
        if ($cart = self::exist($userId, $productId)->first()) {
            $cart->delete();
            return true;
        }
        return false;
    }

    public function scopeExist($query, $userId, $productId)
    {
        return $query->where('user_id', $userId)->where('product_id', $productId);
    }
}
