<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $with = ['statuses'];
    //
    protected $casts =[
        'user'=>'array',
        'shipping_information'=> 'array',
        'cart'=> 'array',
        'created_at'=>'datetime:Y-m-d H:i:s',
        'updated_at'=>'datetime:Y-m-d H:i:s'
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function (Order $order) {
            $order->tracking_number = date('Y') . "-" . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $order->addStatus();
            $order->save();
        });
    }

    public static function collection($userId)
    {
        return self::where('user_id', $userId)->orderBy('id', 'desc')->paginate(15);
    }


    public function statuses()
    {
        return $this->hasMany(\App\Models\OrderStatus::class);
    }

    public function addStatus($status = OrderStatus::PENDING, $remarks = OrderStatus::PENDING_MESSAGE)
    {
        $this->statuses()->create(['status'=> $status, 'remarks'=> $remarks]);
        $this->status = $status;
        $this->save();
    }
}
