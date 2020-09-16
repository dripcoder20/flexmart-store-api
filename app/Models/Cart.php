<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];

    public static function addItem($userId, $product, $quantity = 1)
    {
        //if quantity is less than 1, update quantity to 0
        $quantity = $quantity > 0 ? $quantity : 0;

        // if item exist, just add quantity
        $cart = self::exist($userId, $product['product_id'])->first();
        if ($cart) {
            $cart->increment('quantity', $quantity);
            return $cart;
        }

        // do nothing if $quantity is less than 1
        if ($quantity < 1) {
            return null;
        }

        // else add item to cart
        $cartItem = array_merge([
            'user_id' => $userId,
            'quantity' => $quantity
        ], $product);

        $cart = self::create($cartItem);
        return $cart;
    }

    public static function updateItem($userId, $productId, $quantity = 0)
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

    public static function removeItem($userId, $productId)
    {
        self::where('user_id', $userId)->whereIn('product_id', $productId)->delete();
    }

    public function scopeExist($query, $userId, $productId)
    {
        return $query->where('user_id', $userId)->where('product_id', $productId);
    }

    public function scopeItems($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public static function getItemsCount($userId)
    {
        return self::items($userId)->count();
    }

    public static function getTotal($userId)
    {
        $collection = self::items($userId)->get();

        return $collection->sum->itemTotal();
    }

    public function itemTotal()
    {
        $discount = $this->unit_price * $this->discount;
        return $this->quantity * ($this->unit_price - $discount);
    }

    public static function collection($userId)
    {
        return self::where('user_id', $userId)->orderBy("id")->get();
    }
}
